$('#payment-form').submit(function( event ) {

    //event.preventDefault();
    var $form = $(this);
    $form.find('button').prop('disabled', true);
    var card_id = $form.find('select[name="card_id"]').val();

    if ( card_id ) {
        $form.get(0).submit();
        return false;
    }

    var has_errors = stripePaymentFormValidation();

    if ( has_errors ) {
        $form.find('button').prop('disabled', false );
        return false;
    }

    Stripe.card.createToken( $form, stripeResponseHandler );

    return false;

});

$('.delete-card').submit(function( event ) {
    event.preventDefault()
    var response = confirm("Are you sure you want to delete your card?");
    if (  response ) {
        $(this).get(0).submit();
    }
    return false;
});

$('#update-card').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var brand = button.data('brand')
    var expYear = button.data('exp-year')
    var expMonth = button.data('exp-month')
    var last4 = button.data('last4')
    var modal = $(this)
    modal.find('#card-number').text( brand + ' ****' + last4 )
    modal.find('.modal-body #exp-month').val( expMonth )
    modal.find('.modal-body #exp-year').val( expYear )
    modal.find('.modal-body #update-card-form').attr('action', '/cards/' + id )
})

function stripeResponseHandler(status, response) {

    var $form = $('#payment-form');

    if (response.error) {
        reportError(response.error.message);
        $form.find('button').prop('disabled', false);
    } else {
        var token = response.id;
        $form.append($('<input type="hidden" name="payment_token" />').val(token));
        $form.get(0).submit();
    }

}

function stripePaymentFormValidation() {

    var error = false;
    $('#payment-errors').html('');

    var ccNum = $('#stripe-number').val(),
        cvcNum = $('#stripe-cvc').val(),
        expMonth = $('#stripe-expiry-month').val(),
        expYear = $('#stripe-expiry-year').val();

    if (!Stripe.card.validateCardNumber(ccNum)) {
        error = true;
        reportError('The credit card number appears to be invalid.');
    }

    if (!Stripe.card.validateCVC(cvcNum)) {
        error = true;
        reportError('The CVC code appears to be invalid.');
    }

    /**
     if (!Stripe.card.validateExpiry(expMonth, expYear)) {
        error = true;
        reportError('The expiration date appears to be invalid.');
    }
     **/

    return error;
}

function reportError( msg ) {
    $('#payment-errors').addClass( 'text-danger' ).show().append( '<p><b>' + msg + '</b></p>' );
    return false;

}