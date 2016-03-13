<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
    <style>
      .custom{
          font-size: 2.5em;
        }  
      .usd-color {
        
        color: #63B169;
      }
      .star-color {
        
        color: #EDE818;
      } 
    </style>
<div class="container">
    <div class="page-header">
        <h4>Your Restaurants</h4>
        <p class="text-muted"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> Showing restaurants near <b><?=isset($address)?$address:''; ?></b>. To change location <a href="/">click here</a></p>
    </div>
    <div class="row">
        <form id="searchRestaurantOnPage" method="GET" action="/restaurants">
            <input class="field" id="latitude" name="latitude" type="hidden" value="<?=isset($latitude)?$latitude:''?>">
            <input class="field" id="longitude" name="longitude" type="hidden" value="<?=isset($longitude)?$longitude:''?>">
            <input type="hidden" name="address" value="<?=isset($address)?$address:''; ?>" >
            <div class="col-md-4 well well-sm">
                <ul class="list-group">
                    <li class="list-group-item"> 
                        <h4>Search Menus</h4>

                        <div class="input-group">
                          <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?=isset($email)?$email:''?>">
                          <span class="input-group-btn">
                            <button class="btn btn-secondary" id="SearchRestaurant" type="button">Search</button>
                          </span>
                        </div>
                    </li>
                </ul>                    
                <ul class="list-group">
                    <li class="list-group-item">
                         <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="cuisineHeading">
                              <h4 class="panel-title">                        
                                <a  role="button" data-toggle="collapse" href="#cuisineSection" aria-expanded="true" aria-controls="cuisineSection">
                                    Cuisines&nbsp; 
<!--                                    <span class="badge pull-right">14</span> -->
                                </a>
                                  (<a id='clearCuisine' href="#">clear</a>)
                          </h4>
                        </div>                       
                        <div class="panel-collapse collapse in" role="tabpanel" id="cuisineSection" aria-controls="cuisineSection">
                           
                          <div class="panel-body">
                              
                                <?php foreach($cuisines_dropdown as $index => $option ): ?>
                                    <input type="checkbox" class="cuisine" value='<?=$option['id']?>'  <?=in_array($option['id'],$cuisine)?'checked':''?> >&nbsp;<span class='label label-default'><?=$option['name']?></span><br>
                                <?php endforeach;?>
                          
                          </div>
                        </div>
                    </li>
                    
                </ul>
                <ul class="list-group">
                    <li class="list-group-item">
                         <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="ratingHeading">
                              <h4 class="panel-title">
                                <a  role="button" data-toggle="collapse" href="#ratingSection" aria-expanded="true" aria-controls="ratingSection">
                                    <bold>Minimum Rating</bold>&nbsp;
                                </a>
                                (<a id='clearRating' href="#clearRating">clear</a>)  
                            
                          </h4>
                        </div>
                        <div  id="ratingSection" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="ratingSection">
                         
                          <div class="panel-body">
                                <input type="hidden" class="rating" name="rating" id="rating" data-filled="glyphicon glyphicon-star custom star-color" data-empty="glyphicon glyphicon-star-empty custom star-color" value="<?=$rating?>"/>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="list-group">
                    <li class="list-group-item">
                         <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="deliveryHeading">
                              <h4 class="panel-title">                        
                                <a  role="button" data-toggle="collapse" href="#deliverySection" aria-expanded="true" aria-controls="ratingSection">
                                    <bold>Minimum Delivery</bold>&nbsp;
                                </a>
                                (<a id='clearDelivery' href="#clearDelivery">clear</a>)  
                                  
                          </h4>
                        </div>                                  
                        <div class="panel-collapse collapse in" id="deliverySection" aria-labelledby="deliverySection">
                          <div class="panel-body">                       
                                <input type="hidden" class="rating" name="delivery" id="delivery" data-filled="glyphicon glyphicon-usd custom usd-color" data-empty="glyphicon glyphicon-usd custom" value="<?=$delivery?>"/>
                            </div>
                        </div>                        
                    </li>
                </ul>
            </div>
        </form>   
        <?php echo $__view->render('app/Views/Elements/restaurants.php'); ?>
    </div>
</div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>