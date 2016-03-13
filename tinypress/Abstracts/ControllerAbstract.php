<?php

namespace TinyPress\Abstracts;

use app\Constants\MessageConstant;
use app\Constants\RouteConstant;
use app\Constants\SessionConstant;
use TinyPress\Exceptions\FileNotFoundException;
use TinyPress\Interfaces\ControllerInterface;
use TinyPress\Exceptions\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TinyPress\Services\ContainerService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class ControllerAbstract implements ControllerInterface {

    protected $_layout;
    private   $_containers;

    public function redirect( $url, $status = 302 ) {

        return new RedirectResponse( $url, $status );

    }

    public function __get( $service_name ) {

        return $this->get( $service_name );

    }

    public function get( $service_name ) {

        $container = $this->getContainer();

        if ( !$container['core']->has( $service_name ) ) {
            throw new ServiceNotFoundException( "Service [$service_name] not found" );
        }

        $service =  $container['core']->get( $service_name );

        if ( method_exists( $service, 'setContainer' ) ) {
            $service->setContainer( $container );
        }

        return $service;

    }

    public function generateUrl( $route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_URL ) {

        return $this->urlgenerator->generate( $route, $parameters, $referenceType );

    }

    public function getContainer() {

        if ( !$this->_containers ) {
            $this->_containers['core']    = ContainerService::get( 'core' );
            $this->_containers['symfony'] = ContainerService::get( 'symfony' );
        }

        return $this->_containers;

    }

    public function getParameter( $parameter ) {

        return $this->getContainer()['symfony']->getParameter( $parameter );

    }

    public function render( $template, $vars = null ) {

        if ( !$this->_layout ) {
            throw new FileNotFoundException( 'Please specify a layout template' );
        }

        if ( !is_array( $vars ) ) {
            $vars = [];
        }

        $response = $this->view->render( $this->_layout, array_merge( $vars, [
                '__content' => $this->view->render( $template, $vars )
            ]
        ) );

        return $this->response->setContent( $response );

    }

    public function flash( $message ) {

        if ( !empty( $message ) ) {
            $this->session->getFlashBag()->add( SessionConstant::FLASH, trim( $message ) );
        }

    }

    protected function _requestLogin( $message = MessageConstant::LOGIN_REQUIRED ) {

        $this->session->set( SessionConstant::FORWARD, $this->request->getRequestUri() );
        $this->flash( $message );

        return $this->redirect( $this->generateUrl( RouteConstant::LOGIN ) );

    }

    protected function _ajaxSuccess( $data = '', $code = 200 ) {

        $response = [
            'status'    => 'success',
            'code'      => $code,
            'data'      => $data
        ];

        return $this->_ajax( $response );

    }

    protected function _ajaxError( $data = '', $code = 500 ) {

        $response = [
            'status' => 'error',
            'code'   => $code,
            'data'   => $data
        ];

        return $this->_ajax( $response );

    }

    protected function _ajaxRedirect( $url, $code = 307 ) {

        $response = [
            'status'    => 'redirect',
            'code'      => $code,
            'data'      => $url
        ];

        return $this->_ajax( $response );

    }

    protected function _ajax( array $data ) {

        $this->response->headers->set( 'Content-Type', 'application/json' );

        return $this->response->setContent( json_encode( $data ) );

    }

}
