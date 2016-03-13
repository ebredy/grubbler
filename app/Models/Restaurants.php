<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Restaurants extends ModelAbstract {

    const TABLE_NAME = 'restaurants';

    public function getTableName() {

        return self::TABLE_NAME;

    }

    public function getNearest( $latitude, $longitude, $distance = 5 , $offset = 0, $limit = 10 ) {

        $query = "SELECT restaurants.*, ( 3959 * acos( cos( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($longitude) ) + sin( radians( $latitude ) ) * sin( radians( latitude ) ) ) ) AS distance FROM restaurants HAVING distance < $distance ORDER BY distance LIMIT $offset, $limit;";

        return $this->execute( $query );

    }

}