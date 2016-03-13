<?php

namespace app\Controllers;

use app\Constants\HttpConstant;
use app\Constants\TemplateConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Index extends WebController {

    function index( Request $request ) {

        $http_method = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_GET:

                return $this->_getMenuItems();

                break;

            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    private function _getMenuItems() {

        $this->_layout = TemplateConstant::LAYOUT_INDEX;
        
        
        $response = $this->address->getGeoLocation();
        $restaurant_id = 1;
        $geolocation = [];
        if($response->isOk()){
            
            $geolocation = $response->getData();
            $restaurant = $this->restaurant->search( $geolocation );
            //$this->flash( $response->getError('flash',"value of geo location: ") );
        }
        else
        {
            //for demo purpose leave this but upon going live
            //comment out $retaurant_id to restaurants read
            //
            // $this->flash( $response->getError('flash') );
           
            
            $restaurant    = $this->restaurants->read( [
                'id' => $restaurant_id
            ] );
        }
        if ( !$restaurant ) {
            return $this->render( TemplateConstant::PAGE_HOME );
        }

        $start = 0;
        $limit = 10;
        $args  = [
            'order' => 'id asc',
            'limit' => "$start,$limit",
            'conditions' => [
                'restaurant_id' => $restaurant['id']?$restaurant['id']:$restaurant_id
            ]
        ];

        $menu = $this->menus->find( $args );
        
        $cuisines = $this->menus->getCuisines( $menu );
        
        return $this->render( TemplateConstant::PAGE_HOME, [
            'items'       => $menu,
            'cuisines_dropdown' => $cuisines ,
            'geolocation' => $geolocation,
            'restaurant'  => $restaurant['restaurant'],
            'address'     => $restaurant['full_address'],
            'rating'      => $restaurant['rating'],
            'pricing'     => $restaurant['price'],
        ] );

    }

}