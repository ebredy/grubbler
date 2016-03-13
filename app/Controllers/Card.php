<?php

namespace app\Controllers;

use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Card extends WebController {

    public function index( Request $request, $card_id ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin();
        }

        $this->_layout  = TemplateConstant::LAYOUT_CHECKOUT;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:

                return $this->_manageCard( $request, $card_id );

                break;
            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    private function _manageCard( Request $request, $card_id ) {

        $params = $this->validate->query( $request, [
            '_method'     => 'isAlpha'
        ] );

        if ( !empty( $params['errors'] ) ) {
            $this->flash( 'An error occurred! Please try again.' );
            return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );
        }

        if ( empty( $params['_method'] ) ) {
            return $this->_updateCard( $request, $card_id );
        }

        switch ( strtoupper( $params['_method'] ) ) {

            case HttpConstant::METHOD_DELETE:

                return $this->_deleteCard( $card_id );

                break;
            default:

                throw new HttpMethodNotAllowedException( "Http method [" . $params['_method'] . "] not allowed" );

        }

    }

    private function _deleteCard( $card_id ) {

        $response = $this->card->delete( $card_id );

        if ( $response->isOk() ) {
            $this->flash( 'Your card was successfully deleted' );
            return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );
        }

        $this->flash( 'An error occurred! Please try again.' );

        return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );

    }

    private function _updateCard( Request $request, $card_id ) {

        $params = $this->validate->request( $request, [
            'exp_month'     => 'isInt',
            'exp_year'      => 'isInt',
            'address_zip'   => 'isInt',
        ] );

        if ( !empty( $params['errors'] ) ) {
            $this->flash( 'An error occurred! Please try again.' );
            return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );
        }

        if ( empty( $params ) ) {
            return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );
        }

        $response = $this->card->update( $card_id, $params );

        if ( $response->isOk() ) {
            $this->flash( 'Your card was successfully updated' );
            return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );
        }

        $this->flash( 'An error occurred! Please try again.' );

        return $this->redirect( $this->generateUrl( RouteConstant::PAYMENTS ) );

    }

}
