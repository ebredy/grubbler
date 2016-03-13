<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
<div class="site-wrapper">
    <div class="container">
        <div class="header">
            <h4><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <?php echo $restaurant; ?></h4>
            <p class="text-muted"><?php echo $address; ?></p>
        </div>
        <div class="row filters">
            <ul class="col-md-12 list-inline">
                <li>
                    <a id="cuisineLabel" data-target="#" href="http://example.com" data-toggle="dropdown"
                       aria-haspopup="true" role="button"
                       aria-expanded="false">Cuisine <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="cuisineLabel">
                        <li><a href="#">Cost</a></li>
                        <li><a href="#">Delivery Fee</a></li>
                    </ul>
                </li>
                <li>
                    <a id="costLabel" data-target="#" href="http://example.com" data-toggle="dropdown"
                       aria-haspopup="true" role="button"
                       aria-expanded="false">Cost <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="costLabel">
                        <li><a href="#">Cost</a></li>
                        <li><a href="#">Delivery Fee</a></li>
                    </ul>
                </li>
                <li>
                    <a id="feeLabel" data-target="#" href="http://example.com" data-toggle="dropdown"
                       aria-haspopup="true" role="button"
                       aria-expanded="false">Cost <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="feeLabel">
                        <li><a href="#">Cost</a></li>
                        <li><a href="#">Delivery Fee</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="row">

            <div class="col-xs-12 col-md-9">
                <div class="row">

                    <?php if( !empty( $menu[0] ) ) { ?>
                        <?php foreach( $menu as $item ) { ?>
                            <div class="col-xs-6 col-md-4 food-item">
                                <div class="thumbnail">
                                    <a href="javascript:void(0)" class="food-item-img" data-toggle="modal" data-target="#menu-item" data-menu-id="<?php echo $item['id']; ?>" data-restaurant-id="<?php echo $item['restaurant_id']; ?>" >
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
                                                <?php echo ucfirst( strtolower( $item['description'] ) ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="small">No menu found</p>
                    <?php } ?>

                </div>
            </div>
            <div class="col-xs-6 col-md-3 well well-sm">
                <div id="cart"></div>
            </div>

        </div><!-- /.row -->
    </div><!-- /.container -->
    <div class="overlay" style="display: none; opacity: 1;">
    </div>

    <?php echo $__view->render('app/Views/Elements/footer.php'); ?>
    <?php echo $__view->render('app/Views/Elements/Modals/menu-item.php'); ?>
