/**
 * Created by Erwin on 9/22/2015.
 */
getRestaurants = function(event){
    event.preventDefault();
    
    var cuisine = $("#cuisine").val();
    cuisine  =   Object.prototype.toString.call( cuisine ) === '[object Array]'?cuisine.join(','): cuisine;
    
    $('#AddressSearch').append("<input type='hidden' name='delivery' value='"+ $("#delivery").val() +"' >");
    $('#AddressSearch').append("<input type='hidden' name='rating' value='"+ $("#rating").val() +"' >");
    $('#AddressSearch').append("<input type='hidden' name='cuisine' value='"+ cuisine +"' >");
    $('#AddressSearch').submit();
 
    
}
getRestaurantsOnPage = function(event){
    event.preventDefault();
    var selectCuisine = new Array();
    
    if( $(".cuisine:checked").length > 0 ){
      
        $(".cuisine:checked").each(function(){

                selectCuisine.push($(this).val());
               
        });
        
        var cuisine  =   selectCuisine.length > 0 ? selectCuisine.join(','): selectCuisine;
    
        $('#searchRestaurantOnPage').append("<input type='hidden' name='cuisine' value='"+ cuisine +"' >");
        $('#searchRestaurantOnPage').submit();
 
    }
    else
    {
            errorAlert('You must select one cuisine as an option!');
    }
       
}

showAddressField = function(event){
    
    
    $('#AddressSearch').show();
    $('#inlineField').hide();
    displayedAddressField = window.setTimeout(hideAddressField,20000);
    //$('#inlineField').html('<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>'..'<a href="#" id="changeAddress">Change Address</a>.');
}
hideAddressField = function(event){
    
    
    $('#AddressSearch').hide();
    $('#inlineField').show();
    //$('#inlineField').html('<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>'..'<a href="#" id="changeAddress">Change Address</a>.');
}

disableTimeout = function(event){
    
    
    window.clearTimeout(displayedAddressField);
    
}
iniateHideAddress = function(event){
    
    window.setTimeout(hideAddressField,20000);
}
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(setPosition);
    } else {
        //x.innerHTML = "Geolocation is not supported by this browser.";
    }
}
function clearCuisine(event){
        
        
        event.preventDefault();
        
        $('.cuisine').each(function(){
            
                $(this).prop('checked',true);
                
        });
}
function clearRating(event){
        
        event.preventDefault();
        $('#rating').rating('rate',1);
}
function clearDelivery(event){
        
        event.preventDefault();
        $('#delivery').rating('rate',1);
}
function setPosition(position) {
   // x.innerHTML = "Latitude: " + position.coords.latitude + 
   // "<br>Longitude: " + position.coords.longitude; 
    $('#latitude').val(position.coords.latitude);
    $('#longitude').val(position.coords.longitude);
}
$(document).ready(function(e){
    
    if( $('.selectpicker').length > 0)  $('.selectpicker').selectpicker();
    if( $('#submitAddressSearch').length > 0) $('#submitAddressSearch').on('click',getRestaurants);
    if( $('#searchRestaurantOnPage').length > 0) $('#SearchRestaurant').on('click',getRestaurantsOnPage);
    if( $('.rating').length > 0) $(".rating").rating();
    if( $('#ApplyFilter').length > 0) $('#ApplyFilter').on('click',getRestaurants);
    if( $('#changeAddress').length > 0) $('#changeAddress').on('click',showAddressField);
    if( $('#address').length > 0) $('#address').on('blur',iniateHideAddress);
    if( $('#clearCuisine').length > 0) $('#clearCuisine').on('click',clearCuisine);
    if( $('#clearRating').length > 0) $('#clearRating').on('click',clearRating);
    if( $('#clearDelivery').length > 0) $('#clearDelivery').on('click',clearDelivery);
    //if( $('.collapse').length > 0) $('.collapse').collapse();
    if( $('#address').length > 0) $('#address').on('keypress',disableTimeout);
});