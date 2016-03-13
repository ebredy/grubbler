<?php echo $__view->render('app/Views/Elements/admin-navbar.php'); ?>
<?php echo $__view->render('app/Views/Elements/admin-sitemenu.php'); ?>
  <!-- Page -->
  <div class="page animsition">
    <div class="page-content">
      <!-- Panel -->
      <div class="panel">
        <div class="panel-body">
          <form class="page-search-form" role="search" action="/gadmin/search/restaurants" method="GET">
            <div class="input-search input-search-dark">
              <i class="input-search-icon wb-search" aria-hidden="true"></i>
              <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Search restaurant" value="">
              <input type="hidden" class="form-control" id="page" name="page" value="">
              <input type="hidden" id="per_page" name="per_page" value="" >
              <button type="button" class="input-search-close icon wb-close" aria-label="Close"></button>
            </div>
          </form>
            
            <h1 class="page-search-title">Restaurants Search&nbsp;<?=isset($keyword)?'Result "'.$keyword.'"':''?></h1>
          
          <p class="page-search-count">About
            <span>1,370</span> result (
            <span>0.13</span> seconds)</p>
          <div class="row">
              <div class="col-md-12">
                  <a href="/gadmin/restaurant/add" class="btn btn-animate btn-primary pull-right" >
                        Add Restaurants/Menu
                  </a>
              </div>
          </div>
          <ul class="list-group list-group-full list-group-dividered">
            <?php if( !empty( $restaurants ) ): ?> 

                <?php foreach( $restaurants as $restaurant ): ?>   
                    <li class="list-group-item">
                      <h4><a href="/gadmin/restaurant/edit/<?=$restaurant['id']?>"><?=$restaurant['restaurant']?></a></h4>
                      <a class="search-result-link" href="/gadmin/restaurant/edit/<?=$restaurant['id']?>">/gadmin/restaurants/edit/<?=$restaurant['id']?></a>
                      <p>       
                                Address:<?=$restaurant['full_address']?><br>
                                Operating Time: <?=date('g:i a', strtotime(substr_replace($restaurant['opens'], ':', -2, 0)))?>-<?=date('g:i a', strtotime(substr_replace($restaurant['closes'], ':', -2, 0)))?><br>
                                Rating: <?=$restaurant['rating']?>&nbsp; Star(s)

                      </p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                    <li class="list-group-item">
                      <h4><a href="#">no restaurants</a></h4>
                      <a class="search-result-link" href="#">Try another search keyword</a>
                      <p>


                      </p>
                    </li>                    
            <?php endif; ?>
          </ul>
          <?php echo $__view->render('app/Views/Elements/admin-pagination.php'); ?>
        </div>
      </div>
      <!-- End Panel -->
    </div>
  </div>  
<?php echo $__view->render('app/Views/Elements/admin-footer.php'); ?>