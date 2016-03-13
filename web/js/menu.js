$('#menu-item').on('show.bs.modal', function (event) {
    button = $(event.relatedTarget)
    menu(
        $(this),
        button.data('restaurant-id'),
        button.data('menu-id'),
        button.data('cart-id') );
});

function menu( modal, restaurant_id, menu_id, cart_id ) {

    modal_content = modal.find('.modal-content')
    modal_content.empty().addClass( 'loading' )

    if ( cart_id === undefined ) {
        url = '/cart/add/' + menu_id;
    }else{
        url = '/cart/' + cart_id;
    }

    $.getJSON( url, function( response ) {
        modal_content.removeClass( 'loading' )
        if ( response.status === 'success' ) {
            modal_content.html( response.data )
        }
    }).success(function(){
        activateQrderQtyUpdate()
        activateOrderActions( restaurant_id )
        modal.handleUpdate();
    }).error( function( jqXHR, textStatus, errorThrown ){
    })

    function activateQrderQtyUpdate(){

        $('#menu-item').find( "#menu-item-qty" ).click( function() {

            textBox = $(this);
            textBox.focus().select();
            textBox.keyup( function(e) {

                total_item_cost = $("#total-item-cost")

                old_total = parseFloat( total_item_cost.text() ).toFixed(2)
                if ( isNaN( old_total ) ) {
                    old_total = 0.00;
                }
                total_cost = parseFloat( $( "#item-cost" ).text() ) * parseFloat( $(this).val() )
                total_cost = parseFloat( total_cost ).toFixed(2);
                if ( isNaN( total_cost ) ) {
                    total_cost = 0.00;
                }
                if ( total_cost != old_total ) {
                    total_item_cost.text( total_cost ).parent().effect('highlight')
                }
            });
        });
    }

    function activateOrderActions( restaurant_id ) {

        $('#menu-item').find('.cart-item').on('submit', function(e){

            e.preventDefault();
            form = $(this);
            $('.modal-content').empty().addClass( "loading" );

            $.ajax({
                url:  form.attr( 'action' ),
                type: form.attr( 'method' ),
                data: form.serialize(),
                success: function(response){
                    $('.modal-content').empty().removeClass( "loading" );
                    $('#menu-item').modal('hide');
                    if ( response.status == 'success' ) {
                        cart( restaurant_id, true )
                    }
                }
            });
        });
    }

}
