<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Account Settings</h4>
    </div>
    <form class="narrow" action="/settings" method="POST">
        <p class="small alert alert-warning"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Your settings were last updated on <strong><?php echo $__helper->formatDate( $last_edited ); ?></strong></p>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="expiration">First Name:</label>
                    <input type="text" name="fname" class="form-control input-sm" maxlength="25" placeholder="First name" value="<?php if(isset($fname)){ echo $fname;}?>" autocomplete="off"/>
                    <?php if( !empty( $errors['fname'] ) ) echo '<span class="text-warning">' . $errors['fname'] . '</span>' ;?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="expiration">Last Name:</label>
                    <input type="text" name="lname" class="form-control input-sm" maxlength="25" placeholder="Last name" value="<?php if(isset($lname)){ echo $lname;}?>" autocomplete="off"/>
                    <?php if( !empty( $errors['lname'] ) ) echo '<span class="text-warning">' . $errors['lname'] . '</span>' ;?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="card_number">Email:</label>
            <input type="email" name="email" class="form-control input-sm" maxlength="50" placeholder="Email"  value="<?php if(isset($email)){ echo $email;}?>" autocomplete="off"/>
            <?php if( !empty( $errors['email'] ) ) echo '<span class="text-warning">' . $errors['email'] . '</span>' ;?>
        </div>
        <div class="form-group">
            <label for="card_number">Password:</label>
            <input type="password" name="password" class="form-control input-sm" maxlength="25" placeholder="New Password" autocomplete="off"/>
            <?php if( !empty( $errors['password'] ) ) echo '<span class="text-warning">' . $errors['password'] . '</span>' ;?>
        </div>
        <div class="form-group well">
            <label for="card_number">Current Password</label>
            <input type="password" name="current_password" class="form-control input-sm" maxlength="25" placeholder="Current Password" autocomplete="off"/>
            <?php if( !empty( $errors['current_password'] ) ) echo '<span class="text-warning">' . $errors['current_password'] . '</span>' ;?>
        </div>
        <button type="submit" class="btn btn-default btn-md">Update Settings</button>
    </form>
    <hr>
    <p class="text-muted small">Looking to close your account? <a href="/account/close" class="small text-danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Close Account</a></p>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>