<?php

namespace app\Controllers;

use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Address extends WebController {

    public function index( Request $request, $address_id = null ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin();
        }

        $this->_layout  = TemplateConstant::LAYOUT_CHECKOUT;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:

                return ( $address_id )
                    ? $this->_manageAddress( $request, $address_id )
                    : $this->_createAddress( $request );

                break;
            case HttpConstant::METHOD_GET:

                return ( $address_id )
                    ? $this->_getAddress( $request, $address_id )
                    : $this->_getAddresses( $request );

                break;
            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    private function _manageAddress( Request $request, $address_id ) {

        $query = $this->validate->query( $request, [
            '_method' => 'isAlpha',
        ] );

        $method = ( !empty( $query['_method'] ) )
            ? $query['_method']
            : HttpConstant::METHOD_POST;

        switch ( strtoupper( $method ) ) {

            case HttpConstant::METHOD_DELETE:
                return $this->_deleteAddress( $address_id, $query );
            case HttpConstant::METHOD_PUT:
                return $this->_updateAddress( $request, $address_id );
            default:

                throw new HttpMethodNotAllowedException( "Http method [$method] not allowed" );

        }

    }

    private function _createAddress( Request $request ) {

        $params = $this->validate->request( $request, [
            'fname'     => 'isName|isRequired',
            'lname'     => 'isName|isRequired',
            'apt_number'=> 'isAlpha',
            'address_1' => 'isAddress|isRequired',
            'address_2' => 'isAddress',
            'city'      => 'isAlpha|isRequired',
            'state'     => 'isAlpha|isRequired',
            'zip_code'  => 'isZipCode|isRequired',
            'phone'     => 'isPhone|isRequired',
            'instructions'=>'isAlpha'
        ] );


        $response = $this->address->getRecent();


        $params   = array_merge( $params, $response->getData() );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_MANAGE_ADDRESS, $params );
        }

        $query  = $this->validate->query( $request, [
            'continue' => 'isAlpha',
        ] );

        $response = $this->address->create( $params );

        if ( $response->isOk() ) {

            $this->flash( 'Your new delivery address was successfully created' );

            if ( !empty( $query['continue'] ) ) {
                return $this->_continue( $query['continue'] );
            }

            return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );

        }

        $this->flash( 'Please address the errors bellow' );
        $params['errors'] = $response->getErrors();

        return $this->render( TemplateConstant::PAGE_MANAGE_ADDRESS, $params );

    }

    private function _updateAddress( Request $request, $address_id ) {

        $params = $this->validate->request( $request, [
            'fname'         => 'isName',
            'lname'         => 'isName',
            'address_1'     => 'isAddress',
            'address_2'     => 'isAddress',
            'city'          => 'isAlpha',
            'state'         => 'isAlpha',
            'zip_code'      => 'isZipCode',
            'phone'         => 'isPhone',
            'is_current'    => 'isInt'
        ] );

        $params['id'] = $address_id;

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_EDIT_ADDRESS, $params );
        }

        $query  = $this->validate->query( $request, [
            'continue' => 'isAlpha',
        ] );

        if ( empty( $params ) ) {

            if ( !empty( $query['continue'] ) ) {
                return $this->_continue( $query['continue'] );
            }

            return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );

        }

        if ( isset( $params[ 'is_current' ] ) && ( $params[ 'is_current' ] == true ) ) {
            return $this->_updateCurrentStatus( $address_id, $query );
        }

        $response = $this->address->update( $address_id, $params );

        if ( $response->isOk() ) {

            $this->flash( 'Your delivery address was successfully updated' );

            if ( !empty( $query['continue'] ) ) {
                return $this->_continue( $query['continue'] );
            }

            return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );

        }

        $this->flash( $response->getError( 'flash', 'An error occurred. Please try again' ) );

        return $this->render( TemplateConstant::PAGE_EDIT_ADDRESS, array_merge( $query, $params ) );

    }

    private function _updateCurrentStatus( $address_id, array $query  ) {

        $response = $this->address->setCurrent( $address_id );

        if ( $response->isOk() ) {

            $this->flash( 'Your delivery address has been successfully updated' );

            if ( !empty( $query['continue'] ) ) {
                return $this->_continue( $query['continue'] );
            }

        } else {
            $this->flash( $response->getError( 'flash', 'An error occurred. Please try again' ) );
        }

        return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );

    }

    private function _getAddresses( Request $request ) {

        $query = $this->validate->query( $request, [
            'continue' => 'isRouteKey',
        ] );

        $response = $this->address->getRecent( $query );
        $data     = $response->getData();

        return $this->render( TemplateConstant::PAGE_MANAGE_ADDRESS, $data );

    }

    private function _getAddress( Request $request, $address_id ) {

        $query   = $this->validate->query( $request, [
            'continue' => 'isAlpha',
        ] );

        $response = $this->address->getById( $address_id, $query );

        if ( $response->isOk() ) {
            return $this->render( TemplateConstant::PAGE_EDIT_ADDRESS, $response->getData() );
        }

        $this->flash( 'An error occurred. Please try again' );

        return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );

    }

    private function _deleteAddress( $address_id, array $query = [] ) {

        $response = $this->address->remove( $address_id );

        if ( $response->isOk() ) {

            $this->flash( 'Your delivery address was successfully deleted' );

            if ( !empty( $query['continue'] ) ) {
                return $this->_continue( $query['continue'] );
            }

        } else {
            $this->flash( $response->getError( 'flash', 'An error occurred. Please try again' ) );
        }

        return $this->redirect( $this->generateUrl( RouteConstant::ADDRESSES ) );

    }

}
