<?php

namespace app\Controllers;

use app\Library\Controller;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;

class Menu extends Controller {

    private $_api  = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyB2O03ilyY4gnl90rEJgoFvptViWzwjpG0&address=';

    public function __construct() {

        $this->_layout = TemplateConstant::LAYOUT_MENU;

    }

    public function index( Request $request, $restaurant_id ) {

        $restaurant = $this->restaurants->read( [
            'id' => $restaurant_id
        ] );

        if ( !$restaurant ) {
            $this->flash( 'Restaurant not found!' );
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
        }

        $start = 0;
        $limit = 10;
        $args  = [
            'order' => 'id asc',
            'limit' => "$start,$limit",
            'conditions' => [
                'restaurant_id' => $restaurant_id
            ]
        ];

        $menu = $this->menus->find( $args );

        return $this->render( TemplateConstant::PAGE_MENU, [
            'menu'       => $menu,
            'restaurant' => $restaurant['restaurant'],
            'address'    => $restaurant['full_address'],
            'rating'     => $restaurant['rating'],
            'pricing'    => $restaurant['price'],
        ] );

    }

}