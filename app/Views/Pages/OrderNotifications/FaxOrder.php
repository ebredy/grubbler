<?php echo $__view->render('app/Views/Elements/fax-letterhead.php'); ?>
    <div class="container">
        <div style="border: 1px black solid; padding: 30px;">
            <table style="margin: 10px;">
                <t><td colspan="3"><h2>A New Order, order # <?=$order_id?>,  from <?=$customer_first_name?> &nbsp;<?=$customer_last_name?> Has Been Placed</h2></td></tr>
            <tr><td colspan="3"><h2 class="lead" style="padding: 30 px;">Please provide access code <b><?=$order_confirmation_number?></b> followed by # for phone confirmation in 2 minutes.</h2></td></tr>
            <tr><td colspan="3"> <h2>Order#:<?=$order_id?></h2></td></tr>
            <tr><td colspan="3">Address: <?=$customer_address?>&nbsp; Apt: <?=$customer_apt_number?></td></tr></td></tr>
            <tr><td colspan="3">Address 2: <?=$customer_address_2?>&nbsp;</td></tr>
            <tr><td>City: <?=$customer_city?></td><td> State: <?=$customer_state?></td><td> zip code: <?=$customer_zipcode?></td></tr>
            <tr><td colspan="3">Order: <?php $orderdetails = json_decode($fax_message, true); ?></td></tr>
                                        
            <tr>
                <td colspan="3"><?php echo "<hr>"; 
                                        foreach($orderdetails['items'] as $item){
                                            echo "item: ". $item['item'] ."  qunatity: ".$item['quantity']."<br>";
                                            echo "instructions: ". $item['instructions'] ."  charged: $".$item['total_price']."<br>";
                                            echo "<hr>";
                                        }
                                        echo "sales tax: " .$orderdetails['summary']['sales_tax']."<br>";
                                        echo "tip: " .$orderdetails['summary']['tip']."<br>";
                                        echo "item total: " .$orderdetails['summary']['item_total']."<br>";
                                        echo "grand total: " .$orderdetails['summary']['grand_total']."<br>";
                                    ?>
                </td>
            </tr>
            <tr><td colspan="3">Instructions: <?=$customer_instructions?></td></tr>
            </table>
        </div>
    </div>
<?php echo $__view->render('app/Views/Elements/fax-footer.php'); ?>