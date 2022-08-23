<?php

namespace phcode\model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use JsonSerializable;

#[Entity]
#[Table(name: "users")]
class User
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id;
    
    #[Column]
    private ?string $name;
    
    #[Column]
    private ?string $email;
    
    #[Column]
    private ?string $password;

    #[OneToMany(
        targetEntity: Course::class, 
        mappedBy: "user", 
        cascade: ["persist","remove"]
    )]
    public $courses;

    public function __construct(?string $name = null, ?string $email = null, ?string $password = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->courses = new ArrayCollection();
    }

    public function setName(string $name):void
    {
        $this->name = $name;
    }

    public function setEmail(string $email):void
    {
        $this->email = $email;
    }

    public function setPassword(string $password):void
    {
        $this->password = password_hash($password, PASSWORD_ARGON2I);
    }

    public function passwordVerify(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCourses(): mixed
    {
        return $this->courses;
    }

    public function setCourse(Course $course): void
    {
        $this->courses->add($course);
        $course->setUser($this);
    }

    public function getUserId(): int
    {
        return $this->id;
    }
}
