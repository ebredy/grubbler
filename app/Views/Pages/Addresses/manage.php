<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container" xmlns="http://www.w3.org/1999/html">
    <?php echo $__view->render('app/Views/Elements/address-suggestion.php'); ?>
    <div class="page-header">

        <h3><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> My addresses.</h3>
        <p class="text-muted small">This page allows you to create new addresses, and edit or delete any existing ones.</p>
    </div>
    <div class="row">
        <div class="col-md-6 panel panel-default">
            <div class="panel-body">
                <h4>Add a new delivery address</h4>
                <?php echo $__view->render('app/Views/Elements/Forms/add-address.php' ); ?>
            </div>
        </div>
        <?php if ( !empty( $addresses ) ) { ?>
            <div class="col-md-5 col-md-offset-1">
                <div class="row">
                <?php
                $grids = 0;
                foreach ( $addresses as $address ) {
                $grids++;
                ?>
                    <div class="col-md-6 address-box">
                        <?php $street_address = rtrim( $address['address_1'] . ', ' . $address['apt_number'], ", " );
                        $street_address = rtrim( $street_address . ', ' . $address['address_2'], ", " ); ?>
                        <ul class="address small">
                            <li><b><?php echo $address['fname'] . ' ' . $address['lname']; ?></b></li>
                            <li><?php echo $street_address; ?></li>
                            <li><?php echo $address['city'] . ', ' . $address['state'] . ' ' . $address['zip_code']; ?></li>
                            <li><?php echo 'Phone: ' . $address['phone']; ?></li>
                            <?php if ( !empty( $address['instructions'] ) ) { ?>
                                <li><b>Instructions:</b> <span class="text-success"><?php echo $address['instructions']; ?></span></li>
                            <?php } ?>
                        </ul>
                        <div class="address-actions">
                            <div class="row">
                                <?php if( $grids === 1 ){ ?>
                                    <div class="col-md-12">
                                        <span class="current-address">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                            Current delivery address
                                        </span>
                                    </div>

                                <?php }else{ ?>
                                    <div class="col-md-12">
                                        <form method="post" action="/addresses/<?php echo $address['id']; ?>?_method=put&continue=checkout">
                                            <input type="hidden" name="is_current" value="1">
                                            <input class="btn btn-warning btn-xs" value="Deliver to this address" type="submit">
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-default btn-xs" href="/addresses/<?php echo $address['id']; ?>?continue=checkout">Edit</a>
                                </div>
                                <div class="col-md-6">
                                    <form method="post" action="/addresses/<?php echo $address['id']; ?>?_method=delete&continue=checkout">
                                        <input class="btn btn-default btn-xs" value="Delete" type="submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ( $grids % 2 == 0 ) { ?>
                        </div>
                        <div class="row">
                    <?php } ?>
                <?php } ?>
                        </div>
            </div>
        <?php }else{ ?>
        <div class="col-md-5 col-md-offset-1">

        <?php if ( empty( $addresses ) ) { ?>
                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <strong>0 saved addresses.</strong> You currently don't have any saved addresses. Use this page to get started!
                </div>
            <?php } ?>
            </div>
    <?php } ?>
    </div>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>