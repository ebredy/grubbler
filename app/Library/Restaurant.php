<?php

namespace app\Library;

class Restaurant extends Controller {

    const RESULTS_LIMIT = 10;
    const SEARCH_RADIUS = 2;

    public function search( array $params ) {

        $service_response = $this->_getServiceResponse();

        if ( empty( $params['longitude'] ) || empty( $params['latitude'] )  ) {

            $address = $this->map->getCoordinates( $params['address'] );

            if ( !$address ) {
                return $service_response->setError( 'flash', 'Please enter a valid address!' );
            }

            $params = array_merge( $params, $address );

        }

        $offset = ( !empty( $params['page'] ) )
            ? $params['page'] * self::RESULTS_LIMIT
            : 0;

        $restaurants = $this->restaurants->getNearest( $params['latitude'], $params['longitude'], self::SEARCH_RADIUS, $offset, self::RESULTS_LIMIT );

        return $service_response->setData( [
            'restaurants'   => $restaurants,
            'address'       => isset($params['address'])?$params['address']:$params['city'].','.$params['region']
        ] );

    }

    public function getInfo( $restaurant_id ) {

        $service_response = $this->_getServiceResponse();
        $restaurant       = $this->restaurants->read( [
            'id' => $restaurant_id
        ] );

        if ( !$restaurant ) {
            return $service_response->setError( 'flash', 'Restaurant not found!' );
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

        return $service_response->setData( [
            'menu'       => $menu,
            'restaurant' => $restaurant['restaurant'],
            'address'    => $restaurant['full_address'],
            'rating'     => $restaurant['rating'],
            'pricing'    => $restaurant['price'],
        ] );

    }

}