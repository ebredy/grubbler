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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php echo $__helper->flash(); ?>
<?php echo $__content; ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<?php echo $__view->render('app/Views/Elements/top-progress-bar.php'); ?>
<script src="<?php echo $__assets->getUrl( '/js/js.cookie.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootstrap.min.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/cart.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/menu.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/global.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/checkout.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/stripe.js' );?>"></script>

<?php
$stripe     = $__helper->getParameter( 'stripe' );
$public_key = ( !empty( $stripe['public_key'] ) ) ? $stripe['public_key'] : null;
?>

<script>

    Stripe.setPublishableKey( '<?php echo $public_key; ?>' );
    $( document ).ready(function() {
        <?php if( !empty( $restaurant_id ) ) { ?>cart( '<?php echo $restaurant_id; ?>' )<?php } ?>
    });

</script>
</body>
</html>