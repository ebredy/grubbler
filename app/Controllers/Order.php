<?php

namespace app\Controllers;

use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Order extends WebController {

    public function index( Request $request, $order_id = null ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin();
        }

        $http_method    = $request->getMethod();
        $this->_layout  = TemplateConstant::LAYOUT_DEFAULT;

        switch ($http_method) {

            case HttpConstant::METHOD_GET:
                return ( $order_id )
                    ? $this->_getOrder( $order_id )
                    : $this->_getOrders();
                break;
            default:
                throw new HttpMethodNotAllowedException("Http method [$http_method] not allowed");

        }

    }

    private function _getOrder( $order_id ) {

        $result = $this->order->view( $order_id );

        return $this->render( TemplateConstant::PAGE_ORDER_DETAILS, $result->getData() );

    }

    private function _getOrders() {

        $result = $this->order->history();

        return $this->render( TemplateConstant::PAGE_ORDER_HISTORY, [ 'orders' => $result->getData() ] );

    }

}
