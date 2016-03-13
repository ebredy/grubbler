<div class="modal fade" id="add-address" tabindex="-1" role="dialog" aria-labelledby="menu-item-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="address-modal-content">
            <form id="delivery-form" method="post" action="/addresses">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Delivery Address</h4>
                </div>
                <div class="modal-body">
                    <form id="delivery-form" method="post" action="/addresses">
                        <?php foreach ( $found_addresses as $address ) { ?>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> <?php echo $address['full_address']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="create-address">Add address</button>
                </div>
            </form>
        </div>
    </div>
</div>