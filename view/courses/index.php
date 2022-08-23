<a href="/courses/create" class="btn btn-primary mb-2">New course</a>
<a href="/courses/json" class="btn btn-info mb-2">Exibir JSON</a>
<ul class="list-group">
    <?php foreach ($courses as $course): ?>
        <li class="list-group-item d-flex justify-content-between">
            <?= $course->getName(); ?>
            <group>
                <form name="formDeleteCourse" action="/courses/destroy" method="post">
                    <a href="/courses/edit/<?= $course->getId() ?>" class="btn btn-primary btn-sm">Edit</a>
                    <?=$auth->token();?>
                    <input type="hidden" name="id" value="<?=$course->getId();?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </group>
        </li>
    <?php endforeach; ?>
</ul>
<br />
