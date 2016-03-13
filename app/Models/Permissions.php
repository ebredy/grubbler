<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Permissions extends ModelAbstract {

    const TABLE_NAME = 'user_permissions';

    public function getTableName() {

        return self::TABLE_NAME;

    }
    
    public function lookup( $page = null ){
        
        $SQL="select * 
                     from permissions";
       if($page){ 
            $SQL.=" where page = '".$page."'" ; 
       }
        return $this->execute($SQL);

        
    }
    public function exists( $page ){
        
        return $this->lookup( $page ) ? true: false;
        
    }
    public function add( $page, $permission ){
        
        if( $this-exists( $page ) ){
           
            $SQL="insert into permissions(page, permission) values('".$page."','".$permission."')";

           $this->execute($SQL);
           
           return true;
        }
        return false;
    }
}