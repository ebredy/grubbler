<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class States extends ModelAbstract {

    const TABLE_NAME = 'states';

    public function getTableName() {

        return self::TABLE_NAME;

    }

}