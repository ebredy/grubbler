<?php

namespace app\Library;

use app\Constants\RouteConstant;

class Address extends Controller {

    public $replacee ="";
    public $replacer="";
    public  $streetSuffixes = array(
        "allee" =>"alley",
        "ally" =>"alley",
        "aly" =>"alley",
        "anex" =>"annex",
        "anx" =>"annex",
        "arc" =>"arcade",
        "av" =>"avenue",
        "ave" =>"avenue",
        "aven" =>"avenue",
        "avenu" =>"avenue",
        "avn" =>"avenue",
        "avnue" =>"avenue",
        "bayou" =>"bayoo",
        "bch" =>"beach",
        "blf" =>"bluff",
        "bluf" =>"bluff",
        "blvd" =>"boulevard",
        "bnd" =>"bend",
        "bot" =>"bottom",
        "bottm" =>"bottom",
        "boul" =>"boulevard",
        "boulv" =>"boulevard",
        "br" =>"branch",
        "brdge" =>"bridge",
        "brg" =>"bridge",
        "brk" =>"brook",
        "brnch" =>"branch",
        "btm" =>"bottom",
        "byp" =>"bypass",
        "bypa" =>"bypass",
        "bypas" =>"bypass" ,
        "byps" =>"bypass",
        "canyn" =>"canyon",
        "causway" =>"causeway",
        "cen" =>"center",
        "cent" =>"center",
        "centr" =>"center",
        "centre" =>"center",
        "cir" =>"circle",
        "circ" =>"circle",
        "circl" =>"circle",
        "ck" =>"creek",
        "clb" =>"club",
        "clf" =>"cliff",
        "clfs" =>"cliffs",
        "cmp" =>"camp",
        "cnter" =>"center",
        "cntr" =>"center",
        "cnyn" =>"canyon",
        "cor" =>"corner",
        "cors" =>"corners",
        "cp" =>"camp",
        "cpe" =>"cape",
        "cr" =>"creek",
        "crcl" =>"circle",
        "crcle" =>"circle",
        "crecent" =>"crescent",
        "cres" =>"crescent",
        "cresent" =>"crescent",
        "crk" =>"creek",
        "crscnt" =>"crescent",
        "crse" =>"course",
        "crsent" =>"crescent",
        "crsnt" =>"crescent",
        "crssing" =>"crossing",
        "crssng" =>"crossing",
        "crt" =>"court",
        "cswy" =>"causeway",
        "ct" =>"court",
        "ct" =>"courts",
        "ctr" =>"center",
        "cv" =>"cove",
        "cyn" =>"canyon",
        "div" =>"divide",
        "dl" =>"dale",
        "dm" =>"dam",
        "dr" =>"drive",
        "driv" =>"drive",
        "drv" =>"drive",
        "dv" =>"divide",
        "dvd" =>"divide",
        "est" =>"estate",
        "ests" =>"estates",
        "exp" =>"expressway",
        "expr" =>"expressway",
        "express" =>"expressway",
        "expw" =>"expressway",
        "expy" =>"expressway",
        "ext" =>"extension",
        "extn" =>"extension",
        "extnsn" =>"extension",
        "exts" =>"extensions",
        "fld" =>"field",
        "flds" =>"fields",
        "fls" =>"falls",
        "flt" =>"flat",
        "flts" =>"flats",
        "forests" =>"forest",
        "forg" =>"forge",
        "frd" =>"ford",
        "freewy" =>"freeway",
        "frg" =>"forge",
        "frk" =>"fork",
        "frks" =>"forks",
        "frry" =>"ferry",
        "frst" =>"forest",
        "frt" =>"fort",
        "frway" =>"freeway",
        "frwy" =>"freeway",
        "fry" =>"ferry",
        "ft" =>"fort",
        "fwy" =>"freeway",
        "gardn" =>"garden",
        "gatewy" =>"gateway",
        "gatway" =>"gateway",
        "gdn" =>"garden",
        "gdns" =>"gardens",
        "gln" =>"glen",
        "grden" =>"garden",
        "grdn" =>"garden",
        "grdns" =>"gardens",
        "grn" =>"green",
        "grov" =>"grove",
        "grv" =>"grove",
        "gtway" =>"gateway",
        "gtwy" =>"gateway",
        "harb" =>"harbor",
        "harbr" =>"harbor",
        "havn" =>"haven",
        "hbr" =>"harbor",
        "height" =>"heights",
        "hgts" =>"heights",
        "highwy" =>"highway",
        "hiway" =>"highway",
        "hiwy" =>"highway",
        "hl" =>"hill",
        "hllw" =>"hollow",
        "hls" =>"hills",
        "hollows" =>"hollow",
        "holw" =>"hollow",
        "holws" =>"hollow",
        "hrbor" =>"harbor",
        "ht" =>"heights",
        "hts" =>"heights",
        "hvn" =>"haven",
        "hway" =>"highway",
        "hwy" =>"highway",
        "inlt" =>"inlet",
        "is" =>"island",
        "isles" =>"isle",
        "islnd" =>"island",
        "islnds" =>"islands",
        "iss" =>"islands",
        "jct" =>"junction",
        "jction" =>"junction",
        "jctn" =>"junction",
        "jctns" =>"junctions",
        "jcts" =>"junctions",
        "junctn" =>"junction",
        "juncton" =>"junction",
        "knl" =>"knoll",
        "knls" =>"knolls",
        "knol" =>"knoll",
        "ky" =>"key",
        "kys" =>"keys",
        "la" =>"lane",
        "lanes" =>"lane",
        "lck" =>"lock",
        "lcks" =>"locks",
        "ldg" =>"lodge",
        "ldge" =>"lodge",
        "lf" =>"loaf",
        "lgt" =>"light",
        "lk" =>"lake",
        "lks" =>"lakes",
        "ln" =>"lane",
        "lndg" =>"landing",
        "lndng" =>"landing",
        "lodg" =>"lodge",
        "loops" =>"loop",
        "mdw" =>"meadow",
        "mdws" =>"meadows",
        "medows" =>"meadows",
        "missn" =>"mission",
        "ml" =>"mill",
        "mls" =>"mills",
        "mnr" =>"manor",
        "mnrs" =>"manors",
        "mnt" =>"mount",
        "mntain" =>"mountain",
        "mntn" =>"mountain",
        "mntns" =>"mountains",
        "mountin" =>"mountain",
        "msn" =>"mission",
        "mssn" =>"mission",
        "mt" =>"mount",
        "mtin" =>"mountain",
        "mtn" =>"mountain",
        "nck" =>"neck",
        "orch" =>"orchard",
        "orchrd" =>"orchard",
        "ovl" =>"oval",
        "parkwy" =>"parkway",
        "paths" =>"path",
        "pikes" =>"pike",
        "pk" =>"park",
        "pkway" =>"parkway",
        "pkwy" =>"parkway",
        "pkwys" =>"parkways",
        "pky" =>"parkway",
        "pl" =>"place",
        "plaines" =>"plains",
        "pln" =>"plain",
        "plns" =>"plains",
        "plz" =>"plaza",
        "plza" =>"plaza",
        "pnes" =>"pines",
        "pr" =>"prairie",
        "prarie" =>"prairie",
        "prk" =>"park",
        "prr" =>"prairie",
        "prt" =>"port",
        "prts" =>"ports",
        "pt" =>"point",
        "pts" =>"points",
        "rad" =>"radial",
        "radiel" =>"radial",
        "radl" =>"radial",
        "ranches" =>"ranch",
        "rd" =>"road",
        "rdg" =>"ridge",
        "rdge" =>"ridge",
        "rdgs" =>"ridges",
        "rds" =>"roads",
        "riv" =>"river",
        "rivr" =>"river",
        "rnch" =>"ranch",
        "rnchs" =>"ranch",
        "rpd" =>"rapid",
        "rpds" =>"rapids",
        "rst" =>"rest",
        "rvr" =>"river",
        "shl" =>"shoal",
        "shls" =>"shoals",
        "shoar" =>"shore",
        "shoars" =>"shores",
        "shr" =>"shore",
        "shrs" =>"shores",
        "smt" =>"summit",
        "spg" =>"spring",
        "spgs" =>"springs",
        "spng" =>"spring",
        "spngs" =>"springs",
        "sprng" =>"spring",
        "sprngs" =>"springs",
        "sq" =>"square",
        "sqr" =>"square",
        "sqre" =>"square",
        "sqrs" =>"squares",
        "squ" =>"square",
        "st" =>"street",
        "sta" =>"station",
        "statn" =>"station",
        "stn" =>"station",
        "str" =>"street",
        "stra" =>"stravenue",
        "strav" =>"stravenue",
        "strave" =>"stravenue",
        "straven" =>"stravenue",
        "stravn" =>"stravenue",
        "streme" =>"stream",
        "strm" =>"stream",
        "strt" =>"street",
        "strvn" =>"stravenue",
        "strvnue" =>"stravenue",
        "sumit" =>"summit",
        "sumitt" =>"summit",
        "ter" =>"terrace",
        "terr" =>"terrace",
        "tpk" =>"turnpike",
        "tpke" =>"turnpike",
        "tr" =>"trail",
        "traces" =>"trace",
        "tracks" =>"track",
        "trails" =>"trail",
        "trak" =>"track",
        "trce" =>"trace",
        "trfy" =>"trafficway",
        "trk" =>"track",
        "trks" =>"track",
        "trl" =>"trail",
        "trls" =>"trail",
        "trnpk" =>"turnpike",
        "trpk" =>"turnpike",
        "tunel" =>"tunnel",
        "tunl" =>"tunnel",
        "tunls" =>"tunnel",
        "tunnels" =>"tunnel",
        "tunnl" =>"tunnel",
        "turnpk" =>"turnpike",
        "un" =>"union",
        "vally" =>"valley",
        "vdct" =>"viaduct",
        "via" =>"viaduct",
        "viadct" =>"viaduct",
        "vill" =>"village",
        "villag" =>"village",
        "villg" =>"village",
        "villiage" =>"village",
        "vis" =>"vista",
        "vist" =>"vista",
        "vl" =>"ville",
        "vlg" =>"village",
        "vlgs" =>"villages",
        "vlly" =>"valley",
        "vly" =>"valley",
        "vlys" =>"valleys",
        "vst" =>"vista",
        "vsta" =>"vista",
        "vw" =>"view",
        "vws" =>"views",
        "wls" =>"wells",
        "wy" =>"way",
        "xing" =>"crossing"
    );

    public function getRecent( array $query = [] ) {

        $service_response   = $this->_getServiceResponse();
        $data               = [];
        $data['user_id']    = $this->_getUser( 'id' );
        $data['addresses']  = $this->addresses->getRecent( $data['user_id'], 12 );
        $data['action']     = ( !empty( $query['continue'] ) )
            ? $this->generateUrl( RouteConstant::ADDRESSES ) . '?continue='. $query['continue']
            : $this->generateUrl( RouteConstant::ADDRESSES );

        $service_response->setData( $data );

        return $service_response;

    }

    public function remove( $address_id ) {

        $service_response   = $this->_getServiceResponse();
        $address            = $this->addresses->read( [
            'id'        => $address_id,
            'user_id'   => $this->_getUser( 'id' )
        ] );

        if ( $address ) {

            $is_deleted = $this->addresses->delete( [ 'id' => $address_id ] );

            if ( !$is_deleted ) {
                $service_response->setError( 'flash', 'An error occurred. Please try again' );
            }

        } else {
            $service_response->setError( 'flash', 'Address not found' );
        }

        return $service_response;

    }

    public function getById( $address_id, array $query = [] ) {

        $service_response = $this->_getServiceResponse();
        $address          = $this->addresses->getById( $address_id );

        if ( !$address ) {
            return $service_response->setError( 'flash', 'The address you tried to edit is invalid or does no longer exist.' );
        }

        if ( !empty( $query['continue'] ) ) {
            $address['continue'] = $query['continue'];
        }

        return $service_response->setData( $address );

    }

    public function update( $address_id, array $params ) {

        $service_response = $this->_getServiceResponse();
        $address          = $this->addresses->getById( $address_id );

        if ( !$address ) {
            return $service_response->setError( 'flash', 'The address you tried to edit is invalid or does no longer exist.' );
        }

        foreach ( $address as $field => $value ) {

            if ( isset( $params[ $field ] ) && ( $params[ $field ] !== $value ) ) {
                $update[ $field ] = $params[ $field ];
            }

        }

        if ( empty( $update ) ) {
            return $service_response;
        }

        //TODO: Must have all states in database
        if ( !empty( $update['state'] ) ) {

            $state = $this->states->read( [ 'name' => $update['state'] ] );
            unset( $update['state'] );

            if ( $state ) {
                $update['state_id'] = $state['id'];
            }

        }

        //TODO: Validate city using google maps, in case of spelling errors!
        if ( !empty( $update['city'] ) ) {

            $city = $this->cities->read( [ 'name' => $update['city'] ] );

            if ( $city ) {
                $update['city_id'] = $city['id'];
            } else {
                $this->cities->save( [ 'name' => $update['city'] ] );
                $update['city_id'] = $this->cities->lastInsertId();
            }

            unset( $update['city'] );

        }

        $is_updated = ( !empty( $update ) )
            ? $this->addresses->update( [ 'id' => $address['id'] ], $update )
            : false;

        if ( !$is_updated ) {
            return $service_response->setError( 'flash', 'An error occurred while updating your address! Please try again.' );
        }

        return $service_response;

    }

    public function setCurrent( $address_id ) {

        $service_response = $this->_getServiceResponse();
        $user_id          = $this->_getUser( 'id' );
        $address          = $this->addresses->read( [
            'id'        => $address_id,
            'user_id'   => $user_id
        ] );

        if ( !$address ) {
            return $service_response->setError( 'flash', 'Invalid delivery address. Please try again' );
        }

        $update = [ 'last_used' => $this->_now() ];
        $is_set = $this->addresses->update( [ 'id' => $address_id ], $update );

        if ( !$is_set ) {
            return $service_response->setError( 'flash', 'An error occurred. Please try again' );
        }

        return $service_response;

    }

    public function create( array $params ) {

        $service_response   = $this->_getServiceResponse();
        $address_2          = !empty( $params['address_2'] ) ? $params['address_2'] : '';
        $address            = $params['address_1'] . ' ' . $address_2 . ' ' . ucfirst( $params['city'] ) . ' ' . $params['state']  . ' ' . $params['zip_code'];
        $suggestions        = $this->_verifyAddress( $address );

        if ( $suggestions ) {

            return $service_response->setError( 'suggestions', $suggestions );
        }

        $params['user_id'] = $this->_getUser( 'id' );
        $is_saved          = $this->_saveAddress( $params );

        if ( !$is_saved ) {
            return $service_response->setError( 'flash', 'An error occurred. Please try again' );
        }

        return $service_response;

    }

    public function getGeoLocation(){
        
        $service_response = $this->_getServiceResponse();
        $geolocation = $this->map->getGeoLocation();
        
        if( isset($geolocation['status']) && $geolocation['status'] != 200 ){
            
            return $service_response->setError( 'flash', 'Unable to get location please set your address' );
        }
        
        return $service_response->setData($geolocation);
    }




    //------

    private function _saveAddress( $params ) {

        $address = [];
        $state   = $this->states->read([
            'name' => $params['state']
        ]);

        if ( !$state ) {
            $this->states->save( [
                'name' => $params['state']
            ] );
            $address['state_id'] = $this->states->lastInsertId();
        } else {
            $address['state_id'] = $state['id'];
        }

        $city   = $this->cities->read([
            'name' => $params['city']
        ]);

        if ( !$city ) {
            $this->cities->save( [
                'name' => $params['city']
            ] );
            $address['city_id'] = $this->cities->lastInsertId();
        } else {
            $address['city_id'] = $city['id'];
        }

        $fields = [ 'address_1', 'address_2', 'phone', 'zip_code', 'fname', 'lname', 'user_id', 'apt_number', 'instructions' ];

        foreach ( $params as $field => $val ) {

            if ( in_array( $field, $fields ) && !empty( $val ) ) {
                $address[ $field ] = $val;
            }

        }

        return $this->addresses->save( $address );

    }
    public function getCoordinates( $address ){
        
        $service_response = $this->_getServiceResponse();
        

        $coordinates =  $this->map->getCoordinates( $address );
        
       if( !$coordinates ){
           
          return  $service_response->setError('coordinates', 'Unable to obtain coordinates.');
       }
        
        return $service_response->setData($coordinates);
        
        
    }
    
    private function _verifyAddress( $address ) {

        $address = $this->_replaceAddressAbbreviation(  $address );

        $addresses  = $this->map->getAddresses( $address );



        foreach ( $addresses as $verified ) {

            if ( strpos( $address, $verified['street']  ) === false ) {
                continue;
            }

            if ( strpos( $address, $verified['city'] ) === false ) {
                continue;
            }

            if ( strpos( $address, $verified['state'] ) === false ) {
                continue;
            }

            if ( strpos( $address, $verified['zip_code'] ) === false ) {
                continue;
            }

            return [];

        }

        return $addresses;

    }
    
    private function _replaceAddressAbbreviation( $address ){

        $address = str_replace( '.', '', $address );

        $stateReplace = substr( $address ,-8 , 2 );


        foreach($this->streetSuffixes as $suffixAbbr => $suffixReplacer ){

            if( stripos( strtolower( $address ) , " ".$suffixAbbr." "  ) !== false ){

                $address = ucwords(str_replace( " ".strtolower( $suffixAbbr )." ", " ".strtolower( $suffixReplacer )." " , $address ));

                $address = str_replace( ucwords( $stateReplace ) ,strtoupper( $stateReplace ), $address );

                break;
            }
        }

        return $address;

    }

}