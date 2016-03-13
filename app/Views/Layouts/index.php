<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>The Ultimate Food Ordering Experience!</title>

    <!-- Bootstrap -->
    <link href="<?php echo $__assets->getUrl( '/css/bootstrap.min.css' );?>" rel="stylesheet">
    <link href="<?php echo $__assets->getUrl( '/css/index.css' );?>" rel="stylesheet">
    <link href="<?php echo $__assets->getUrl( '/css/bootstrap-select.css' );?>" rel="stylesheet">
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
<script src="<?php echo $__assets->getUrl('js/jquery/1.11.2/jquery.min.js' );?>"></script>
<script src="<?php echo $__assets->getUrl('js/jquery-ui/1.11.3/jquery-ui.js' );?>"></script>
<!-- Include all compiled plugins (below), or include individual filesjquery as needed -->
<script src="<?php echo $__assets->getUrl( '/js/js.cookie.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootstrap.min.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/bootstrap-select.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/restaurants.js' );?>"></script>
<script src="<?php echo $__assets->getUrl( '/js/global.js' );?>"></script>
<script>
    $(document).ready(function(){

        $("#q").focus( function(){
            $('.overlay').fadeIn(400);
        }).focusout( function(){
            $('.overlay').stop().fadeOut(100);
        })

        $(".food-item").hover( function(){
            $this = $(this);
            caption = $this.find('.caption')
            h = caption.height()
            caption.stop(true, true).animate({bottom: h},200);
        }, function () {
            caption.stop(true, true).animate({bottom:0},200);
        })

    });

</script>
</body>
</html>