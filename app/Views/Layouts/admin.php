<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="bootstrap admin template">
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="private">
  <meta name="author" content="">
  <title>Grubbler | Admin </title>
  <link rel="apple-touch-icon" href="<?php echo $__assets->getUrl('/admin/base/assets/images/apple-touch-icon.png' );?>">
  <link rel="shortcut icon" href="<?php echo $__assets->getUrl('/admin/base/assets/images/favicon.ico' );?>">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/css/bootstrap.min.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/css/bootstrap-extend.min.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/base/assets/css/site.min.css' );?>">
  <!-- Plugins -->
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/animsition/animsition.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/asscrollable/asScrollable.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/switchery/switchery.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/intro-js/introjs.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/slidepanel/slidePanel.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/flag-icon-css/flag-icon.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/waves/waves.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/chartist-js/chartist.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/jvectormap2/jquery-jvectormap.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/base/assets/examples/css/dashboard/v1.css' );?>">
  <!-- Fonts -->
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/fonts/material-design/material-design.min.css' );?>">
  <link rel="stylesheet" href="<?php echo $__assets->getUrl('/admin/global/fonts/brand-icons/brand-icons.min.css' );?>">
  <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
  <!--[if lt IE 9]>
    <script src="<?php echo $__assets->getUrl('/admin/global/vendor/html5shiv/html5shiv.min.js' );?>"></script>
    <![endif]-->
  <!--[if lt IE 10]>
    <script src="<?php echo $__assets->getUrl('/admin/global/vendor/media-match/media.match.min.js' );?>"></script>
    <script src="<?php echo $__assets->getUrl('/admin/global/vendor/respond/respond.min.js' );?>"></script>
    <![endif]-->
  <!-- Scripts -->
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/modernizr/modernizr.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/breakpoints/breakpoints.js' );?>"></script>
  <script>
  Breakpoints();
  </script>
</head>
<body class="dashboard">
  <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <?php echo $__helper->flash(); ?>
    <?php echo $__content; ?>



  <!-- Core  -->
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/jquery/jquery.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/bootstrap/bootstrap.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/animsition/animsition.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/asscroll/jquery-asScroll.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/mousewheel/jquery.mousewheel.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/asscrollable/jquery.asScrollable.all.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/ashoverscroll/jquery-asHoverScroll.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/waves/waves.js' );?>"></script>
  <!-- Plugins -->
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/switchery/switchery.min.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/intro-js/intro.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/screenfull/screenfull.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/slidepanel/jquery-slidePanel.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/chartist-js/chartist.min.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/jvectormap2/jquery-jvectormap.min.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/jvectormap2/maps/jquery-jvectormap-world-mill-en.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/matchheight/jquery.matchHeight-min.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/peity/jquery.peity.min.js' );?>"></script>
  <!-- Scripts -->
  <script src="<?php echo $__assets->getUrl('/admin/global/js/core.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/base/assets/js/site.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/base/assets/js/sections/menu.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/base/assets/js/sections/menubar.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/base/assets/js/sections/sidebar.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/configs/config-colors.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/base/assets/js/configs/config-tour.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/asscrollable.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/animsition.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/slidepanel.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/switchery.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/tabs.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/matchheight.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/jvectormap.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/peity.js' );?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/formvalidation/formValidation.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/formvalidation/framework/bootstrap.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/formvalidation/formValidation.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/vendor/jquery-wizard/jquery-wizard.js');?>"></script>  
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/animsition.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/jquery-wizard.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/global/js/components/matchheight.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/js/admin-restaurants-wizard.js');?>"></script>
  <script src="<?php echo $__assets->getUrl('/admin/base/assets/examples/js/dashboard/v1.js');?>"></script>

</body>
</html>