$( "#submit-checkout" ).click( function(e) {

    e.preventDefault();

    $('body').append($('<form/>', {
        id: 'checkout-form',
        method: 'POST',
        action: '/checkout'
    }));

    checkoutForm = $('#checkout-form');

    checkoutForm.append($('<input/>', {
        type: 'hidden',
        name: 'payment',
        value: $('#payment-form').serialize()
    }));

    checkoutForm.append($('<input/>', {
        type: 'delivery',
        name: 'delivery',
        value: $('#delivery-form').serialize()
    }));

    checkoutForm.append($('<input/>', {
        type: 'hidden',
        name: 'cart',
        value: $('#cart-form').serialize()
    }));

    checkoutForm.submit();

});

$( ".select-card" ).change( function(e) {

    option = $(this).find(":selected").data( 'toggle' );

    if ( option === 'new-card' ) {
        $('#new-card-form').fadeIn();
    } else {
        $('#new-card-form').fadeOut();
    }

});
