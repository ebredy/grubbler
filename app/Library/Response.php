<?php

namespace app\Library;

class Response implements \ArrayAccess {

    private $_data   = [];
    private $_errors = [];

    public function isOk() {

        return empty( $this->_errors );

    }

    public function getErrors() {

        return $this->_errors;

    }

    public function getData() {

        return $this->_data;

    }

    public function set( $key, $value ) {

        $this->_data[ $key ] = $value;
        return $this;

    }

    public function setError( $key, $value ) {

        $this->_errors[ $key ] = $value;
        return $this;

    }

    public function getError( $key, $default = null ) {

        return ( isset( $this->_errors[ $key ] ) )
            ? $this->_errors[ $key ]
            : $default;

    }

    public function get( $key, $default = null ) {

        return ( isset( $this->_data[ $key ] ) )
            ? $this->_data[ $key ]
            : $default;

    }

    public function setErrors( array $errors ) {

        $this->_errors = array_merge( $this->_errors, $errors );
        return $this;

    }

    public function setData( array $data ) {

        $this->_data =  array_merge( $this->_data, $data );
        return $this;

    }

    public function offsetSet($offset, $value) {

        if ( !is_null( $offset ) ) {
            $this->_data[ $offset ] = $value;
        }

    }

    public function offsetExists( $offset ) {

        return isset( $this->_data[ $offset ] );

    }

    public function offsetUnset( $offset ) {

        unset( $this->_data[ $offset ] );

    }

    public function offsetGet( $offset ) {

        return ( $this->offsetExists( $offset ) ) ? $this->_data[ $offset ] : null;

    }

}