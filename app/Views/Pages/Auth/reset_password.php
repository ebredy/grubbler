<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Reset your password</h4>
        <p class="text-muted">Please enter your new password.</p>
    </div>
    <form class="narrow" method="POST" action="/account/reset_password?token=<?php echo $token; ?>">
        <table class="table">
            <tr>
                <td>
                    <input type="password" required name="password" class="form-control" placeholder="New Password" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="password" required name="cpassword" class="form-control" placeholder="Confirm Password" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="Reset Password" class="btn btn-default" ></td>
            </tr>
        </table>
    </form>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>