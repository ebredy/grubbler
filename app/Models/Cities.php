<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Cities extends ModelAbstract {

    const TABLE_NAME = 'cities';

    public function getTableName() {

        return self::TABLE_NAME;

    }

}