<div id="restaurants" class="col-md-8">
    <?php if(!empty($restaurants)){ ?>
        <?php foreach( $restaurants as $restaurant ) { ?>
            <div class="restaurant media">
                <div class="media-left">
                    <a href="/restaurants/<?php echo $restaurant['id']; ?>">
                        <img class="media-object" src="https://static.delivery.com/merchant_logo.php?id=79053&w=94&h=94">
                    </a>
                </div>
                <div class="media-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="media-heading"><a href="/restaurants/<?php echo $restaurant['id']; ?>"><?php echo $restaurant['restaurant']; ?></a></h4>
                            <p class="small text-muted"><?php echo $restaurant['full_address']; ?></p>
                        </div>
                        <div class="col-md-4">
                            <table class="table text-muted small">
                                <tr>
                                    <td>Distance</td>
                                    <td><?php echo number_format( $restaurant['distance'], 1 ); ?> mi</td>
                                </tr>
                                <tr>
                                    <td>Rating</td>
                                    <td><?php echo $restaurant['rating']; ?> stars</td>
                                </tr>
                                <tr>
                                    <td>Pricing</td>
                                    <td><?php echo $restaurant['price']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php }else{  ?>
            <div class="restaurant media">
                <div class="media-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class='alert alert-warning' role='alert'> <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span>&nbsp; :( No Local Restaurants Found!</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                             &nbsp;
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="col-md-offset-2">Try another search criteria</h4>
                        </div>
                    </div>
                </div>
            </div>    
        
    <?php } ?>
    
</div>
<?php if ( !empty( $restaurants ) && !empty( $next_page ) ) { ?>
    <a id="next" href="<?php echo $next_page; ?>" style="display: none">next page?</a>
<?php } ?>