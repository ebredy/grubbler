<form action="/checkout" method="POST" id="payment-form">
    <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
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
    <button type="submit" class="btn btn-danger btn-md">Submit Order</button>
</form>