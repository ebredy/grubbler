<?php

$container = [];
$container['stripe'] = function ($c) {
    return new \app\Adapters\StripeAdapter();
};
$container['payment'] = function ($c) {
    return new \app\Services\Payment( $c['stripe'] );
};
$container['view'] = function ($c) {
    return new \TinyPress\Adapters\SymfonyTemplatingAdapter();
};
$container['validate'] = function ($c) {
    return new \app\Services\Validate();
};
$container['httpcurl'] = function ($c) {
    return new \app\Services\HttpCurl();
};
$container['card'] = function ($c) {
    return new \app\Library\Card();
};
$container['order'] = function ($c) {
    return new \app\Library\Order();
};
$container['map'] = function ($c) {
    return new \app\Services\Map();
};
$container['users'] = function ($c) {
    return new \app\Models\Users();
};
$container['orders'] = function ($c) {
    return new \app\Models\Orders();
};
$container['cards'] = function ($c) {
    return new \app\Models\Cards();
};
$container['user'] = function ($c) {
    return new \app\Library\User();
};
$container['menus'] = function ($c) {
    return new \app\Models\Menus();
};
$container['cities'] = function ($c) {
    return new \app\Models\Cities();
};
$container['states'] = function ($c) {
    return new \app\Models\States();
};
$container['restaurants'] = function ($c) {
    return new \app\Models\Restaurants();
};
$container['restaurant'] = function ($c) {
    return new \app\Library\Restaurant();
};
$container['addresses'] = function ($c) {
    return new \app\Models\Addresses();
};

$container['address'] = function ($c) {
    return new \app\Library\Address();
};
$container['ordernotification'] = function ($c) {
    return new \app\Library\OrderNotification();
};
$container['ordernotifications'] = function ($c) {
    return new \app\Models\OrderNotifications();
};
$container['cart'] = function ($c) {
    return new \app\Library\Cart();
};
$container['doctrine'] = function ($c) {
    return new \TinyPress\Adapters\DoctrineAdapter();
};
$container['phpass'] = function ($c) {
    return new Hautelook\Phpass\PasswordHash( 8,false );
};
$container['security'] = function ($c) {
    return new \app\Services\Security();
};
$container['viewhelper'] = function ($c) {
    return new \app\Helpers\ViewHelper();
};
$container['urlgenerator'] = function ($c) {

    return new \Symfony\Component\Routing\Generator\UrlGenerator(
        $c['routes'],
        $c['request_context']
    );

};
return $container;