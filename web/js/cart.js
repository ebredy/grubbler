function cart( restaurant_id, sync_carts ) {

    if ( !$.isNumeric( restaurant_id ) ) {
        return;
    }
    cart_container = $('#cart');
    if ( !cart_container.length ) {
        return;
    }
    cart_container.empty().addClass( "loading" );
    $.getJSON( '/cart?restaurant_id=' + restaurant_id, function( response ) {
        cart_container.empty().removeClass( 'loading' )
        if (response.status === 'success') {
            cart_container.html( response.data ).effect('highlight');
        }
    }).error(function( jqXHR, textStatus, errorThrown ) {
        cart_container.empty().removeClass( 'loading' )
    }).success(function(){
        activateCartItemRemoval()

        if ( sync_carts !== undefined ) {
            syncMiniCart()
        }
    })
}

function activateCartItemRemoval() {

    $(".delete-item").click(function () {

        button = $(this)
        restaurant_id = button.data('restaurant-id')
        item_id = button.data('cart_id')

        $.ajax({
            url:  '/cart/' + item_id,
            type: 'DELETE',
            dataType: 'json',
            success: function(response){
                if (response.status === 'success') {
                    cart(restaurant_id, true);
                }
            }
        });
    })

}

function syncMiniCart() {

    cart_total = $('#cart-total').text()
    old_total = $("#mini-cart-total").text()

    if ( !cart_total ) {
        cart_total = '0.00'
    }

    if ( cart_total != old_total ) {
        $("#mini-cart-total").text( cart_total ).effect('highlight')
        Cookies.set('cart_total', cart_total );
    }

}