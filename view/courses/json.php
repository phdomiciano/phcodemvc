<form name="formJSON" method="post" action="/courses/json">
    <?=$auth->token();?>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="form-group">
                <h4>List of courses in JSON format</h4>
                <label><?=json_encode($courses);?></label>
            </div>
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="form-group">
                <label>Decode test</label>
                <input type="text" class="form-control mb-2" name="json_test" placeholder="Insert JSON value for a test">
                <button type="submit" class="btn btn-primary">Decode</button>
                <?php if(!is_null($jsonDecode)){ ?>
                    <br />
                    <label><?=var_dump($jsonDecode);?></label>
                <?php } ?>
            </div>
        </li>
    </ul>
    <br /><br />
    <a href="/courses/index" class="btn btn-secondary">Back</a>
</form>