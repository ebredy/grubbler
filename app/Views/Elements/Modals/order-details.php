<div class="modal fade" id="order-<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="order-<?php echo $id; ?>-label" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <?php echo $restaurant['restaurant']; ?></h4>
                <p class="text-muted"><?php echo $restaurant['full_address']; ?></p>
            </div>
            <div class="modal-body">
                <table class="cart">
                    <?php foreach( $items as $cart_id => $item ) { ?>
                        <tr>
                            <td>
                                <h4><?php echo $item['item']; ?></h4>
                                <p><?php echo $item['quantity'] . ' items at $' . ltrim( $item['price'], '$' ) . ' each'; ?></p>
                                <p class="instructions">
                                    <?php if ( !empty( $item['instructions'] ) ) { ?>
                                        <span class="label label-info">Instructions</span> <?php echo $item['instructions']; ?>
                                    <?php } ?>
                                </p>
                            </td>
                            <td class="price">
                                <?php echo '$' . $item['total_price']; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                    <tr>
                        <td>Food/Beverage Total:</td>
                        <td class="price">$<?php echo $summary['item_total']; ?></td>
                    </tr>
                    <tr>
                        <td>Sales Taxes:</td>
                        <td class="price">$<?php echo $summary['sales_tax']; ?></td>
                    </tr>
                    <tr>
                        <td>Select Tip:</td>
                        <td>
                            <select name="tip">
                                <option value="2">$2.00</option>
                                <option value="5">$5.00</option>
                                <option value="10">$10.00</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                    <tr class="total-cost text-danger">
                        <td>Total</td>
                        <td>$<span id="cart-total"><?php echo $summary['grand_total']; ?></span></td>
                        <input type="hidden" name="grand_total" value="<?php echo $summary['grand_total']; ?>">
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>