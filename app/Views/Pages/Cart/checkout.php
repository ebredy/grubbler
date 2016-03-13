<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h3>Review your order</h3>
    </div>
    <div class="row">
        <div class="col-md-5">
            <h4>Delivery Address - <a href="/addresses?continue=checkout">Change</a></h4>
            <?php $street_address = rtrim( $address['address_1'] . ', ' . $address['apt_number'], ", " );
            $street_address = rtrim( $street_address . ', ' . $address['address_2'], ", " ); ?>
            <ul class="address">
                <li><?php echo $address['fname'] . ' ' . $address['lname']; ?></li>
                <li><?php echo $street_address; ?></li>
                <li><?php echo $address['city'] . ', ' . $address['state'] . ' ' . $address['zip_code']; ?></li>
                <li><?php echo 'Phone: ' . $address['phone']; ?></li>
                <?php if ( !empty( $address['instructions'] ) ) { ?>
                    <li><span class="label label-default">Instructions:</span> <span class="text-success"><?php echo $address['instructions']; ?></span></li>
                <?php } ?>
                <li class="small text-muted"><a href="/addresses/<?php echo $address['id']; ?>?continue=checkout">Edit</a> this address</li>
            </ul>
            <hr>
            <h4>Payment Method</h4>
            <?php if ( !empty( $cards ) ) { ?>
                <form action="/checkout" method="POST" id="payment-form">
                    <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                    <div class="form-group">
                        <select class="form-control select-card" name="card_id">
                            <?php foreach( $cards as $card ) { ?>
                                <option data-toggle="saved-card" value="<?php echo $card['id']; ?>"><?php echo $card['brand']; ?> ending in <?php echo $card['last_4']; ?></option>
                            <?php } ?>
                            <option data-toggle="new-card" value="">Add New Card</option>
                        </select>
                    </div>
                    <div id="new-card-form">
                        <span id="payment-errors" role="alert" style="display: none"></span>
                        <div class="form-group">
                            <label for="card_number">Card number:</label>
                            <input type="text" class="form-control input-sm" maxlength="20" size="20" placeholder="Card number" id="stripe-number" data-stripe="number" autocomplete="off"/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expiration">Expiration MM:</label>
                                    <input type="text" class="form-control input-sm" maxlength="2" size="2" data-stripe="exp-month" id="stripe-exp-month" placeholder="MM" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expiration">Expiration YYYY:</label>
                                    <input type="text" class="form-control input-sm" maxlength="4" size="4" data-stripe="exp-year" id="stripe-exp-year" placeholder="YYYY" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cvc">CVC code:</label>
                                    <input type="text" class="form-control input-sm" maxlength="4" size="4" data-stripe="cvc" id="stripe-cvc" placeholder="CVC" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger btn-md">Submit Order</button>
                </form>
            <?php }else { ?>
                <?php echo $__view->render('app/Views/Elements/Forms/stripe-payment-form.php'); ?>
            <?php } ?>
        </div>
        <div class="col-md-5 col-md-offset-2 panel panel-default">
            <div id="cart" class="panel-body"></div>
        </div>
    </div>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>
<?php echo $__view->render('app/Views/Elements/Modals/menu-item.php'); ?>
<?php echo $__view->render('app/Views/Elements/Modals/add-address.php'); ?>