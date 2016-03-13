<?php

namespace TinyPress\Interfaces;

use TinyPress\Interfaces\ContainerInterface;

Interface ControllerInterface {

    public function render( $template, $vars = null );

}
