<?php

namespace app\Library;

use app\Constants\RouteConstant;

class WebController extends Controller {

    protected function _continue( $continue ) {

        try {

            if ( !empty( $continue ) ) {

                $urls    = (array)explode( ',', $continue );
                $url_key = array_pop( $urls );
                $chain   = count( $urls );

                if ( $chain ) {

                    if ( $chain > 3 ) {
                        $urls = array_slice( $urls, 0, 3 );
                    }

                    if ( !empty( $url_key ) && ( ctype_alpha( $url_key ) ) ) {
                        return $this->redirect( $this->generateUrl( $url_key, ['continue' => implode( ',', $urls ) ] ) );
                    }

                }

                return $this->redirect( $this->generateUrl( $url_key ) );

            }

        } catch ( \Exception $e ) {}

        return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );

    }

}