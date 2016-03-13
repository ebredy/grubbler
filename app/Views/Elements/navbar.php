<nav class="navbar navbar-reddish navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="/">
                <img src="/img/grubbler-white.png" class="logo">
            </a>
        </div>
        <?php echo $__view->render('app/Views/Elements/Forms/search-bar.php'); ?>
        <ul class="nav navbar-nav navbar-right">
            <li class="blog-nav-item" ><a href="/checkout">$<span id="mini-cart-total">0.00</span> <span class="glyphicon glyphicon-shopping-cart" id="top-nav-cart" aria-hidden="true"></span></a></li>
            <?php if ( $__helper->isLoggedIn() ) { ?>
                <li class="blog-nav-item dropdown" >
                    <a href="#" id="my-account" data-toggle="dropdown" aria-expanded="true"><?php echo $__helper->user('name');?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="my-account">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/addresses">Saved Addresses</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/orders">Order History</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/payments">Payment Info</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/settings">Account Settings</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/logout">Logout</a></li>
                    </ul>
                </li>
            <?php } else { ?>
                <li ><a href="/signin">Sign In</a></li>
                <li><a href="/signup">Sign Up</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>