<?php echo $__view->render('app/Views/Elements/navbar.php'); ?>
    <div class="masthead text-center" style="background-image: url(/img/couch.png)" xmlns="http://www.w3.org/1999/html">
        <div class="overlay">
            <div class="container">
                <h1>Food Delivery that's Simplified<br/>Personalized & Fun</h1>
                <form class="form-inline" action="/restaurants" method="GET">
                    <input class="field" id="street_number" name="street_number" type="hidden">
                    <input class="field" id="route" name="street_address" type="hidden">
                    <input class="field" id="latitude" name="latitude" type="hidden">
                    <input class="field" id="longitude" name="longitude" type="hidden">
                    <input class="field" id="administrative_area_level_1" name="state" type="hidden">
                    <input class="field" id="postal_code" name="postal_code" type="hidden">
                    <input class="field" id="country" name="country" type="hidden">
                    <div class="form-group form-group-lg large-search">
                        <input type="text" autofocus="autofocus" id="address" name="address" class="form-control" placeholder="Enter your delivery address">
                    </div>
                    <button type="submit" class="btn btn-orange btn-lg"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </form>
            </div>
        </div>
    </div>
    <div class="masthead-footer" style="background: #fd9b2a"></div>
    <div class="container">
        <div class="row text-center page-header">
            <h3><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> Need Help Deciding?</h3>
            <h4 style="margin: 0 auto;width: 550px;padding: 10px;border-radius: 3px;background: lightgoldenrodyellow">Select the Food Type You're in the Mood For Bellow</h4>
            <p class="text-muted small"><strong>Tip:</strong> Our algorithm gets smarter and adapts to your tastes the more you use it. <a href="#">Learn more</a></p>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <a href="#" class="thumbnail">
                    <img src="/img/food/a.png" alt="...">
                </a>
            </div>
            <div class="col-xs-6 col-md-4">
                <a href="#" class="thumbnail">
                    <img src="/img/food/b.png" alt="...">
                </a>
            </div>
            <div class="col-xs-6 col-md-4">
                <a href="#" class="thumbnail">
                    <img src="/img/food/c.png" alt="...">
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <a href="#" class="thumbnail">
                    <img src="/img/food/d.png" alt="...">
                </a>
            </div>
            <div class="col-xs-6 col-md-4">
                <a href="#" class="thumbnail">
                    <img src="/img/food/e.png" alt="...">
                </a>
            </div>
            <div class="col-xs-6 col-md-4">
                <a href="#" class="thumbnail">
                    <img src="/img/food/f.png" alt="...">
                </a>
            </div>
        </div>
    </div>

    <div class="container apps-info panel">
        <div class="panel-body">
            <div class="row text-center">
                <ul class="app-links">
                    <li><a href=""><img src="/img/app-store.svg"></a></li>
                    <li><a href=""><img src="/img/google-play.svg"></a></li>
                </ul>
            </div>
        </div>
    </div>
<?php echo $__view->render('app/Views/Elements/footer.php'); ?>