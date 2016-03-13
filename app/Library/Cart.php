<?php

namespace app\Library;

use app\Constants\CookieConstant;
use app\Constants\SessionConstant;

class Cart extends Controller {


    public function view( $restaurant_id = null ) {

        $service_response   = $this->_getServiceResponse();
        $cart               = $this->_getCart( $restaurant_id );
        $cart               = $this->_computeCart( $cart );

        if ( $cart ) {
            return $service_response->setData( $cart );
        }

        return $service_response;

    }

    public function removeItem( $item_id ) {

        $service_response   = $this->_getServiceResponse();
        $cart               = $this->_getCart();

        if ( empty( $cart[ 'items' ][ $item_id ] ) ) {
            return $service_response;
        }

        unset( $cart[ 'items' ][ $item_id ] );
        $this->_setCart( $cart );

        return $this->_ajaxSuccess();

    }

    public function addItem( array $params ) {

        $service_response   = $this->_getServiceResponse();
        $item               = $this->menus->read( [
            'id'            => $params['menu_id'],
            'restaurant_id' => $params['restaurant_id']
        ] );

        if ( !$item ) {
            return $service_response->setError( 'error', 'Menu item with id ' . $params['menu_id'] . ' not found' );
        }

        $cart = $this->_getCart( $params['restaurant_id'] );

        if ( !$cart ) {

            $cart = $this->_createCart( $params['restaurant_id'] );

            if ( !$cart ) {
                return $service_response->setError( 'error', 'Failed to create cart for restaurant ' . $params['restaurant_id'] );
            }

        }

        $cart_id                    = uniqid();
        $item['cart_id']            = $cart_id;
        $item['quantity']           = $params['quantity'];
        $item['total_price']        = ( $params['quantity'] * $item['price'] );
        $item['instructions']       = !empty( $params['instructions'] ) ? $params['instructions'] : null;
        $cart['items'][ $cart_id ]  = $item;

        $this->_setCart( $cart );

        return $service_response;


    }

    public function editItem( $item_id, $params ) {

        $service_response = $this->_getServiceResponse();
        $cart             = $this->_getCart();

        if ( !isset( $cart[ 'items' ][ $item_id ] ) ) {
            return $service_response;
        }

        if ( isset( $params['quantity'] ) ) {

            if ( $params['quantity'] == 0 ) {
                return $this->removeItem( $item_id );
            }

            $cart[ 'items' ][ $item_id ]['quantity'] = $params['quantity'];

        }

        if ( isset( $params['instructions'] ) ) {
            $cart[ 'items' ][ $item_id ]['instructions'] = $params['instructions'];
        }

        $this->_setCart( $cart );

        return $service_response;

    }
    public function updateTip( $tip ) {

        $service_response = $this->_getServiceResponse();
        $cart             = $this->_getCart();

        if ( !isset( $cart[ 'summary' ]['tip'] ) ) {
            return $service_response;
        }

        $cart[ 'summary' ]['tip']  = $tip;

        $this->_setCart( $cart );

        return $service_response;

    }
    public function getItem( $item_id ) {

        $service_response   = $this->_getServiceResponse();
        $cart               = $this->_getCart();

        if ( empty( $cart[ 'items' ][ $item_id ] ) ) {
            return $service_response->setError( 'error', "Menu item with id [$item_id] not found" );
        }

        $menu                   = $cart[ 'items' ][ $item_id ];
        $menu['action']         = "/cart/$item_id";
        $menu['method']         = "POST";
        $menu['button_text']    = 'Update Order';

        return $service_response->setData( $menu );

    }

    public function getAddItemForm( $menu_id ) {

        $service_response   = $this->_getServiceResponse();
        $menu               = $this->menus->read( [ 'id' => $menu_id ] );

        if ( !$menu ) {
            return $service_response->setError( 'menu_id', 'Menu not found!' );
        }

        $menu['action']         = "/cart";
        $menu['method']         = "PUT";
        $menu['button_text']    = 'Add to Order';
        $menu['quantity']       = 1;
        $menu['total_price']    = $menu['price'];

        return $service_response->setData( $menu );

    }

    public function checkout( array $params ) {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $cart             = $this->_computeCart( $this->_getCart() );

        if ( empty( $cart['summary']['grand_total'] ) ) {
            return $service_response->setError( 'cart', 'Empty cart' );
        }

        $amount  = ( $cart['summary']['grand_total'] * 100 );
        
        $address = $this->addresses->getCurrent( $user_id );

        if ( empty( $address['id'] ) || ( $address['id'] !== $params['address_id'] ) ) {
            return $service_response->setError( 'address_id', 'Invalid address' );
        }

        $charge = [
            'amount'        => $amount,
            'currency'      => 'usd',
            'description'   => $user_id
        ];

        //$customer_id = 'cus_6Ll8ztdxU4W4ty';//$this->_getUser( 'customer_id' );
        $customer_id =  $this->_getUser( 'customer_id' );
        if ( !$customer_id ) {

            
            $customer = $this->payment->createCustomer( $params['payment_token'], $user_id );

            if ( !$customer->isOk() ) {
                return $service_response->setError( 'account', 'Failed to create customer' );
            }

            $customer_id = $customer->get( 'id' );
            $this->users->update( [ 'id' => $user_id ], [ 'customer_id' => $customer_id ] );
            $this->_setUserAttr( 'customer_id', $customer_id );
            

        }

        $charge['customer'] = $customer_id;

        if ( !empty( $params['card_id'] ) ) {

            $card = $this->cards->read( [
                    'id'        => $params['card_id'],
                    'user_id'   => $user_id
            ] );
 
            if ( empty( $card ) ) {
                return $service_response->setError( 'card_id', 'Invalid card code CNF' );
            }

        } else {

            $card_response = $this->payment->createCard( $customer_id, $params['payment_token'] );

            if ( !$card_response->isOk() ) {
                //TODO: handle errors
                return $service_response->setError( 'card_id', 'Invalid card code CCC additional info: '.$card_response->getError('payment') ); //CCC - can't create card
            }

            $card               = $card_response->getData();
            $card['user_id']    = $user_id;
            $result             = $this->card->save( $card );

            if ( !$result->isOk() ) {
                //TODO: log error then do something
                return $service_response->setError( 'card_id', 'Invalid card code CSC' ); /// can't save card
            }

            $params['card_id'] = $result->get('id');

        }

        $charge['source'] = $card['source_id'];
        $charge_response  = $this->payment->createCharge( $charge );

        if ( !$charge_response->isOk() ) {
            return $charge_response;
        }

        $this->cards->update(
            [ 'id'        => $params['card_id'] ],
            [ 'last_used' => $this->_now() ]
        );

        $this->_emptyCart();
        $charge = $charge_response->getdata();

        $cart['delivered_to'] = $address;
        $order                = [
            'details'        => json_encode( $cart ),
            'receipt_number' => $this->_getReceiptNumber( $user_id ),
            'restaurant_id'  => $cart['restaurant']['id'],
            'amount'         => $amount,
            'user_id'        => $user_id,
            'is_charged'     => 1,
            'card_id'        => $params['card_id'],
            'source_id'      => $charge['charge_id']
        ];
        $order_response = $this->order->save( $order );
        
        if(!$order_response->isOk()){

            return $service_response->setError( 'order', 'unable to save order' );
            
        }

        $order_id =  $order_response->get('id');
        
        $this->ordernotification->queue($order_id);
        
        
        return $service_response;

    }

    private function _getReceiptNumber( $user_id ) {

        return uniqid();

    }

    private function _setCart( array $cart ) {

        $this->session->set( SessionConstant::CART, $cart );

    }

    private function _emptyCart() {

        $this->session->remove( SessionConstant::CART );
        $this->response->headers->clearCookie( CookieConstant::MINI_CART );

    }

    private function _getCart( $restaurant_id = null ) {

        $cart = (array)$this->session->get( SessionConstant::CART );
        if ( !$restaurant_id ) {
            return $cart;
        }

        return ( !empty( $cart['restaurant'][ 'id' ] )
            && ( $cart['restaurant'][ 'id' ] == $restaurant_id ) ) ? $cart : [];

    }

    private function _createCart( $restaurant_id ) {

        $restaurant = $this->restaurants->read( [
            'id' => $restaurant_id
        ]);

        if ( !$restaurant ) {
            return [];
        }

        return [
            'items'         => [],
            'restaurant'    => $restaurant,
            'summary'       => [
                'tip'           => 0.00,
                'sales_tax'     => 0.00,
                'item_total'    => 0.00,
                'grand_total'   => 0.00,
                'created_on'    => time()
            ]
        ];

    }

    private function _computeCart( array $cart ) {

        if ( !$cart ) {
            return $cart;
        }

        foreach ( $cart['items'] as $cart_id => $item ) {

            //TODO: Store numbers only, with $$$ signs!
            $total_price = ltrim( $item['price'], '$' ) * $item['quantity'];

            $total_price = number_format( $total_price, 2 );
            $cart['summary']['item_total'] += $total_price;
            $cart['items'][$cart_id]['total_price'] = $total_price;

        }

        $sales_tax   = number_format( $cart['summary']['item_total'] * 0.08, 2 );
        $cart['summary']['sales_tax']   = sprintf('%0.2f', $sales_tax);
        $grand_total = $cart['summary']['item_total'] + $cart['summary']['sales_tax'] + $cart['summary']['tip'];
        $cart['summary']['grand_total'] = sprintf('%0.2f', $grand_total);

        return $cart;

    }

    private function _submitOrder( array $params, $receipt ) {

        return [];

    }

    private function _verifyAddress( $user_id, array $params ) {

        $response = [
            'errors'  => [],
            'address' => null
        ];

        if ( empty( $params['delivery']['address_id'] ) ) {
            $response['errors'] = [
                'address_id' => 'A delivery address is required!'
            ];
            return $response;
        }

        $address = $this->addresses->read( [
            'id'        => $params['delivery']['address_id'],
            'user_id'   => $user_id
        ] );

        if ( $address ) {
            $response['address'] = $address;
        }
        else {
            $response['errors'] = [
                'address_id' => 'Invalid delivery address!'
            ];
        }

        return $response;

    }

}
