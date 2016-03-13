<?php

namespace app\Services;

use TinyPress\Services\ContainerService;

class HttpCurl {

    private $_container;

    public function __construct() {

        $this->_container = ContainerService::get( 'symfony' );

    }

    public function get( $url, array $opts = [] ) {

        static $proxies   = [];
        static $use_proxy = false;
        $curl_opts        = array_merge( [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FRESH_CONNECT  => true
        ], $opts );

        if ( is_array( $proxies ) && empty( $proxies ) ) {
            $proxies   = $this->getProxies();
            $use_proxy = ( is_array( $proxies ) && !empty( $proxies ) );
        }

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
        curl_setopt( $ch, CURLOPT_URL, $url );

        if ( $use_proxy ) {
            $index = array_rand( $proxies );
            curl_setopt( $ch, CURLOPT_PROXY, $proxies[ $index ] );
            unset( $proxies[ $index ] );
        }

        $response = curl_exec( $ch );
        curl_close( $ch );

        return $response;

    }

    public function getProxies() {

        $proxies = [];

        if ( $this->_container->hasParameter( 'proxies' ) ) {
            $proxies = $this->_container->getParameter( 'proxies' );
        }

        return ( is_array( $proxies ) && !empty( $proxies ) )
            ? (array)$proxies
            : [];

    }

}