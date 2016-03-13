<?php echo $__view->render('app/Views/Elements/admin-navbar.php'); ?>
<?php echo $__view->render('app/Views/Elements/admin-sitemenu.php'); ?>
  <!-- Page -->
  <div class="page animsition">
    <div class="page-content padding-30 container-fluid">
      <div class="row" data-plugin="matchHeight" data-by-row="true">
          <?php echo $__view->render('app/Views/Elements/admin-widgetuserstat.php'); ?>
          <?php echo $__view->render('app/Views/Elements/admin-widgetuservisit.php'); ?>
          <?php echo $__view->render('app/Views/Elements/admin-widgetuserclicks.php'); ?>
          <?php echo $__view->render('app/Views/Elements/admin-widgetitems.php'); ?>
    
        <div class="clearfix"></div>
        <?php echo $__view->render('app/Views/Elements/admin-widget-jvmap.php'); ?>
        <?php echo $__view->render('app/Views/Elements/admin-widgetcurrentchart.php'); ?>
        <?php echo $__view->render('app/Views/Elements/admin-widgetuserlist.php'); ?>
        <?php echo $__view->render('app/Views/Elements/admin-chat.php'); ?>
        <?php echo $__view->render('app/Views/Elements/admin-widgetinfo.php'); ?>
        <?php echo $__view->render('app/Views/Elements/admin-projects.php'); ?>
        <?php echo $__view->render('app/Views/Elements/admin-projectstatus.php'); ?>

      </div>
    </div>
  </div>
  <!-- End Page -->
<?php echo $__view->render('app/Views/Elements/admin-footer.php'); ?>