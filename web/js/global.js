$( document ).ready(function() {
    loadMiniCart()
});


function loadMiniCart() {

    cart_total = Cookies.set('cart_total');
    if ( cart_total ) {
        $("#mini-cart-total").text( cart_total );
    }

}