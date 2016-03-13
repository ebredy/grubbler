<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
    <div class="site-wrapper">
        <div class="container">
            <div class="header">
                <h2>The Ultimate Food Ordering Experience</h2>
                <p class="text-muted lead" id="inlineField"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <?=$geolocation['city'].",".$geolocation['region']?> <a href="#" id="changeAddress">Change Address</a>.</p>
               
                <form class="form-inline" id="AddressSearch" action="/restaurants" method="GET"  style='display:none;'>
<!--                    <input class="field" id="street_number" name="street_number" type="hidden">
                    <input class="field" id="route" name="street_address" type="hidden"> --->
                    <input class="field" id="latitude" name="latitude" type="hidden" value="<?=$geolocation['latitude']?>">
                    <input class="field" id="longitude" name="longitude" type="hidden" value="<?=$geolocation['longitude']?>">
<!--                    <input class="field" id="administrative_area_level_1" name="state" type="hidden">
                    <input class="field" id="postal_code" name="postal_code" type="hidden">
                    <input class="field" id="country" name="country" type="hidden">-->
                    <div class="form-group form-group-lg large-search">
                        <input type="text" autofocus="autofocus" id="address" name="address" class="form-control" value="<?=$geolocation['city'].','.$geolocation['region']?>" placeholder="Enter your delivery address">
                    </div>
                    <button type="submit" id="submitAddressSearch" class="btn btn-orange btn-lg"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </form>
            </div>
            <div class="row filters">
                <ul class="col-md-12 list-inline">
                    <li>
<!--                        <a id="cuisineLabel" data-target="#" href="http://example.com" data-toggle="dropdown"
                           aria-haspopup="true" role="button"
                           aria-expanded="false">Cuisine <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="cuisineLabel">
                            <li><a href="#">Cost</a></li>
                            <li><a href="#">Delivery Fee</a></li>
                        </ul>-->
                        <div class='form-group' >
                            <label for='cuisine'>
                                Cuisine:&nbsp;
                            </label>
                            <select id='cuisine' class='selectpicker' multiple>
                                <?php foreach($cuisines_dropdown as $index => $option ): ?>
                                    <option value='<?=$option['id']?>' data-content="<span class='label label-info'><?=$option['name']?></span>" selected><?=$option['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class='form-group' >
                            <label for='rating'>
                                Rating:&nbsp;
                            </label>
                            <select id='rating' class='selectpicker' data-width="120px">
                                <option value='1'   data-content="<span class='glyphicon glyphicon-star' style='color: yellow;' ></span><span class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span>" ></option>
                                <option value='2' data-content="<span class='glyphicon glyphicon-star' style='color: yellow;'></span><span class='glyphicon glyphicon-star' style='color: yellow;'></span><span class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span>"></option>
                                <option value='3' data-content="<span class='glyphicon glyphicon-star' style='color: yellow;' ></span><span class='glyphicon glyphicon-star' style='color: yellow;'></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span>"></option>
                                <option value='4' data-content="<span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span class='glyphicon glyphicon-star' ></span>"></option>
                                <option value='5' data-content="<span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span><span style='color: yellow;' class='glyphicon glyphicon-star' ></span>"></option>
                            </select>
                        </div>                        
<!--                        <a id="costLabel" data-target="#" href="http://example.com" data-toggle="dropdown"
                           aria-haspopup="true" role="button"
                           aria-expanded="false">Cost <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="costLabel">
                            <li><a href="#">Cost</a></li>
                            <li><a href="#">Delivery Fee</a></li>
                        </ul>-->
                    </li>
                    <li>
                        <div class='form-group' >
                            <label for='delivery'>
                                Minimum Delivery Fee:&nbsp;
                            </label>
                            <select id='delivery' class='selectpicker' data-width="120px">
                                <option value='0' data-content="<span class='label label-default'>Free</span>">Free</option>
                                <option value='5'  data-content="<span class='label label-default'>Up to $5.00</span>">Up to 5.00</option>
                                <option value='10'  data-content="<span class='label label-default'>Up to $10.00</span>">Up to 10.00</option>
                                <option value='20'  data-content="<span class='label label-default'>Up to $20.00</span>">Up to 20.00</option>
                                <option value='30'  data-content="<span class='label label-default'>Up to $30.00</span>">Up to 30.00</option>
                            </select>
                        </div>
<!--                        <a id="feeLabel" data-target="#" href="http://example.com" data-toggle="dropdown"
                           aria-haspopup="true" role="button"
                           aria-expanded="false">Cost <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="feeLabel">
                            <li><a href="#">Cost</a></li>
                            <li><a href="#">Delivery Fee</a></li>
                        </ul>-->
                    </li>
                    <li>
                        <div class='form-group' >
                            <button class='btn btn-primary' id='ApplyFilter' >Apply Filter</button>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="row">

                <?php if( !empty( $items[0] ) ) { ?>
                    <?php foreach( $items as $item ) { ?>
                        <div class="col-xs-6 col-md-3 food-item">
                            <div class="thumbnail">
                                <a href="/restaurants/<?php echo $item['restaurant_id']; ?>#<?php echo $item['id']; ?>" class="food-item-img" >
                                    <img src="<?php echo $item['image']; ?>" alt="...">
                                </a>
                                <div class="caption">
                                    <div class="row">
                                        <div class="col-md-9 food-item-title"><?php echo ucfirst( strtolower( $item['item'] ) ); ?></div>
                                        <?php if( !empty( $item['price'] ) ) { ?>
                                            <div class="col-md-3 text-right food-item-price"><?php echo '$' . ltrim( $item['price'], '$' ); ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-muted small">
                                            <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden-caption">
                                    <div class="row">
                                        <div class="col-md-12"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> 35 mins est. &bull; $2 fee &bull; $20 minimum</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="small">No menu found</p>
                <?php } ?>

            </div><!-- /.row -->
        </div><!-- /.container -->
        <div class="overlay" style="display: none; opacity: 1;">
        </div>
        <script id='restaurantsTemplate' type='html/template' >
            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="/restaurants/{%restaurant_id%}#{%id%}" class="food-item-img" >
                        <img src="{%image%}" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">{%item%}</div>
                            
                                <div class="col-md-3 text-right food-item-price">${%price%}</div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden-caption">
                        <div class="row">
                            <div class="col-md-12"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> 35 mins est. &bull; $2 fee &bull; $20 minimum</div>
                        </div>
                    </div>
                </div>
            </div>            
        </script>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>