<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
    <div class="container">
        <?php echo $__view->render('app/Views/Elements/address-suggestion.php'); ?>
        <div class="page-header">

            <h3>Enter a new delivery address.</h3>
            <p class="text-muted small">When finished, click the "Continue" button.</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form id="delivery-form" method="post" action="/addresses">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> <?php echo $entered_address; ?>
                        </label>
                    </div>
                    <?php foreach ( $found_addresses as $address ) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> <?php echo $address['full_address']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>