<?php

namespace TinyPress\Interfaces;

Interface ViewInterface {

    public function render( $template, array $vars = [] );

    public function addGlobal( $key, $value );

}
