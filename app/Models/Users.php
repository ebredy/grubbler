<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Users extends ModelAbstract {

    const TABLE_NAME = 'users';

    public function getTableName() {

        return self::TABLE_NAME;

    }

}