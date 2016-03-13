<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Addresses extends ModelAbstract {

    const TABLE_NAME = 'addresses';

    public function getTableName() {

        return self::TABLE_NAME;

    }

    public function getById( $address_id ) {

        $query = 'SELECT addresses.*, cities.name as city, states.name as state FROM addresses ' .
            'inner join cities on addresses.city_id = cities.id ' .
            'inner join states on addresses.state_id = states.id ' .
            'where addresses.id = ' . $address_id . ' ' .
            'limit 1';

        $address = $this->execute( $query );

        return ( !empty( $address[0] ) )
            ? $address[0]
            : [];

    }

    public function getRecent( $user_id, $limit = 1 ) {

        $query = 'SELECT addresses.*, cities.name as city, states.name as state FROM addresses ' .
            'inner join cities on addresses.city_id = cities.id ' .
            'inner join states on addresses.state_id = states.id ' .
            'where addresses.user_id = ' . $user_id . ' ' .
            'order by addresses.last_used DESC ' .
            'limit ' . $limit;

        return $this->execute( $query );

    }

    public function getCurrent( $user_id ) {

        $addresses = $this->getRecent( $user_id );

        return ( !empty( $addresses[0] ) )
            ? $addresses[0]
            : [];

    }

}