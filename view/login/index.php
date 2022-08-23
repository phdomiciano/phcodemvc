<div class="d-flex p-2 justify-content-between">
    <form name="formLogin" method="post" action="/login/store">
        <?=$auth->token();?>
        <h1 class="display-6">Login</h1>
        <ul class="list-group">
            <div class="form-group">
                <li class="list-group-item">
                    <input type="text" class="form-control" name="email" placeholder="email">
                </li>
                <li class="list-group-item">
                    <input type="password" class="form-control" name="password" placeholder="password">
                </li>
            </div>
            <div>
                <a class="underlineHover" href="#">I forget my password.</a>
            </div>
        </ul>
        <br />
        <button type="submit" class="btn btn-primary">Enter</button>
    </form>
    <div class="d-flex justify-content-end">
        <div class="card" style="width: 50rem;">
            <div class="card-body">
                <h1 class="display-6">Are you new here?</h1> <h1 class="display-5">Create your user!</h1>
                <form name="formCreateUser" action="/user/store" method="post">
                    <?=$auth->token();?>
                    <ul class="list-group">
                        <div class="form-group">
                            <li class="list-group-item">
                                Name: <input type="text" class="form-control" name="name" placeholder="Insert your full name">
                            </li>
                            <li class="list-group-item">
                                E-mail: <input type="text" class="form-control" name="email" placeholder="Insert your e-mail">
                            </li>
                            <li class="list-group-item">
                                Password: <input type="password" class="form-control" name="password" placeholder="Create your password">
                            </li>
                            <li class="list-group-item">
                                Password confirmation: <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password">
                            </li>
                        </div>
                    </ul>
                    <br />
                    <button type="submit" class="btn btn-primary">Create user</button>
                </form>
            </div>
        </div>
    </div>
</div>

