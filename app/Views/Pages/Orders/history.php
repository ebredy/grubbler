<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>Order History</h4>
    </div>
    <div class="row">
        <table class="table">
            <tr>
                <th>Receipt Number</th>
                <th>Order Placed</th>
                <th>Total Cost</th>
                <th>Restaurant</th>
                <th>Delivery Address</th>
            </tr>
            <?php foreach( $orders as $order ) { ?>
                <tr class="small">
                    <td><a href="javascript:void(0)" data-toggle="modal" data-target="#order-<?php echo $order['id']; ?>" ><?php echo $order['receipt_number']; ?></a></td>
                    <td><?php echo $__helper->formatDate( $order['created_on'] ); ?></td>
                    <td>$<?php echo $order['amount']; ?></td>
                    <td>
                        <ul class="address">
                            <li><strong><a href="/restaurants/<?php echo $order['restaurant']['id']; ?>"><?php echo $order['restaurant']['restaurant']; ?></a></strong></li>
                            <li class="text-muted"><?php echo $order['restaurant']['full_address']; ?></li>
                        </ul>
                    </td>
                    <td>
                        <ul class="address">
                            <li class="small"><strong><?php echo $order['delivered_to']['fname'] . ' ' . $order['delivered_to']['lname']; ?></strong></li>
                            <li class="text-muted"><?php echo $__helper->formatAddress( $order['delivered_to'] ); ?></li>
                            <li class="text-muted"><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> <?php echo $order['delivered_to']['phone']; ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>
<?php foreach( $orders as $order ) { ?>
    <?php echo $__view->render('app/Views/Elements/Modals/order-details.php', $order ); ?>
<?php } ?>
