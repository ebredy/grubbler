<?php

namespace app\Library;

use app\Constants\SessionConstant;
use TinyPress\Abstracts\ControllerAbstract;

class Controller extends ControllerAbstract {

    protected function _getServiceResponse() {

        return new Response();

    }

    protected function _now() {

        return date('Y-m-d H:i:s');

    }

    protected function _getUser( $attribute = null ) {

        if ( !empty( $attribute ) ) {
            return  $this->session->get( SessionConstant::CURRENT_USER . "/$attribute" );
        }

        return  $this->session->get( SessionConstant::CURRENT_USER );

    }

    protected function _setUser( array $user ) {

        $this->session->set( SessionConstant::CURRENT_USER, $user );

    }

    protected function _setUserAttr( $attribute, $value ) {

        $this->session->set( SessionConstant::CURRENT_USER. "/$attribute", $value );

    }

}