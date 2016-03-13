<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <?php echo $__view->render('app/Views/Elements/address-suggestion.php'); ?>
    <div class="page-header">

        <h3>Update your delivery address.</h3>
        <p class="text-muted small">When finished, click the "Continue" button.</p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <form id="delivery-form" method="post" action="/addresses/<?php echo $id; ?>?_method=put<?php if(!empty($continue)){ echo "&continue=$continue"; }?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fname">First name</label>
                                <input type="text" class="form-control input-sm" value="<?php if(!empty($fname)){echo $fname;} ?>" name="fname" placeholder="First name">
                                <?php if( !empty( $errors['fname'] ) ) { ?>
                                    <p class="text-danger"><?php echo $errors['fname']; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lname">Last name</label>
                                <input type="text" class="form-control input-sm" value="<?php if(!empty($lname)){echo $lname;} ?>"name="lname" placeholder="Last name">
                                <?php if( !empty( $errors['lname'] ) ) { ?>
                                    <p class="text-danger"><?php echo $errors['lname']; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address_1">Address line 1:</label>
                    <input type="text" class="form-control input-sm" name="address_1" value="<?php if(!empty($address_1)){echo $address_1;} ?>" placeholder="Address line 1">
                    <?php if( !empty( $errors['address_1'] ) ) { ?>
                        <p class="text-danger"><?php echo $errors['address_1']; ?></p>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="address_2">Address line 2:</label>
                                <input type="text" class="form-control input-sm" name="address_2" value="<?php if(!empty($address_2)){echo $address_2;} ?>" placeholder="Address line 2">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address_2">Apt/Suite Number:</label>
                                <input type="text" class="form-control input-sm" name="apt_number" value="<?php if(!empty($apt_number)){echo $apt_number;} ?>" placeholder="Apt/suite number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input type="text" class="form-control input-sm" name="city" placeholder="City" value="<?php if(!empty($city)){echo $city;} ?>"  >
                                <?php if( !empty( $errors['city'] ) ) { ?>
                                    <p class="text-danger"><?php echo $errors['city']; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State:</label>
                                <?php echo $__view->render('app/Views/Elements/states.php' ); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="zip_code">Zip code:</label>
                                <input type="text" class="form-control input-sm" name="zip_code" placeholder="Zip code" value="<?php if(!empty($zip_code)){echo $zip_code;} ?>">
                                <?php if( !empty( $errors['zip_code'] ) ) { ?>
                                    <p class="text-danger"><?php echo $errors['zip_code']; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone number:</label>
                    <input type="text" class="form-control input-sm" name="phone" placeholder="Phone Number" value="<?php if(!empty($phone)){echo $phone;} ?>">
                    <?php if( !empty( $errors['phone'] ) ) { ?>
                        <p class="text-danger"><?php echo $errors['phone']; ?></p>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="phone">Delivery instructions (optional):</label>
                    <textarea class="form-control input-sm" name="instructions" placeholder="(optional) Enter any special delivery instructions" rows="2"><?php if(!empty($instructions)){echo $instructions;} ?></textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-danger btn-md">Update delivery address</button>
            </form>
        </div>
        <div class="col-md-6">
            <h4>Delivery Address Tip:</h4>
            <p>To help avoid delays in delivery, please make sure that your address is entered correctly. Save time and avoid frustration by entering the information in the appropriate boxes and double-checking for typos and other errors.</p>
        </div>
    </div>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>