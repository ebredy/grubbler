<?php

namespace app\Controllers;

use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Payment extends WebController {

    public function index( Request $request, $payment_id = null ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin();
        }

        $http_method    = $request->getMethod();
        $this->_layout  = TemplateConstant::LAYOUT_STRIPE;

        switch ($http_method) {

            case HttpConstant::METHOD_GET:
                return ( $payment_id )
                    ? $this->_getPayment( $payment_id )
                    : $this->_getPayments();
                break;
            default:
                throw new HttpMethodNotAllowedException("Http method [$http_method] not allowed");

        }

    }

    private function _getPayment( $card_id ) {

        $result = $this->card->view( $card_id );

        return $this->render( TemplateConstant::PAGE_CARD_DETAILS, $result->getData() );

    }

    private function _getPayments() {

        $result = $this->card->viewAll();

        return $this->render( TemplateConstant::PAGE_CARDS, [ 'cards' => $result->getData() ] );

    }

}
