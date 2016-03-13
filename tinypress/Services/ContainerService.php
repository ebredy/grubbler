<?php

namespace TinyPress\Services;

class ContainerService {

    private static $_container;

    public static function set( $id, $container ) {

        self::$_container[ $id ] = $container;

    }

    public static function get( $id ) {

        return self::$_container[ $id ];

    }

    public static function has( $id ) {

        return isset( self::$_container[ $id ] );

    }

}