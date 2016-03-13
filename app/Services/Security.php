<?php

namespace app\Services;

use app\Constants\SessionConstant;
use TinyPress\Services\ContainerService;
use TinyPress\Exceptions\ServiceNotFoundException;

class Security {

    private $_containers;

    public function __construct() {

        $this->_containers['core'] = ContainerService::get( 'core' );

        $this->_containers['symfony'] = ContainerService::get( 'symfony' );

    }

    public function generateToken( $string, $randomize = false ) {

        $random = ( $randomize ) ? uniqid() : '';
        $secret = $this->_containers['symfony']->getParameter( 'auth_secret' );

        return sha1( $secret.$string.$random );

    }

    public function deAuthenticate() {

        $session    = $this->_containers['core']->get('session');
        $persist    = $session->get( SessionConstant::PERSIST );
        $flashBag   = $session->getFlashBag()->all();

        $session->invalidate();

        $session->set( SessionConstant::PERSIST, $persist );
        $session->getFlashBag()->setAll( $flashBag );

    }

    public function isAuthenticated() {

        $session = $this->_containers['core']->get('session');
                 
        if ( $session->has( SessionConstant::AUTH_TOKEN ) ) {

            $last_request = $session->get( SessionConstant::PREVIOUS_REQUEST_TIME );
            $seconds_ago = floor( $last_request / 1000 );
            mail('ebredy@gmail.com','time left',microtime(true) - $seconds_ago);
            if ( ( microtime(true) - $seconds_ago ) <=  1800 ) {

                $auth_token = $this->generateToken( $session->get( SessionConstant::CURRENT_USER . '/email' ) );
               
                return ( $auth_token === $session->get( SessionConstant::AUTH_TOKEN ) );

            }

            $this->deAuthenticate();

        }

        return false;

    }

}