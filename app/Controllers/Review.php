<?php

namespace app\Controllers;

use app\Library\Abstracts\ControllerAbstract as Controller;
use app\Library\Constants\Templates;
use Symfony\Component\HttpFoundation\Request;

class Review extends Controller
{

    public function index( Request $request, $order_id ) {

        if ( !$this->isAuthenticated() ) {
            //return $this->_requestLogin();
        }

        $order = $this->Orders->read( [
            'id' => $order_id
        ] );

        if ( !$order ) {
            die( 'invalid order' );
        }

        return $this->_view( Templates::REVIEW, $order );

    }

}