<?php

namespace app\Services;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use TinyPress\Services\ContainerService;

class Map {

    private $_api  = 'https://maps.googleapis.com/maps/api/geocode/json';
    private $_geo_ip_api ='http://www.geoplugin.net/php.gp';
    private $_container;

    public function __construct() {

        $this->_container['symfony'] = ContainerService::get( 'symfony' );
        $this->_container['core']    = ContainerService::get( 'core' );

    }

    public function getCoordinates( $address ) {

        $addresses = $this->_map( $address );

        if ( $addresses ) {

            if ( isset( $addresses[0]['geometry']['location'] ) ) {
                return [
                    'latitude'  => $addresses[0]['geometry']['location']['lat'],
                    'longitude' => $addresses[0]['geometry']['location']['lng']
                ];
            }

        }

        return [];

    }

    public function getAddresses( $address ) {

        $addresses = $this->_map( $address );

        if ( empty( $addresses ) ) {
            return [];
        }

        $found = [];

        foreach( $addresses as $address ) {

            $data = [];

            if ( empty( $address['formatted_address'] ) ) {
                continue;
            }

            $data['full_address'] = $address['formatted_address'];

            if ( !empty( $address['geometry']['location'] ) ) {
                $data['longitude'] = $address['geometry']['location']['lng'];
                $data['latitude']  = $address['geometry']['location']['lat'];
            }

            if ( empty( $address['address_components'] ) ) {
                continue;
            }

            foreach( $address['address_components'] as $part ) {

                if ( empty( $part['types'] ) ) {
                    continue;
                }

                if ( in_array( 'postal_code', $part['types'] ) ) {
                    $data['zip_code'] = $part['long_name'];
                    continue;
                }

                if ( in_array( 'postal_code_suffix', $part['types'] ) ) {
                    $data['zip_code_suffix'] = $part['long_name'];
                    continue;
                }

                if ( in_array( 'administrative_area_level_1', $part['types'] ) ) {
                    $data['state'] = $part['short_name'];
                    continue;
                }

                if ( in_array( 'country', $part['types'] ) ) {
                    $data['country'] = $part['long_name'];
                    continue;
                }

                //TODO: validate accuracy!
                if ( in_array( 'locality', $part['types'] ) ) {
                    $data['city'] = $part['long_name'];
                    continue;
                }

                if ( in_array( 'street_number', $part['types'] ) ) {
                    $data['street_number'] = $part['long_name'];
                    continue;
                }

                if ( in_array( 'route', $part['types'] ) ) {
                    $data['street'] = $part['long_name'];
                    continue;
                }

                if ( in_array( 'administrative_area_level_2', $part['types'] ) ) {
                    $data['county'] = $part['long_name'];
                    continue;
                }

            }

            $found[] = $data;

        }

        return $found;

    }
    
    public function getGeoLocation( $ip = null ) {

        $ip = $ip? $ip: $_SERVER['REMOTE_ADDR'];
 
        $geo = [];
        
        $geoLocation = unserialize( $this->_container['core']->get('httpcurl')->get( $this->_geo_ip_api.'?ip='.$ip ) );
        
        if(isset($geoLocation['geoplugin_status']) && $geoLocation['geoplugin_status'] == 200 ){
            
            
            
            foreach($geoLocation as $geoKey => $geoValue){
                
                $geo[str_replace("geoplugin_","",$geoKey)] = $geoValue;
            }
            
        }
        
        return $geo;

    }

    private function _map( $address ) {

        $key = $this->_container['symfony']->getParameter( 'googleapi' );

        if ( empty( $key['key'] ) ) {
            throw new ParameterNotFoundException( 'googleapi' );
        }

        $url     = $this->_api . '?key=' . $key['key'] . '&address=' .  urlencode( trim( $address ) );
        $address = $this->_container['core']->get('httpcurl')->get( $url );

        if ( $address ) {

            $address = json_decode( $address, true );

            if ( ( strtoupper( $address['status'] ) === 'OK' ) && !empty( $address['results'] ) ) {
                return $address['results'];
            }

        }

        return [];

    }

}