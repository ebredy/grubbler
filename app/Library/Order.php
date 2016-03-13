<?php

namespace app\Library;

class Order extends Controller {

    public function save( array $data ) {

        $order   = [];
        $fields  = [ 'source_id', 'receipt_number', 'is_charged', 'user_id', 'card_id', 'amount', 'restaurant_id', 'details' ];

        foreach ( $fields as $field ) {

            if ( isset( $data[ $field ] ) ) {
                $order[ $field ] = $data[ $field ];
            }

        }

        $service_response = $this->_getServiceResponse();

        if ( !empty( $order ) ) {

            $is_saved = $this->orders->save( $order );

            if ( $is_saved ) {
                return $service_response->set( 'id', $this->orders->lastInsertId() );
            }

        }

        return $service_response->setError( 'error', 'An error occurred');

    }

    public function view( $order_id ) {

        $user_id = $this->_getUser( 'id' );
        $order   = $this->orders->read( [
            'user_id' => $user_id,
            'id'      => $order_id
        ] );

        return $this->_getServiceResponse()->setData( $order );

    }

    public function history() {

        $user_id = $this->_getUser( 'id' );
        $orders  = $this->orders->getRecent( $user_id );

        foreach ( $orders as $index => $order ) {
            $orders[ $index ] = array_merge( $orders[ $index ], json_decode( $order['details'], true ) );
            unset( $orders[ $index ]['details'] );
        }

        return $this->_getServiceResponse()->setData( $orders );

    }

}
