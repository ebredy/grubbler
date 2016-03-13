<?php

namespace app\Helpers;

use app\Constants\SessionConstant;
use TinyPress\Services\ContainerService;

class ViewHelper {

    private $_containers;

    public function __construct() {

        $this->_containers['core'] = ContainerService::get( 'core' );
        $this->_containers['symfony'] = ContainerService::get( 'symfony' );

    }

    public function __call( $method, $args ) {

        return ( $args )
            ? call_user_func_array( [ $this->_containers['symfony'], $method ], $args )
            : call_user_func( [ $this->_containers['symfony'], $method ] );

    }

    function isLoggedIn() {

        return $this->_containers['core']->get('security')->isAuthenticated();

    }

    function formatDate( $created_on ) {

        return date( "F j, Y, g:i a", strtotime( $created_on ) );

    }

    function formatAddress( array $data ) {

        $address = '';

        if ( !empty( $data['address_1'] ) ) {
            $address .= $data['address_1'];
        }

        if ( !empty( $data['address_2'] ) ) {
            $address .= ', ' . $data['address_2'];
        }

        if ( !empty( $data['apt_number'] ) ) {
            $address .= ' ' . $data['apt_number'];
        }

        if ( !empty( $data['city'] ) ) {

            $address .= ', ' . $data['city'];

            if ( !empty( $data['state'] ) ) {
                $address .= ' ' . $data['state'];
            }

        }

        return $address;

    }


    function flash() {

        $session    = $this->_containers['core']->get('session');
        $flash_bag  = $session->getFlashBag();

        if ( $flash_bag->has( SessionConstant::FLASH ) ) {
            return '<div id="flash" class="alert alert-warning" role="alert">' . $flash_bag->get( SessionConstant::FLASH )[0] . '</div>';
        }

        return '';

    }

    function user( $attribute ) {

        $session = $this->_containers['core']->get('session');

        if ( $attribute === 'name' ) {
            $fname = $session->get( SessionConstant::CURRENT_USER . '/fname' );
            $lname = $session->get( SessionConstant::CURRENT_USER . '/lname' );
            return ucfirst( $fname ) . ' ' . strtoupper( $lname[0] ) . '.';
        }

        return '';

    }

}