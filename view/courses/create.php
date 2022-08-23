<form name="formCourse" method="post" action="<?= isset($course) ? '/courses/update' : '/courses/store'; ?>">
    <?=$auth->token();?>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name of course" name="name" value="<?= isset($course) ? $course->getName() : '';?>">
                <?php if(isset($course)){ ?>
                    <input type="hidden" id="id" name="id" value="<?=$course->getId();?>">
                <?php } ?>
            </div>
        </li>
        <li class="list-group-item">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" placeholder="Description of course" name="description" value="<?= isset($course) ? $course->getDescription() : '';?>">
            </div>
        </li>
    </ul>
    <br />
    <a href="/courses/index" class="btn btn-secondary">Back</a>
    <button type="submit" class="btn btn-primary">Save</button>
</form>