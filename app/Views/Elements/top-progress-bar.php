<link href='<?php echo $__assets->getUrl( '/js/nprogress/nprogress.css' );?>' rel="stylesheet">
<script src="<?php echo $__assets->getUrl( '/js/nprogress/nprogress.js' );?>"></script>
<script>
    NProgress.start().set(0.6)
    $( document ).ready(function() {
        setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 500);
    });
</script>