<?php

namespace TinyPress\Interfaces;

interface DatabaseInterface
{

    /**
     * $params = [
     *   'conditions' => [ 'Model.field' => $thisValue ], //array of conditions
     *   'fields'     => [ 'Model.field1', 'DISTINCT Model.field2' ], //array of field names
     *   'order'      => [ 'Model.created', 'Model.field3 DESC' ], //string or array defining order
     *   'group'      => [ 'Model.field' ], //fields to GROUP BY
     *   'limit'      => n, //int
     *   'offset'     => n, //int
     * ];
     */
    public function find( $table_name, array $params );

    public function read( $table_name, array $id, array $fields = [] );

    public function update( $table_name, array $data, array $id );

    public function save( $table_name, array $data );

    public function delete( $table_name, array $id );

}
