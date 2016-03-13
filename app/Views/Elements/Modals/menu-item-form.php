<form class="cart-item" method="<?php echo $method; ?>" action="<?php echo $action; ?>">
    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">
    <input type="hidden" name="menu_id" value="<?php echo $id; ?>">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="menu-item-label">
            <?php echo $item; ?>
            <?php if( !empty( $price ) ) { ?>
                <span class="label label-danger">$<span id="total-item-cost"><?php echo $total_price; ?></span></span>
            <?php } ?>
        </h4>
        <?php if( !empty( $price ) ) { ?>
            <p><input type="text" value="<?php echo $quantity; ?>" id="menu-item-qty" name="quantity" maxlength="3" > items at <b>$<span id="item-cost"><?php echo ltrim( $price, '$' ); ?></span></b> each</p>
        <?php } ?>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="message-text" class="control-label">Special Intrsuctions:</label>
            <textarea class="form-control" id="instructions" name="instructions"><?php if ( !empty($instructions) ){ echo $instructions; }; ?></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
    </div>
</form>