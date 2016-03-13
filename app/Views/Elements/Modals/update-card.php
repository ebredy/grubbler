<div class="modal fade" id="update-card" tabindex="-1" role="dialog" aria-labelledby="update-cardLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="update-cardLabel">Update Card</h4>
                <p id="card-number" class="text-muted text-success"></p>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="update-card-form">
                    <span id="payment-errors" role="alert" style="display: none"></span>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expiration">Expiration MM:</label>
                                <input type="text" class="form-control input-sm" maxlength="2" id="exp-month" name="exp_month" placeholder="Expiration month" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expiration">Expiration YYYY:</label>
                                <input type="text" class="form-control input-sm" maxlength="4" id="exp-year" name="exp_year" placeholder="Expiration year" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address_zip">Billing zip code:</label>
                        <input type="text" class="form-control input-sm" maxlength="5" id="address-zip" name="address_zip" placeholder="Billing zip code" autocomplete="off"/>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Card</button>
            </div>
            </form>
        </div>
    </div>
</div>