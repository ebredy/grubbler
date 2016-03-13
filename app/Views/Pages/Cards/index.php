<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="container">
    <div class="page-header">
        <h4>My Payment Information</h4>
    </div>
    <div class="row">
        <table class="table">
            <tr>
                <th>Type</th>
                <th>Credit Card Number</th>
                <th>Expiration Date</th>
                <th>Actions</th>
            </tr>
            <?php
            $counter = 0;
            foreach( $cards as $card ) {
                $counter++;
                ?>
                <tr class="small">
                    <td><?php echo $card['brand']; ?></td>
                    <td><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> ****<?php echo $card['last_4']; ?></td>
                    <td><?php echo $card['exp_month'] . '/' . $card['exp_year']; ?></td>
                    <td>
                    <div class="row">
                        <div class="col-md-3">
                            <button
                                class="btn btn-default btn-xs"
                                data-id="<?php echo $card['id']; ?>"
                                data-brand="<?php echo $card['brand']; ?>"
                                data-exp-year="<?php echo $card['exp_year']; ?>"
                                data-last4="<?php echo $card['last_4']; ?>"
                                data-exp-month="<?php echo $card['exp_month']; ?>"
                                data-toggle="modal"
                                data-target="#update-card">Update</button>
                        </div>
                        <div class="col-md-3">
                            <form method="post" class="delete-card" action="/cards/<?php echo $card['id']; ?>?_method=delete">
                                <input class="btn btn-default btn-xs" value="Delete" type="submit">
                            </form>
                        </div>
                    </div>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>
<?php echo $__view->render('app/Views/Elements/Modals/update-card.php'); ?>


