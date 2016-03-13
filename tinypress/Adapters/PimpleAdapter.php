<?php

namespace TinyPress\Adapters;

use TinyPress\Interfaces\ContainerInterface;
use Pimple\Container as Pimple;

class PimpleAdapter implements ContainerInterface {

    private $_container;

    public function __construct( array $core_configs = [] ) {

        $configs = require_once CONFIG_PATH . 'services.php';

        if ( is_array( $configs ) ) {
            $this->_container = new Pimple( array_merge( $configs, $core_configs ) );
        }

    }

    public function has( $service_name ) {

        return isset( $this->_container[ $service_name ] );

    }

    public function get( $service_name ) {

        return $this->_container[ $service_name ];

    }

}
