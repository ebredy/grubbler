<?php

namespace app\Services;

use app\Interfaces\PaymentInterface;

class Payment {

    private $_adapter;

    public function __construct( PaymentInterface $adapter ) {

        $this->_adapter = $adapter;

    }

    public function __call( $method, $args ) {

        return ( $args )
            ? call_user_func_array( [ $this->_adapter, $method ], $args )
            : call_user_func( [ $this->_adapter, $method ] );

    }

}
