<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Forgot your password?</h4>
        <p class="text-muted">Just enter the email you signed up with and we'll let you reset it.</p>
    </div>
    <form class="narrow" method="POST" action="/account/reset_password">
        <table class="table">
            <tr>
                <td>
                    <input type="email" required name="email" class="form-control" placeholder="Email" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="Reset Password" class="btn btn-default" ></td>
            </tr>
        </table>
    </form>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>