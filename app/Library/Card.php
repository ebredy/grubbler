<?php

namespace app\Library;

class Card extends Controller {

    public function save( array $data ) {

        $card   = [];
        $fields = [
            'source_id', 'user_id', 'brand', 'last_4', 'funding', 'country_code',
            'exp_month', 'exp_year', 'holder_name', 'address_line1', 'address_line2',
            'address_city', 'address_state', 'address_zip', 'address_country'
        ];

        foreach ( $fields as $field ) {

            if ( isset( $data[ $field ] ) ) {
                $card[ $field ] = $data[ $field ];
            }

        }

        $service_response = $this->_getServiceResponse();

        if ( !empty( $card ) ) {

            $is_saved = $this->cards->save( $card );

            if ( $is_saved ) {
                return $service_response->set( 'id', $this->cards->lastInsertId() );
            }

        }

        return $service_response->setError( 'error', 'An error occurred');

    }

    public function delete( $card_id ) {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $customer_id       = $this->_getUser( 'customer_id' );

        if ( !$customer_id ) {
            return $service_response->setError( 'customer_id', 'User is missing account id');
        }

        $card = $this->cards->read( [
            'user_id' => $user_id,
            'id'      => $card_id
        ] );

        if ( empty( $card['source_id'] ) ) {
            return $service_response->setError( 'error', 'Invalid card');
        }

        $response = $this->payment->deleteCard( $customer_id, $card['source_id'] );

        if ( !$response->isOk() ) {
            return $service_response->setError( 'error', 'Invalid card');
        }

        $this->cards->delete( [ 'id' => $card_id ] );

        return $service_response;

    }

    public function update( $card_id, array $params ) {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $customer_id       = $this->_getUser( 'customer_id' );

        if ( !$customer_id ) {
            return $service_response->setError( 'customer_id', 'User is missing customer id');
        }

        $card = $this->cards->read( [
            'user_id' => $user_id,
            'id'      => $card_id
        ] );

        if ( empty( $card['source_id'] ) ) {
            return $service_response->setError( 'error', 'Invalid card');
        }

        $update = [];

        foreach ( $params as $field => $value ) {

            if ( isset( $card[ $field ] ) && ( $card[ $field ] != $value )  ) {
                $update[ $field ] = $value;
            }

        }

        if ( empty( $update ) ) {
            return $service_response->setError( 'error', 'Invalid card');
        }

        $response = $this->payment->updateCard( $customer_id, $card['source_id'], $update );

        if ( !$response->isOk() ) {
            return $service_response->setError( 'error', 'Invalid card');
        }

        $this->cards->update( [ 'id' => $card_id ], $update );

        return $service_response;

    }

    public function view( $card_id ) {

        $user_id = $this->_getUser( 'id' );
        $card    = $this->cards->read( [
            'user_id' => $user_id,
            'id'      => $card_id
        ] );

        return $this->_getServiceResponse()->setData( $card );

    }

    public function viewAll() {

        $user_id = $this->_getUser( 'id' );
        $cards   = $this->cards->getRecent( $user_id );


        return $this->_getServiceResponse()->setData( $cards );

    }

}
