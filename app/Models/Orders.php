<?php

namespace app\Models;

use TinyPress\Abstracts\ModelAbstract;

class Orders extends ModelAbstract {

    const TABLE_NAME = 'orders';

    public function getTableName() {

        return self::TABLE_NAME;

    }

    public function getRecent( $user_id, $limit = 12 ) {

        return $this->find( [
            'conditions' => [
                'user_id' => $user_id
            ],
            'limit' => $limit,
            'order' => 'created_on DESC'
        ] );

    }

}