<?php

namespace app\Models;

use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Abstracts\ModelAbstract;

class Menus extends ModelAbstract {

    const TABLE_NAME = 'menus';

    public function getTableName() {

        return self::TABLE_NAME;

    }
    public function getCuisines( array $menu = [] ){
        
                    
                $ids = [];
                $cuisines = [];
                
                if(empty($menu)){
                    
                    foreach($menu as $itemKeys=>$itemValues ){

                       $ids[] =  $itemValues['id'];
                    }
                    
                    $sql="select c.id, 
                                 c.name 
                         from    categories c
                            inner join  menu_categories mc
                                            on c.id = mc.category_id
                        where mc.menu_id in(".join(",", $ids).")
                        order by c.name asc";
                    
                    $cuisines =  $this->execute( $sql );
                }
                
                //empty result then return all
                if(empty($cuisines)){
                  $sql="select c.id, 
                             c.name 
                     from    categories c
                     order by c.name asc";
                    return  $this->execute( $sql );
                }
                return $cuisines;
    }
}