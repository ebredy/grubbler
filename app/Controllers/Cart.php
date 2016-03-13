<?php

namespace app\Controllers;

use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use TinyPress\Exceptions\BadRequestHttpException;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Cart extends WebController {

    public function index( Request $request, $item_id = null ) {

        if ( !$request->isXmlHttpRequest() ) {
            //throw new BadRequestHttpException( 'Only Ajax requests allowed!');
        }

        $http_method = $request->getMethod();

        switch ($http_method) {

            case HttpConstant::METHOD_GET:

                return ( $item_id )
                    ? $this->_getEditForm( $item_id )
                    : $this->_getCart( $request );

            break;

            case HttpConstant::METHOD_PUT:

                if ( $item_id ) {
                    throw new BadRequestHttpException();
                }

                return $this->_addItem( $request );

            break;

            case HttpConstant::METHOD_POST:

                if ( !$item_id ) {
                    throw new BadRequestHttpException();
                }

                return $this->_editItem( $request, $item_id );

            break;

            case HttpConstant::METHOD_DELETE:

                if ( !$item_id ) {
                    throw new BadRequestHttpException();
                }

                return $this->_deleteItem( $item_id );

                break;

            default:
                throw new HttpMethodNotAllowedException("Http method [$http_method] not allowed");

        }

    }

    public function getAddItemForm( $menu_id ) {

        $response   = $this->cart->getAddItemForm( $menu_id );
        $form       = $this->view->render( TemplateConstant::ELEMENT_MENU_ITEM, $response->getData() );

        return $this->_ajaxSuccess( $form );

    }

    private function _getCart( Request $request ) {

        $params = $this->validate->request( $request, [
            'restaurant_id' => 'isInt'
        ] );

        $restaurant_id = !empty( $params['restaurant_id'] )
            ? $params['restaurant_id']
            : null;

        $response       = $this->cart->view( $restaurant_id );
        $data           = $response->getData();
        $cart_template  = $this->view->render( TemplateConstant::ELEMENT_CART, $data );

        return $this->_ajaxSuccess( $cart_template );

    }

    //TODO: Must validate signature inputs in routes config
    private function _getEditForm( $item_id ) {

        $response       = $this->cart->getItem( $item_id );
        $data           = $response->getData();
        $cart_template  = $this->view->render( TemplateConstant::ELEMENT_MENU_ITEM, $data );

        return $this->_ajaxSuccess( $cart_template );

    }

    private function _editItem( $request, $item_id  ) {

        $params = $this->validate->request( $request, [
            'quantity'      => 'isNumeric',
            'instructions'  => 'isSafeString'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->_ajaxError( 'Invalid parameters' );
        }

        if ( empty( $params ) ) {
            return $this->_ajaxSuccess();
        }

        $response = $this->cart->editItem( $item_id, $params );

        return $this->_ajaxSuccess();

    }

    private function _addItem( $request ) {

        $params = $this->validate->request( $request, [
            'quantity'      => 'isNumeric|isRequired',
            'menu_id'       => 'isNumeric|isRequired',
            'restaurant_id' => 'isNumeric|isRequired',
            'instructions'  => 'isSafeString'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->_ajaxError( 'Invalid parameters' );
        }

        $response = $this->cart->addItem( $params );

        return $this->_ajaxSuccess();

    }

    private function _deleteItem( $item_id ) {

        $response = $this->cart->removeItem( $item_id );

        if ( !$response->isOk() ) {
            return $this->_ajaxError( "Failed to remove item [$item_id] from cart for restaurant [$restaurant_id]" );
        }

        return $this->_ajaxSuccess();

    }
    
    public function updateTip( Request $request){

        if ( !$request->isXmlHttpRequest() ) {
            throw new BadRequestHttpException( 'Only Ajax requests allowed!');
        }

        $http_method = $request->getMethod();

        switch ($http_method) {



            case HttpConstant::METHOD_POST:

                return $this->_updateTip( $request );

            break;


            default:
                throw new HttpMethodNotAllowedException("Http method [$http_method] not allowed");

        }
        
    }

    public function _updateTip( Request $request ) {

        
      

       $params = $this->validate->request( $request, [
            'tip'      => 'isMoney'
        ] );
        
        if ( !empty( $params['errors'] ) ) {
            return $this->_ajaxError( 'Error updating tip' );
        }
        $response   = $this->cart->updateTip( $params['tip'] );
        return $this->_ajaxSuccess( $response );

    }
    

}