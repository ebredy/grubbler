<div class="modal fade" id="add-address" tabindex="-1" role="dialog" aria-labelledby="menu-item-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="address-modal-content">
            <form id="delivery-form" method="post" action="/addresses">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Delivery Address</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname">First name</label>
                                    <input type="text" class="form-control input-sm" name="fname" placeholder="First name">
                                    <?php if( !empty( $errors['fname'] ) ) { ?>
                                        <p class="help-block"><?php echo $errors['fname']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lname">Last name</label>
                                    <input type="text" class="form-control input-sm" name="lname" placeholder="Last name">
                                    <?php if( !empty( $errors['lname'] ) ) { ?>
                                        <p class="help-block"><?php echo $errors['lname']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address_1">Address line 1:</label>
                        <input type="text" class="form-control input-sm" name="address_1" placeholder="Address line 1">
                        <?php if( !empty( $errors['address_1'] ) ) { ?>
                            <p class="help-block"><?php echo $errors['address_1']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="address_2">Address line 2:</label>
                        <input type="text" class="form-control input-sm" name="address_2" placeholder="Address line 2">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" class="form-control input-sm" name="city" placeholder="City">
                                    <?php if( !empty( $errors['city'] ) ) { ?>
                                        <p class="help-block"><?php echo $errors['city']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="state">State:</label>
                                    <?php echo $__view->render('app/Views/Elements/states.php'); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="zip_code">Zip code:</label>
                                    <input type="text" class="form-control input-sm" name="zip_code" placeholder="Zip code">
                                    <?php if( !empty( $errors['zip_code'] ) ) { ?>
                                        <p class="help-block"><?php echo $errors['zip_code']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone number:</label>
                        <input type="text" class="form-control input-sm" name="phone" placeholder="Phone Number">
                        <?php if( !empty( $errors['phone'] ) ) { ?>
                            <p class="help-block"><?php echo $errors['phone']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="phone">Delivery instructions (optional):</label>
                        <textarea class="form-control input-sm" name="instructions" placeholder="(optional) Enter any special delivery instructions" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="create-address">Add address</button>
                </div>
            </form>
        </div>
    </div>
</div>