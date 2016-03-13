<?php

namespace TinyPress\Abstracts;

use TinyPress\Interfaces\ModelInterface;
use TinyPress\Interfaces\ContainerInterface;
use Symfony\Component\DependencyInjection\IntrospectableContainerInterface;
use TinyPress\Services\ContainerService;

abstract class ModelAbstract implements ModelInterface {

    private $_db;

    public function __construct() {

        $adapter = ContainerService::get( 'symfony' )->getParameter( 'database' );

        if ( empty( $adapter['adapter'] ) ) {
            throw new \LogicException( $adapter['adapter'] . 'is not registered in your application.');
        }

        $this->_db = ContainerService::get( 'core' )->get( $adapter['adapter'] );
        $configs   = ContainerService::get( 'symfony' )->getParameter( $adapter['adapter'] );
        $this->_db->connect( $configs );

    }

    abstract public function getTableName();

    public function find( array $args = [] ) {

        return $this->_db->find( $this->getTableName(), $args );

    }

    public function execute( $query, array $values = [], array $options = [] ) {

        return $this->_db->execute( $query, $values, $options );

    }

    public function lastInsertId() {

        return $this->_db->lastInsertId();

    }
    
    public function search( $keyword, $limit, $offset ){
        
        return $this->_db->execute( "call Search('?','?', ?, ? )", [ $keyword, $this->getTableName(), $limit, $offset ]); 
    }
    
    public function read( array $id, array $fields = [] ) {

        return $this->_db->read( $this->getTableName(), $id, $fields );

    }

    public function readAll( array $ids, array $fields = [], $limit = 100 ) {

        return $this->_db->readAll( $this->getTableName(), $ids, $fields, $limit );

    }

    public function save( array $data ) {

        return $this->_db->save( $this->getTableName(), $data );

    }

    public function count( array $params = [], array $fields = [] ) {

        return $this->_db->count( $this->getTableName(), $params, $fields );

    }

    public function saveAll( array $batch_data ) {

        return $this->_db->saveAll( $this->getTableName(), $batch_data );

    }

    public function update( array $id, array $data ) {

        return $this->_db->update( $this->getTableName(), $data, $id );

    }

    public function delete( array $id ) {

        return $this->_db->delete( $this->getTableName(), $id );

    }

}
