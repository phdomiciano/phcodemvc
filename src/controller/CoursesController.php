<?php

namespace phcode\controller;

use phcode\model\Course;
use phcode\model\User;
use phcode\infra\View;
use phcode\infra\EntityManagerCreator;
use \Exception;
use phcode\infra\Auth;

class CoursesController extends Controller
{

    public function index(): void
    {
        $auth = new Auth();
        $user = $auth->user($this->entityManager);
        $courses = $user->getCourses();

        $view = new View("courses.index", "List of courses");
        $view->show(compact('courses'));
    }

    public function create(): void
    {
        $view = new View("courses.create", "New course");
        $view->show();
    }

    public function store(): void
    {
        if(!$this->request->validate()){
            header("Location: /courses/create");
            return;
        }
        $auth = new Auth();
        $user = $auth->user($this->entityManager);

        $course = new Course();
        $course->setName($this->request->get("name"));
        $course->setDescription($this->request->get("description"));

        $user->setCourse($course);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        View::setAlert("success","'".$course->getName()."' created with success!");
        header("Location: /courses/index");
        return;
    }

    public function edit(): void
    {
        $idcourse = $this->request->get("id");
        $course = $this->entityManager->find(Course::class, $idcourse);

        $auth = new Auth();
        $auth->verifyOwner($course);

        $view = new View("courses.create", "Edit course");
        $view->show(compact('course'));
    }

    public function update(): void
    {
        if(!$this->request->validate()){
            header("Location: /courses/edit/".$this->request->get("id"));
            return;
        }
        $course = $this->entityManager->find(Course::class, $this->request->get("id"));
        $course->setName($this->request->get("name"));
        $course->setDescription($this->request->get("description"));

        $auth = new Auth();
        $auth->verifyOwner($course);

        $this->entityManager->flush();
        View::setAlert("success","'".$course->getName()."' updated with success!");
        header("Location: /courses/index");
        return;
    }

    public function destroy(): void
    {
        $course = $this->entityManager->find(Course::class, $this->request->get("id"));

        $auth = new Auth();
        $auth->verifyOwner($course);

        $this->entityManager->remove($course);
        $this->entityManager->flush();
        View::setAlert("info","'".$course->getName()."' deleted with success!");
        header("Location: /courses/index");
        return;
    }

    public function showJSON(): void
    {
        $coursesRepository = $this->entityManager->getRepository(Course::class);
        $courses = $coursesRepository->findAll();

        $view = new View("courses.json", "Exibir JSON");

        $jsonDecode = null;
        if($this->request->get("json_test")){
            $jsonDecode = json_decode($this->request->get("json_test",false));
            if(json_last_error()){
                View::setAlert("danger","JSON ERROR: Syntax error, invalid JSON");
            } 
            else {
                View::setAlert("success","Valid JSON! Decode with success!");
            }
        }
        $view->verifyAlerts();
        $view->show(compact('courses','jsonDecode'));
    }

}