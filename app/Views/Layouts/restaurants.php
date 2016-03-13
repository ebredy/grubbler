<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Hello World!</title>

    <!-- Bootstrap -->
    <link href="<?php echo $__assets->getUrl( '/css/bootstrap.min.css' );?>" rel="stylesheet">
        <link href="<?php echo $__assets->getUrl( '/css/default.css' );?>" rel="stylesheet">
    <link href="<?php echo $__assets->getUrl( '/css/bootstrap-rating.css' );?>" rel="stylesheet">
    <link href="<?php echo $__assets->getUrl( '/css/bootstrap-select.css' );?>" rel="stylesheet">
     <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body onload="initialize()">
<?php echo $__helper->flash(); ?>
<?php echo $__content; ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
<script>
    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initialize() {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {HTMLInputElement} */(document.getElementById('address')),
            { types: ['geocode'] });
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            fillInAddress();
        });
    }

    function fillInAddress() {

        var place = autocomplete.getPlace();

        var latInput = document.getElementById('latitude');
        if ( latInput ) {
            latInput.value = place.geometry.location.lat();
        }

        var lngInput = document.getElementById('longitude');
        if ( lngInput ) {
            lngInput.value = place.geometry.location.lng();
        }

        for (var component in componentForm) {
            comp = document.getElementById(component);
            if ( comp ) {
                comp.value = '';
            }
        }

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                var addressTypeInput = document.getElementById(addressType)
                if ( addressTypeInput ) {
                    addressTypeInput.value = val;
                }
            }
        }

    }

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = new google.maps.LatLng(
                    position.coords.latitude, position.coords.longitude);
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

</script>

<?php echo $__view->render('app/Views/Elements/top-progress-bar.php'); ?>
<script src="<?php echo $__assets->getUrl( '/js/js.cookie.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootbox.min.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/dialogue-handler.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootstrap.min.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootstrap-select.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/collapse.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootstrap-rating.min.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/restaurants.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/cart.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/menu.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/global.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/infinite-scroll/jquery.infinitescroll.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/infinite-scroll/behaviors/manual-trigger.js' );?>"></script>

<script>


    $('#restaurants').infinitescroll({
        navSelector  	: "#next:last",
        nextSelector 	: "a#next:last",
        itemSelector 	: "#restaurants .restaurant",
        loadingHtml     : 'Cool...',
        loadingText     : 'Loading...',
        dataType	 	: 'html'
//      maxPage         : 3,
//		prefill			: true,
//		path: ["http://nuvique/infinite-scroll/test/index", ".html"]
//        path: function(index) {
//            return "index" + index + ".html";
 //       }
        // behavior		: 'twitter',
        // appendCallback	: false, // USE FOR PREPENDING
        // pathParse     	: function( pathStr, nextPage ){ return pathStr.replace('2', nextPage ); }
    }, function(newElements, data, url){
        //USE FOR PREPENDING
        // $(newElements).css('background-color','#ffef00');
        // $(this).prepend(newElements);
        //
        //END OF PREPENDING

//    	window.console && console.log('context: ',this);
//    	window.console && console.log('returned: ', newElements);

    });
</script>
</body>
</html>