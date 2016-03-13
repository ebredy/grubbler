<?php

namespace app\Controllers;


use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Checkout extends WebController {

    public function index( Request $request ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin( 'Ready to checkout? Please login!' );
        }

        $http_method    = $request->getMethod();
        $this->_layout  = TemplateConstant::LAYOUT_STRIPE;

        switch ($http_method) {

            case HttpConstant::METHOD_POST:
                return $this->_checkout( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->_getCheckout();
                break;
            default:
                throw new HttpMethodNotAllowedException("Http method [$http_method] not allowed");

        }

    }

    private function _getCheckout() {

        $user_id = $this->_getUser( 'id' );
        $address = $this->addresses->getCurrent( $user_id );

        if ( empty( $address ) ) {
            $this->flash( 'Please add a delivery address' );
            return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );
        }

        $response = $this->cart->view();
        $cart     = $response->getData();
        $cards    = $this->cards->getRecent( $user_id );

        
        $restaurant_id = ( isset( $cart['restaurant']['id'] ) )
            ? $cart['restaurant']['id']
            : null;

        return $this->render( TemplateConstant::PAGE_CHECKOUT, [
            'restaurant_id'  => $restaurant_id,
            'address'        => $address,
            'cards'          => $cards
        ] );

    }

    private function _checkout( Request $request ) {

        $params = $this->validate->request( $request, [
            'payment_token' => 'isToken',
            'address_id'    => 'isInt|isRequired',
            'card_id'       => 'isInt'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_CHECKOUT, $params );
        }

        if ( empty( $params['payment_token'] ) && empty( $params['card_id'] ) ) {
            $this->flash( 'Something went wrong. Please try again' );
            return $this->redirect( $this->generateUrl( RouteConstant::CHECKOUT ) );
        }

        $user_id = $this->_getUser( 'id' );
        $address = $this->addresses->getCurrent( $user_id );

        if ( empty( $address ) ) {
            $this->flash( 'Please add a delivery address' );
            return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );
        }

        $response = $this->cart->checkout( $params );

        if ( !$response->isOk() ) {
            $this->flash( 'Something went wrong. Please try again');
            return $this->redirect( $this->generateUrl( RouteConstant::CHECKOUT ) );
        }

        return $this->render( TemplateConstant::PAGE_CONFIRMATION, $response->getData() );

    }

}
