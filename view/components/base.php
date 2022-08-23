<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?=$this->title?> - Courses Manager</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php if($auth->validate()){ ?>
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="/courses/index" style="color:grey">Home</a>
            <group>
                <label class="display-8" style="color:grey">Courses of <?=$auth->user->getName()?> | </label>
                <a href="/login/destroy" class="btn btn-secondary btn-sm">Logout</a>
            </group>
        </nav>
    <?php } ?>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4"><?=$this->title?></h1>
            <hr class="my-4">
        </div>
        <?php foreach($this->errors as $error){ ?>
            <div class="alert alert-danger" role="alert">
                <?=$error?>
            </div>
        <?php } ?>
        <?php foreach($this->alerts as $alert){ ?>
            <div class="alert alert-<?=$alert['style']?>" role="alert">
                <?=$alert['text']?>
            </div>
        <?php } ?>

        <?php require_once __DIR__."/../".$this->file; ?>
    </div>
    <br />
</body>
</html>