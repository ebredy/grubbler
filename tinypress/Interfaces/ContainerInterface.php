<?php

namespace TinyPress\Interfaces;

Interface ContainerInterface {

    public function has( $service_name );

    public function get( $service_name );

}
