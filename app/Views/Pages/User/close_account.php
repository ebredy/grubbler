<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Close Account</h4>
    </div>
    <form class="narrow" action="/account/close" method="POST">
        <p class="small alert alert-danger"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> WARNING! This will permanently delete your account and CANNOT be undone</p>
        <div class="form-group well">
            <label for="card_number">Current Password</label>
            <input type="password" name="current_password" class="form-control input-sm" maxlength="25" placeholder="Current Password" autocomplete="off"/>
            <?php if( !empty( $errors['current_password'] ) ) echo '<span class="text-warning">' . $errors['current_password'] . '</span>' ;?>
        </div>
        <button type="submit" class="btn btn-danger btn-md">Permanently Delete Account</button>
    </form>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>