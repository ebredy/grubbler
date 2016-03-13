<?php

namespace app\Adapters;

use app\Interfaces\PaymentInterface;
use app\Library\Response;
use Stripe\Stripe;
use \Stripe\Error\InvalidRequest;
use \Stripe\Error\Authentication;
use \Stripe\Error\ApiConnection;
use \Stripe\Error\Card;
use \Stripe\Error\Base;
use TinyPress\Services\ContainerService;

class StripeAdapter implements PaymentInterface {

    public function __construct() {

        $container = ContainerService::get( 'symfony' );
        $stripe    = $container->getParameter( 'stripe' );
        Stripe::setApiKey( $stripe[ 'secret_key' ] );

    }

    public function createCharge( array $params ) {

        $response = $this->_getResponse();

        if ( empty( $params['amount'] ) || empty( $params['currency'] ) ) {
            return $response->setError( 'message', 'Amount and currency are required fields' );
        }

        $charge = $this->_submit( [ 'Stripe\Charge', 'create' ], [ $params ] );

        if ( !is_object( $charge ) || !$charge->paid ) {
            return $response->setError( 'payment', 'An error occurred! Please try again' );
        }

        $data = [
            'charge_id'         => $charge->id,
            'source_id'         => $charge->source->id,
            'brand'             => $charge->source->brand,
            'last_4'            => $charge->source->last4,
            'funding'           => $charge->source->funding,
            'country_code'      => $charge->source->country,
            'exp_month'         => $charge->source->exp_month,
            'exp_year'          => $charge->source->exp_year,
            'holder_name'       => $charge->source->name,
            'address_line1'     => $charge->source->address_line1,
            'address_line2'     => $charge->source->address_line2,
            'address_city'      => $charge->source->address_city,
            'address_state'     => $charge->source->address_state,
            'address_zip'       => $charge->source->address_zip,
            'address_country'   => $charge->source->address_country
        ];

        return $response->setData( $data );

    }

    public function updateCard( $customer_id, $card_id, array $data ) {

        $service_response  = $this->_getResponse();
        $customer          = $this->_submit( [ 'Stripe\Customer', 'retrieve' ], [ $customer_id ] );

        if ( !is_object( $customer ) ) {
            //TODO: handle errors better
            return $service_response->setError( 'customer_id', 'invalid customer id');
        }

        $card = $customer->sources->retrieve( $card_id );

        if ( !is_object( $card ) ) {
            //TODO: handle errors better
            return $service_response->setError( 'card_id', 'invalid card id');
        }

        foreach ( $data as $field => $value ) {
            $card->{$field} = $value;
        }

        try {

            $card_response = $card->save();
            return $service_response;

        } catch ( \Exception $e ) {}

        return $service_response->setError( 'error', 'Unable to update card');

    }

    public function createCard( $customer_id, $token ) {

        $response  = $this->_getResponse();
        $customer  = $this->_submit( [ 'Stripe\Customer', 'retrieve' ], [ $customer_id ] );

        if ( !is_object( $customer ) ) {
            //TODO: handle error
            return $response->setError('payment', 'unable to retrieve card on file customer id: '.$customer_id);
        }

        $card = $customer->sources->create( [ 'source' => $token ] );

        if ( !is_object( $card ) ) {
            //TODO: handle error
        }

        $data = [
            'source_id'         => $card->id,
            'brand'             => $card->brand,
            'last_4'            => $card->last4,
            'funding'           => $card->funding,
            'country_code'      => $card->country,
            'exp_month'         => $card->exp_month,
            'exp_year'          => $card->exp_year,
            'holder_name'       => $card->name,
            'address_line1'     => $card->address_line1,
            'address_line2'     => $card->address_line2,
            'address_city'      => $card->address_city,
            'address_state'     => $card->address_state,
            'address_zip'       => $card->address_zip,
            'address_country'   => $card->address_country
        ];

        return $response->setData( $data );

    }


    public function createAccount( array $data = [] ) {

        $response = $this->_getResponse();
        $account  = $this->_submit( [ 'Stripe\Account', 'create' ], [ [
            'managed' => true,
            'country' => 'US'
        ] ] );

        if ( !is_object( $account ) || !$account->id ) {
            return $response->setError( 'payment', 'An error occurred! Please try again' );
        }

        return $response->set( 'id', $account->id );

    }
    public function createCustomer(  $payment_token , $user_id ) {

        $response = $this->_getResponse();
        $customer  = $this->_submit( [ 'Stripe\Customer', 'create' ], [ [
            'description' => $user_id,
            'source' => $payment_token
        ] ] );

        if ( !is_object( $customer ) || !$customer->id ) {
            return $response->setError( 'payment', 'An error occured creating a customer account! Please try again' );
        }

        return $response->set( 'id', $customer->id );

    }
    public function deleteCard( $customer_id, $card_id ) {

        $service_response  = $this->_getResponse();
        $customer          = $this->_submit( [ 'Stripe\Customer', 'retrieve' ], [ $customer_id ] );

        if ( !is_object( $customer ) ) {
            //TODO: handle errors better
            return $service_response->setError( 'customer_id', 'invalid account id');
        }

        $card = $customer->sources->retrieve( $card_id );

        if ( !is_object( $card ) ) {
            //TODO: handle errors better
            return $service_response->setError( 'card_id', 'invalid card id');
        }

        try {

            $card_response = $card->delete();
            return $service_response;

        } catch ( \Exception $e ) {}

        return $service_response->setError( 'error', 'Unable to delete card');

    }

    public function retrieveAccount( $account_id ) {

        return [];

    }

    public function updateAccount( $account_id, array $data ) {

        return [];

    }

    public function retrieveCharge( $charge_id ) {

        return [];

    }

    public function updateCharge( $charge_id, array $data ) {

        return [];

    }

    public function captureCharge( $charge_id, array $data = [] ) {

        return [];

    }

    public function createRefund( $charge_id, array $data = [] ) {

        return [];

    }

    public function retrieveRefund( $charge_id, $refund_id ) {

        return [];

    }

    public function updateRefund( $charge_id, $refund_id, array $data ) {

        return [];

    }

    public function retrieveCard( $customer_id, $card_id ) {

        return [];

    }

    public function listCards( $customer_id, $limit = 10 ) {

        return [];

    }

    private function _getResponse() {

        return new Response();

    }

    private function _submit( array $method, array $args = [] ) {

        $errors = [];

        try {

            return call_user_func_array( $method, $args );

        } catch( Card $e) {

            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();

            if ( !empty( $body['error'] ) ) {
                $errors = $body['error'];
            }

        } catch ( InvalidRequest $e) {

            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();

            if ( !empty( $body['error'] ) ) {
                $errors = $body['error'];
            }

        } catch ( Authentication $e) {

            // Authentication with Stripe's API failed (maybe you changed API keys recently)
            $body = $e->getJsonBody();

            if ( !empty( $body['error'] ) ) {
                $errors = $body['error'];
            }

        } catch ( ApiConnection $e) {

            // Network communication with Stripe failed
            $body = $e->getJsonBody();

            if ( !empty( $body['error'] ) ) {
                $errors = $body['error'];
            }

        } catch ( Base $e) {

            // Display a very generic error to the user, and maybe send yourself an email
            $body = $e->getJsonBody();

            if ( !empty( $body['error'] ) ) {
                $errors = $body['error'];
            }

        } catch ( \Exception $e) {

            // Something else happened, completely unrelated to Stripe
            $errors['message']  = $e->getMessage();
            $errors['code']     = $e->getCode();

        }

        return [ 'error' => $errors ];

    }

}