<?php

namespace TinyPress\Adapters;

use TinyPress\Interfaces\DatabaseInterface;
use Doctrine\DBAL\DriverManager;

class DoctrineAdapter implements DatabaseInterface
{

    private $_doctrine;

    public function count( $table_name, array $conditions = [], array $fields = [] ) {

        $fields = ( !empty( $fields ) )
                  ? implode( ', ', $fields )
                  : '*';
        $sql    = "SELECT COUNT({$fields}) AS total FROM {$table_name}";

        if ( $conditions ) {
            $sql .= " WHERE " . $this->_buildConditions( $conditions );
        }

        try {
            $result = $this->_doctrine->executeQuery( $sql )->fetch();
        } catch ( \Exception $e ) {
            return 0;
        }

        return isset( $result['total'] ) ? $result['total'] : 0;

    }

    public function execute( $query, array $values = [], array $options = [] ) {

        try {
            return $this->_doctrine->executeQuery( $query, $values, $options )->fetchAll();
        } catch ( \Exception $e ) {
            return [];
        }

    }

    public function find( $table_name, array $params = [] ) {

        return $this->_find( $table_name, $params );

    }

    public function saveAll( $table_name, $batch_data ) {

        $keys   = array_keys( $batch_data[0] );
        sort( $keys, SORT_STRING );
        $fields = implode( ',', $keys );
        $sql    = "INSERT INTO $table_name ( $fields ) VALUES ";
        $comma  = '';

        foreach ( $batch_data as $row ) {

            ksort( $row, SORT_STRING );

            $values = [];

            foreach ( $row as $field => $value ) {
                $values[] = is_numeric( $value ) ? $value : "'$value'";
            }

            $sql .= $comma . '(' . implode(',', $values ) . ')';
            $comma = ',';

        }

        try {

            $stmt = $this->_doctrine->prepare( $sql );
            return $stmt->execute();

        } catch ( \Exception $e ) {
            return false;
        }

    }

    public function read( $table_name, array $id, array $fields = [] ) {

        $fields = ( !empty( $fields ) )
                  ? implode( ', ', $fields )
                  : '*';
        $key    = key( $id );
        $sql    = "SELECT {$fields} FROM {$table_name} WHERE {$key} = ? LIMIT 1";

        try {
            return $this->_doctrine->fetchAssoc( $sql, [ $id[ $key ] ]);
        } catch ( \Exception $e ) {
            return [];
        }

    }
    public function readAll( $table_name, array $id, array $fields = [], $limit = 100 ) {

        $fields = ( !empty( $fields ) )
                  ? implode( ', ', $fields )
                  : '*';
        $key    = key( $id );
        $sql    = "SELECT {$fields} FROM {$table_name} WHERE {$key} = ? LIMIT {$limit}";

        try {
            return $this->_doctrine->fetchAll( $sql, [ $id[ $key ] ]);
        } catch ( \Exception $e ) {
            return [];
        }

    }
    public function update( $table_name, array $data, array $id ) {

        try {
            return $this->_doctrine->update( $table_name, $data, $id );
        } catch ( \Exception $e ) {
            return [];
        }

    }

    public function lastInsertId() {

        try {
            return $this->_doctrine->lastInsertId();
        } catch ( \Exception $e ) {
            return false;
        }

    }

    public function save( $table_name, array $data ) {

        try {
            return $this->_doctrine->insert( $table_name, $data );
        } catch ( \Exception $e ) {
            return false;
        }

    }

    public function delete( $table_name, array $id ) {

        try {
            return $this->_doctrine->delete( $table_name, $id );
        } catch ( \Exception $e ) {
            return false;
        }

    }

    private function _find( $table_name, array $params = [] ) {

        $sql  = "SELECT ";
        $sql .= ( !empty( $params['fields'] ) )
            ? implode( ', ', $params['fields'] )
            : '*';
        $sql .= " FROM " . $table_name;

        if ( !empty( $params['conditions'] ) ) {
            $sql .= " WHERE " . $this->_buildConditions( $params['conditions'] );
        }

        if ( !empty( $params['order'] ) ) {
            $sql .= " ORDER BY " . $params['order'];
        }

        if ( !empty( $params['group'] ) ) {
            $sql .= " GROUP BY " . implode( ',', $params['group'] );
        }

        if ( !empty( $params['limit'] ) ) {
            $sql .= " LIMIT " . $params['limit'];
        }

        if ( !empty( $params['offset'] ) ) {
            $sql .= " OFFSET " . $params['offset'];
        }

        try {
            return $this->_doctrine->fetchAll( $sql );
        } catch ( \Exception $e ) {
            return [];
        }

    }

    private function _buildConditions( array $conditions ) {

        $params    = [];
        $operators = [ '<=', '>=', '>', '<', 'IS NOT NULL' ]; //Order matters because of strpos

        foreach( $conditions as $field => $value ) {

            $operator = '=';

            foreach ( $operators as $var ) {

                if ( strpos( $field, $var ) ) {
                    $field    = trim( rtrim( $field, $var ) );
                    $operator = trim( $var );
                    break;
                }

            }

            if ( $operator === 'IS NOT NULL' ) {
                $params[] = "{$field} {$operator}";
            }
            else {
                $params[] = ( is_numeric( $value ) )
                    ? "{$field} {$operator} {$value}"
                    : "{$field} {$operator} '{$value}'";
            }

        }

        return implode( ' AND ', $params );

    }

    public function connect( array $config ) {

        $this->_doctrine = DriverManager::getConnection( $config );

    }

}
