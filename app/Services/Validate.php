<?php

namespace app\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use TinyPress\Exceptions\MissingRequiredParameterException;

class Validate {

    private $_validator;

    public function __construct() {

        $this->_validator = Validation::createValidator();

    }

    public function request( Request $request, array $rules ) {

        return $this->params( $request->request->all(), $rules );

    }


    public function query( Request $request, array $rules ) {

        return $this->params( $request->query->all(), $rules );

    }

    public function params( array $params, array $rules ) {

        $errors  = [];
        $valid   = [];

        foreach( $rules as $field => $rule ) {

            $value = ( ( isset( $params[ $field ] ) && ( $params[ $field ] !== '' ) ) )
                ? $params[ $field ]
                : null;

            $rules          = (array)explode( '|', $rule, 2 );
            $is_required    = array_search( 'isRequired', $rules );

            if ( $is_required !== false ) {

                if ( $value === null ) {
                    $errors[ $field ] = 'This field is required';
                    continue;
                }

                unset( $rules[ $is_required ] );

            }

            if ( empty( $rules ) ) {
                throw new MissingRequiredParameterException( "The field [$field] is missing a validation rule" );
            }

            $error = $this->{ $rules[ 0 ] }( $value );

            if ( $error ) {
                $errors[ $field ] = $error;
            } else if ( $value !== null ) {
                $valid[ $field ] = $value;
            }

        }

        if ( !empty( $errors ) ) {
            $valid['errors'] = $errors;
        }

        return $valid;

    }

    //TODO: Implement
    public function isAddress( $str ) {

        return null;

    }

    //TODO: Implement
    public function isSafeString( $str ) {

        return null;

    }

    public function isNumeric( $str ) {

        return $this->isInt( $str );
    }

    public function isInt( $str ) {

        $errors = $this->_validator->validate( $str, [
                new Assert\Regex(
                    [
                        'pattern' => '/[0-9]/',
                        'match'   => true,
                        'message' => 'Only integers allowed'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }
    public function isMoney( $str ) {

        $errors = $this->_validator->validate( $str, [
                new Assert\Regex(
                    [
                        'pattern' => '/^[0-9]+(?:\.[0-9]{1,3})?$/',
                        'match'   => true,
                        'message' => 'Only money format allowed'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }
    public function isResetToken( $token ) {

        return null;

    }

    public function isToken( $key ) {

        $errors = $this->_validator->validate( $key,
            [
                new Assert\Regex(
                    [
                        'pattern' => '/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/',
                        'match'   => true,
                        'message' => 'Remove special characters'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isRouteKey( $key ) {

        $errors = $this->_validator->validate( $key,
            [
                new Assert\NotBlank(),
                new Assert\Regex(
                    [
                        'pattern' => '/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/',
                        'match'   => true,
                        'message' => 'Remove special characters'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isAlphaNum( $str, $min = 3, $max = 100 ) {

        $errors = $this->_validator->validate( $str,
            [
                new Assert\Length( [ 'min' => $min, 'max' => $max ] ),
                new Assert\Regex(
                    [
                        'pattern' => '/[a-zA-Z0-9 ]/',
                        'match'   => true,
                        'message' => 'Only numbers and characters'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isAlpha( $str, $min = 2, $max = 65 ) {

        $errors = $this->_validator->validate( $str,
            [
                new Assert\Length( [ 'min' => $min, 'max' => $max ] ),
                new Assert\Regex(
                    [
                        'pattern' => '/[a-zA-Z ]/',
                        'match'   => true,
                        'message' => 'Remove special characters'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isEmail( $email ) {

        $errors = $this->_validator->validate( $email,
            [
                new Assert\Email()
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isPassword( $password, $min = 6, $max = 25  ) {

        $errors     = $this->_validator->validate( $password,
            [
                new Assert\Length( [ 'min' => $min, 'max' => $max ] )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isName( $name ) {

        $errors     = $this->_validator->validate( $name,
            [
                new Assert\Length( [ 'min' => 2, 'max' => 25 ] ),
                new Assert\Regex(
                    [
                        'pattern' => '/\d/',
                        'match'   => false,
                        'message' => 'Your name cannot contain a number'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isPhone( $string ) {


        $errors     = $this->_validator->validate( $string,
            [
                new Assert\Length( [ 'min' => 10, 'max' => 25 ] ),
                new Assert\Regex(
                    [
                        'pattern' => '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',
                        'match'   => true,
                        'message' => 'Invalid phone number'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isZipCode( $string ) {


        $errors     = $this->_validator->validate( $string,
            [
                new Assert\Length( [ 'min' => 5, 'max' => 10 ] ),
                new Assert\Regex(
                    [
                        'pattern' => '/^([0-9]{5})(-[0-9]{4})?$/i',
                        'match'   => true,
                        'message' => 'Invalid zip code'
                    ]
                )
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

    public function isNotEmpty( $str ) {

        $errors = $this->_validator->validate( $str,
            [
                new Assert\NotBlank(),
            ]
        );

        if ( $errors->count() ) {
            return $errors[0]->getMessage();
        }

        return null;

    }

}