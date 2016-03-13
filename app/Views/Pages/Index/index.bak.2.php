<nav class="navbar navbar-reddish navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="/">
                <img src="/img/grubbler-white.png" class="logo">
            </a>
        </div>
        <form class="form-inline navbar-form navbar-left" action="/restaurants" method="GET">
            <div class="form-group has-feedback">
                <input type="search" class="form-control search-bar" id="q" name="q" placeholder="Search by dish or restaurant">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">

                <a id="dLabel" data-target="#"
                   href="http://example.com"
                   data-toggle="dropdown"
                   role="button"
                   aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Cart</a>
                <div class="dropdown-menu" aria-labelledby="dLabel">
                    <ul>
                        <li>Hahah</li>
                        <li>Hahah  wed</li>
                    </ul>
                </div>
            </li>
            <li><a href="/signin">Login</a></li>
            <li><a href="/signup">Sign Up</a></li>
        </ul>
    </div>
</nav>
<div class="site-wrapper">
    <div class="container">
        <div class="header">
            <h2>The Ultimate Food Ordering Experience</h2>
            <p class="text-muted lead"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> 20 Beacon Way, NJ. <a href="#">Change Address</a>.</p>
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
            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/a.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
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

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/b.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/c.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/d.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/e.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/f.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/e.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-3 food-item">
                <div class="thumbnail">
                    <a href="#" class="food-item-img">
                        <img src="/img/food/f.jpg" alt="...">
                    </a>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-9 food-item-title">NY Deep Dish Pizza</div>
                            <div class="col-md-3 text-right food-item-price">$4.98</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-muted small">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <a href="#">Royers Round Top Cafe, Brooklyn, NY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->
    </div><!-- /.container -->
    <div class="overlay" style="display: none; opacity: 1;">
    </div>

    <footer class="blog-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="/img/grubbler-grey.png" class="footer-logo">
                    <p><strong>Awesome Food Delivered to Your Door</strong></p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae sem sit amet ex venenatis pulvinar. Vivamus vestibulum elit et sollicitudin varius.</p>
                </div>
                <div class="col-md-2 col-md-offset-2">
                    <h4>Company</h4>
                    <ul class="list-unstyled">
                        <li><a href="#">About</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h4>Community</h4>
                    <ul class="list-unstyled">
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Reviews</a></li>
                        <li><a href="#">Developers</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h4>Legal</h4>
                    <ul class="list-unstyled">
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <p>Copyright &copy; 2015 Grubbler LLC. All rights reserved. Hand crafted with <span class="glyphicon glyphicon-heart pink" aria-hidden="true"></span> in NYC.</p>
                </div>
                <div class="col-md-4 text-right">
                    <ul class="list-inline">
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Pinterest</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </footer>