<?php

namespace TinyPress\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class TinyPressException {

    public static function exceptionHandler( FlattenException $exception ) {

        return self::_handle(
            $exception->getMessage(),
            $exception->getStatusCode()
        );

    }

    public static function errorHandler( $errno, $errstr, $errfile, $errline ) {

        return self::_handle( $errstr, $errno );

    }

    private static function _handle( $msg, $code ) {
       
        echo 'code: '.$code.' msg: '.$msg.'<br>';
        echo '<pre>';
        print_r(debug_backtrace());
        echo '</pre>';
        return new Response( "Something went wrong! ($msg)", $code );

    }

} // TinyPressException