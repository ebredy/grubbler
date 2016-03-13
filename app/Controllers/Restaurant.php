<?php

namespace app\Controllers;

use app\Constants\HttpConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Restaurant extends WebController {

    public function index( Request $request, $restaurant_id = null ) {

        $this->_layout  = TemplateConstant::LAYOUT_RESTAURANTS;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_GET:

                return ( $restaurant_id )
                    ? $this->_getRestaurant( $request, $restaurant_id )
                    : $this->_getRestaurants( $request );

                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    private function _getRestaurants( Request $request ) {

        $params = $this->validate->query( $request, [
            'address'           => 'isAddress',
            'street_number'     => 'isInt',
            'street_address'    => 'isAddress',
            'city'              => 'isAddress',
            'state'             => 'isAddress',
            'postal_code'       => 'isInt',
            'latitude'          => 'isAddress',
            'longitude'         => 'isAddress',
            'rating'            => 'isInt',
            'cuisine'           => 'isInt',
            'delivery'          => 'isInt',
            'email'             => 'isEmail',
            'page'              => 'isInt',
            'order'             => 'isAlpha',
            'q'                 => 'isAlpha'
        ] );

        if ( !empty( $params['errors'] ) ) {
            $this->flash( 'Please enter an address' );
            
            return $this->render( TemplateConstant::PAGE_RESTAURANTS );
        }

        $response = $this->restaurant->search( $params );

        if ( !$response->isOk() ) {

            if ( $request->isXmlHttpRequest() ) {
                return '';
            }

            $this->flash( $response->getError( 'flash', 'An error occurred. Please try again' ) );
            return $this->render( TemplateConstant::PAGE_RESTAURANTS );

        }

        $results = $response->getData();
        $results = array_merge($results,$params);
        $results['cuisine'] =  count(explode(",",$params['cuisine'])) > 1? explode(",",$params['cuisine']): [ $params['cuisine'] ];
        
        if(empty($result['cuisine_dropdown'])){
            
            $results['cuisines_dropdown'] =  $cuisines = $this->menus->getCuisines();
            
        }
        if ( isset( $results['restaurants'] ) && ( count( $results['restaurants'] ) >= \app\Library\Restaurant::RESULTS_LIMIT ) ) {

            $params['page'] = ( !empty( $params['page'] ) )
                ? $params['page'] + 1
                : 2;

            $results['next_page'] = $this->generateUrl( RouteConstant::RESTAURANTS, $params, UrlGeneratorInterface::RELATIVE_PATH  );

        }

        if(empty($results['address'])){
         
                $results['address'] = $params['address']; 
        }

        if ( $request->isXmlHttpRequest() ) {
            return $this->response->setContent( $this->view->render( TemplateConstant::ELEMENT_RESTAURANTS, $results ) );
        }

      
        return $this->render( TemplateConstant::PAGE_RESTAURANTS, $results );

    }

    private function _getRestaurant( Request $request, $restaurant_id ) {

        $response = $this->restaurant->getInfo( $restaurant_id );

        if ( !$response->isOk() ) {
            $this->flash( $response->getError( 'flash', 'An error occurred. Please try again' ) );
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
        }

        return $this->render( TemplateConstant::PAGE_MENU, $response->getData() );

    }

}