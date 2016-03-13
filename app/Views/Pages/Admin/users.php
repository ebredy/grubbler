<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Sign Up</h4>
        <p class="text-muted">Already a member? <a href="/signin">Login here</a></p>
    </div>
    <form class="narrow" action="/signup" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="expiration">First Name:</label>
                    <input type="text" name="fname" class="form-control input-sm" maxlength="25" placeholder="First name" required autocomplete="off"/>
                    <?php if( !empty( $errors['fname'] ) ) echo '<span class="text-warning">' . $errors['fname'] . '</span>' ;?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="expiration">Last Name:</label>
                    <input type="text" name="lname" class="form-control input-sm" maxlength="25" placeholder="Last name" required autocomplete="off"/>
                    <?php if( !empty( $errors['lname'] ) ) echo '<span class="text-warning">' . $errors['lname'] . '</span>' ;?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="card_number">Email:</label>
            <input type="email" name="email" class="form-control input-sm" maxlength="50" placeholder="Email" required autocomplete="off"/>
            <?php if( !empty( $errors['email'] ) ) echo '<span class="text-warning">' . $errors['email'] . '</span>' ;?>
        </div>
        <div class="form-group">
            <label for="card_number">Password:</label>
            <input type="password" name="password" class="form-control input-sm" maxlength="25" placeholder="Password" required autocomplete="off"/>
            <?php if( !empty( $errors['password'] ) ) echo '<span class="text-warning">' . $errors['password'] . '</span>' ;?>
        </div>
        <button type="submit" class="btn btn-danger btn-md">Sign Up</button>
    </form>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>