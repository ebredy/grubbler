<?php

require_once VENDOR_PATH . 'autoload.php';

date_default_timezone_set( TIMEZONE );
$tinypress_exception = 'TinyPress\\Exceptions\\TinyPressException';

set_error_handler( [ $tinypress_exception, 'errorHandler' ] );
set_exception_handler( [ $tinypress_exception, 'exceptionHandler' ] );

use Symfony\Component\Stopwatch\Stopwatch;
$stopwatch = new Stopwatch();
$request_time = $stopwatch->start('request')->getOrigin();

$file_locater                 = new Symfony\Component\Config\FileLocator( [ CONFIG_PATH ] );
$routing_loader               = new Symfony\Component\Routing\Loader\YamlFileLoader( $file_locater );

$container = [];
$container['routes']          = $routing_loader->load( 'routes.yml' );
$container['request_context'] = new Symfony\Component\Routing\RequestContext();

use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;

$container['session'] = new \Symfony\Component\HttpFoundation\Session\Session( null, new NamespacedAttributeBag() );

if ( !$container['session']->isStarted() ) {
    $container['session']->start();
    $container['session']->migrate();
}

use app\Constants\SessionConstant;
$container['session']->set( SessionConstant::REQUEST_TIME, $request_time );

$matcher        = new Symfony\Component\Routing\Matcher\UrlMatcher(
    $container['routes'],
    $container['request_context']
);

$symfony_container = new Symfony\Component\DependencyInjection\ContainerBuilder();
$config_loader     = new Symfony\Component\DependencyInjection\Loader\YamlFileLoader(
    $symfony_container,
    $file_locater
);

foreach ( new DirectoryIterator( CONFIG_PATH ) as $file ) {

    $filename = $file->getFilename();

    if ( empty( $filename )         ||
        $filename == '.'            ||
        $filename == '..'           ||
        $filename == 'routes.yml'   ||
        $file->getExtension() != 'yml' ) {
        continue;
    }

    $config_loader->load( $filename );

}

$dispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();

$dispatcher->addSubscriber( new Symfony\Component\HttpKernel\EventListener\ExceptionListener(
        "{$tinypress_exception}::exceptionHandler" )
);
$dispatcher->addSubscriber( new Symfony\Component\HttpKernel\EventListener\RouterListener(
    $matcher, null, null,
    new Symfony\Component\HttpFoundation\RequestStack() )
);
$dispatcher->addSubscriber( new Symfony\Component\HttpKernel\EventListener\ResponseListener( CHARSET ) );

$http_kernel = new Symfony\Component\HttpKernel\HttpKernel(
    $dispatcher,
    new Symfony\Component\HttpKernel\Controller\ControllerResolver()
);

$container['request']  = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$container['response'] = new Symfony\Component\HttpFoundation\Response();
TinyPress\Services\ContainerService::set( 'core', new TinyPress\Adapters\PimpleAdapter( $container ) );
TinyPress\Services\ContainerService::set( 'symfony', $symfony_container );

$response = $http_kernel->handle( $container['request'] );

$container['session']->set( SessionConstant::PREVIOUS_REQUEST_TIME, $request_time );

$response->send();
