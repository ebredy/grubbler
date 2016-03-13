<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Login</h4>
        <p class="text-muted">Not yet a member? <a href="/signup">Sign up here</a></p>
    </div>
    <form class="narrow" method="POST" action="/signin">
        <table class="table">
            <tr>
                <td><input type="email" name="email" class="form-control" required placeholder="Email"></td>
            </tr>
            <tr>
                <td><input type="password" name="password" class="form-control" required placeholder="Password"></td>
            </tr>
            <tr>
                <td><input type="checkbox" name="remember"> Remember me - <a href="/account/reset_password">Forgot Password?</a></td>
            </tr>
            <tr>
                <td><input type="submit" value="Login" class="btn btn-default" ></td>
            </tr>
        </table>
    </form>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>