/**
 * Created by Erwin on 9/22/2015.
 */
$('.suggestedAddress').on('click',function(e){
    e.preventDefault();

    $("input[name='address_1']").val($(this).attr('data-address_1'));
    $("input[name='city']").val($(this).attr('data-city'));
    $("input[name='state']").val($(this).attr('data-state'));
    $("input[name='zip_code']").val($(this).attr('data-zip'));

});