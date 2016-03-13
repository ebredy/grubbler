<?php

namespace app\Library;

use app\Constants\MessageConstant;
use app\Constants\SessionConstant;
use Symfony\Component\HttpFoundation\Request;

class User extends Controller {

    public function signIn( $params ) {

        $service_response = $this->_getServiceResponse();
        $user             = $this->users->read( [ 'email' => $params[ 'email' ] ] );

        if ( empty( $user['id'] ) ) {
            return $service_response->setError( 'flash', MessageConstant::INVALID_LOGIN_CREDENTIALS );
        }

        $valid_password = $this->phpass->checkPassword(
            $params['password'],
            $user['password']
        );

        if( !$valid_password ) {
            return $service_response->setError( 'flash', MessageConstant::INVALID_LOGIN_CREDENTIALS );
        }

        unset( $user[ 'password' ] );
        $this->_setUser( $user );
        $this->session->set( SessionConstant::AUTH_TOKEN, $this->security->generateToken( $params[ 'email' ] ) );

        return $service_response;

    }

    public function signOut() {

        $this->security->deAuthenticate();
        return $this->_getServiceResponse();

    }

    public function closeAccount( array $params ) {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $user             = $this->users->read( [ 'id' => $user_id ] );

        if ( !$user ) {
            return $service_response->setError( 'flash', 'Error processing your request. Please try again' );
        }

        $valid_password = $this->phpass->checkPassword(
            $params['current_password'],
            $user['password']
        );

        if( !$valid_password ) {
            return $service_response->setError( 'current_password', 'Invalid password' );
        }

        $customer_id = $this->_getUser( 'customer_id' );

        if ( $customer_id ) {

            $cards = $this->cards->getAll( $user_id );

            foreach ( $cards as $card ) {
                $this->payment->deleteCard( $customer_id, $card['source_id'] );
            }

        }

        //TODO: clear all cookies and destroy session

        $is_deleted = $this->users->delete( [ 'id' => $user_id ] );

        if ( !$is_deleted ) {
            return $service_response->setError( 'flash', 'Error deleting account. Please try again'  );
        }

        return $service_response;

    }

    public function resetPassword( array $params ) {

        $response = $this->_getServiceResponse();

        if ( $params['password'] !== $params['cpassword'] ) {
            return $response->setError( 'password', "Your passwords don't match" );
        }

        $user = $this->users->read( [ 'token' => $params['token'] ] );

        if ( !$user ) {
            return $response->setError( 'flash', 'Invalid request' );
        }

        $token_parts = explode( '.', $params['token'], 3 );

        if ( $token_parts[0] > time() ) {
            return $response->setError( 'flash', "Your token has expired. Please request a new token" );
        }

        $new_password = $this->phpass->hashPassword( $params['password'] );

        $this->users->update( [ 'id' => $user['id'] ], [
            'token'     => '',
            'password'  => $new_password
        ] );

        return $response;

    }

    public function setPasswordResetToken( array $params ) {

        $service_response = $this->_getServiceResponse();
        $user             = $this->users->read( [ 'email' => $params['email'] ] );

        if ( !$user ) {
            return $service_response->setError( 'email', 'Invalid email' );
        }

        if ( isset( $user['token'] ) ) {

            $parts = explode( '.', $user['token'], 3 );

            if ( isset( $parts[3] ) && ( $parts[0] <= time() ) ) {
                return $service_response;
            }

        }

        $token = ( time() + ( 24 * 60 * 60 ) ) . '.' . $user['password'] . '.' . uniqid();

        $is_updated = $this->users->update( [ 'id' => $user['id'] ], [ 'token' => $token ] );

        if ( !$is_updated  ) {
            return $service_response->setError( 'flash', 'An error occurred, please try again' );
        }

        return $service_response;

    }

    public function register( array $params ) {

        $service_response   = $this->_getServiceResponse();
        $user               = $this->users->read( [ 'email' => $params['email'] ] );

        if ( !empty( $user['id'] ) ) {
            return $service_response->setError( 'email', 'This email already exists' );
        }

        $params['password'] = $this->phpass->hashPassword( $params['password'] );
        $is_created         = $this->users->save( $params );

        if ( !$is_created ) {
            return $service_response->setError( 'flash', 'Error creating account. Please try again'  );
        }

        return $service_response;

    }

    public function getSettings() {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $user             = $this->users->read( [ 'id' => $user_id ] );

        return $service_response->setData( $user );

    }

    public function updateSettings( array $params ) {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $user             = $this->users->read( [ 'id' => $user_id ] );

        if ( !$user ) {
            return $service_response->setError( 'flash', 'Error processing your request. Please try again' );
        }

        $update         = [];
        $valid_password = $this->phpass->checkPassword(
            $params['current_password'],
            $user['password']
        );

        if( !$valid_password ) {
            return $service_response->setError( 'current_password', 'Invalid password' );
        }

        foreach ( $params as $field => $value ) {

            if ( isset( $user[ $field ] ) && ( $user[ $field ] !== $value ) ) {
                $update[ $field ] = $value;
            }

        }

        if ( !empty( $params['password'] ) && ( $params['password'] !== $params['current_password'] ) ) {
            $update[ 'password' ] = $this->phpass->hashPassword( $params['password'] );
        }

        if ( empty( $update ) ) {
            return $service_response->setError( 'flash', 'Please enter at least one field to update' );
        }

        $is_updated = $this->users->update( [ 'id' => $user_id ], $update );

        if ( !$is_updated ) {
            return $service_response->setError( 'flash', 'Error processing your request. Please try again' );
        }

        unset( $update[ 'password' ] );
        $user = array_merge( $user, $update );
        $this->_setUser( $user );

        return $service_response;

    }

}