<?php if( !empty( $items ) ) { ?>
    <div class="text-center">
        <p class="small text-muted"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Order summary for<br/>
            <a href="/restaurants/<?php echo $restaurant['id']; ?>"><?php echo $restaurant['restaurant']; ?></a></p>
    </div>

    <form id="cart-form" method="#" action="/checkout" autocomplete="off">
        <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
        <table class="cart">
            <?php foreach( $items as $cart_id => $item ) { ?>
                <tr>
                    <td>
                        <h4><?php echo $item['item']; ?></h4>
                        <p><a href="javascript:void(0)"
                              data-toggle="modal"
                              data-target="#menu-item"
                              data-cart-id="<?php echo $item['cart_id']; ?>"
                              data-menu-id="<?php echo $item['id']; ?>"
                              data-restaurant-id="<?php echo $item['restaurant_id']; ?>" >Edit</a> - <a href="javascript:void(0)" class="delete-item" data-menu-id="<?php echo $item['id']; ?>" data-cart_id="<?php echo $item['cart_id']; ?>" data-restaurant-id="<?php echo $item['restaurant_id']; ?>">Delete</a> - <?php echo $item['quantity'] . ' items at $' . ltrim( $item['price'], '$' ) . ' each'; ?></p>
                        <input type="hidden" name="items[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>">
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
                <input type="hidden" name="item_total" value="<?php echo $summary['item_total']; ?>">
            </tr>
            <tr>
                <td>Sales Taxes:</td>
                <td class="price">$<?php echo $summary['sales_tax']; ?></td>
                <input type="hidden" name="sales_tax" value="<?php echo $summary['sales_tax']; ?>">
            </tr>
            <tr>
                <td>Select Tip:</td>
                <td>
                    <select name="tip" id="tip">
                        <option value="2.00">2.00 tip</option>
                        <option value="<?=$summary['item_total'] *.10?>">$<?php echo money_format('%i',$summary['item_total'] *.10); ?> (10% tip)</option>
                        <option value="<?=$summary['item_total'] *.15?>">$<?php echo money_format('%i',$summary['item_total'] *.15); ?> (15% tip)</option>
                        <option value="<?=$summary['item_total'] *.20?>">$<?php echo money_format('%i',$summary['item_total'] *.20); ?> (20% tip)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><hr/></td>
            </tr>
            <tr class="total-cost text-danger">
                <td>Total</td>
                <td>$<span id="cart-total"><?php echo $summary['grand_total'] + 2.00; ?></span></td>
                <input type="hidden" name="grand_total" id="grand_total" original_grand_total="<?php echo $summary['grand_total']; ?>" value="<?php echo $summary['grand_total'] + 2.00; ?>">
            </tr>
        </table>
        <hr>
        <a href="/checkout" class="btn btn-sm btn-danger btn-block" id="ProceedToCheckout" >Proceed to Checkout</a>
        <script>
            //moved in the on ready  position from regular cart.js because it needs the entire css to be rendered to remove the css from view
            $(document).ready(function(event){
                
                        var pathname = document.location.pathname.substring(1);
   
                        var parts = pathname.split(/\//);
                        var firstURI = parts[0];


                        if( firstURI !== 'checkout'){

                            $('#ProceedToCheckout').addClass("btn btn-sm btn-danger btn-block").show();
                        }
                        else{
                            
                            $('#ProceedToCheckout').removeClass("btn btn-sm btn-danger btn-block").hide();
                            

                        }
                        
                    $('#tip').on('change', function(event){
                       
                       $('#cart-total').html( parseFloat( $('#grand_total').attr('original_grand_total') ) + parseFloat( $(this).val() ) );
                       $('#grand_total').val( parseFloat( $('#grand_total').attr('original_grand_total') ) + parseFloat( $(this).val() ) );
                       
                        $.post('/tip',{ tip:  parseFloat( $(this).val() )} )
                            .done( function(data){
                            
                                //console.log(data);
                        });
                    });                        

            });
        </script>
    </form>
<?php } else { ?>
    <p class="text-muted small">Your cart is currently empty</p>
<?php } ?>
