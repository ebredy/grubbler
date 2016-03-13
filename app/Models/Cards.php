<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Cards extends ModelAbstract {

    const TABLE_NAME = 'cards';

    public function getTableName() {

        return self::TABLE_NAME;

    }

    public function getAll( $user_id ) {

        return $this->find( [
            'conditions' => [
                'user_id' => $user_id
            ]
        ] );

    }

    public function getRecent( $user_id ) {

        return $this->find( [
            'conditions' => [
                'user_id' => $user_id
            ],
            'order' => 'last_used DESC',
            'limit' => 7
        ] );

    }

    public function getDefault( $user_id ) {

        $cards = $this->getRecent( $user_id );

        return ( !empty( $cards[0] ) )
            ? $cards[0]
            : [];

    }

}