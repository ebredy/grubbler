<?php

namespace TinyPress\Listeners;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TinyPress\Abstracts\ControllerAbstract;

class ControllerListener implements EventSubscriberInterface
{
    private $_container;

    public function __construct( $container )
    {
        $this->_container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {

        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ( $controller[0] instanceof ControllerAbstract ) {
            $controller[0]->setContainer( $this->_container );
        }

    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
