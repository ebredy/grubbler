<?php

namespace app\Controllers;

use app\Constants\MessageConstant;
use app\Constants\SessionConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Account extends WebController {

    public function signIn( Request $request ) {

        if ( $this->security->isAuthenticated() ) {
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
        }

        $this->_layout  = TemplateConstant::LAYOUT_DEFAULT;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                return $this->_signIn( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->render( TemplateConstant::PAGE_LOGIN );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    public function signUp( Request $request ) {

        if ( $this->security->isAuthenticated() ) {
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
        }

        $this->_layout  = TemplateConstant::LAYOUT_DEFAULT;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                return $this->_signUp( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->render( TemplateConstant::PAGE_SIGNUP );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    public function settings( Request $request ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin();
        }

        $this->_layout  = TemplateConstant::LAYOUT_DEFAULT;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                return $this->_updateSettings( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->_getSettings();
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    public function resetPassword( Request $request ) {

        if ( $this->security->isAuthenticated() ) {
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
        }

        $this->_layout  = TemplateConstant::LAYOUT_DEFAULT;
        $http_method    = $request->getMethod();

        $params = $this->validate->query( $request, [
            'token'  => 'isResetToken'
        ] );

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:

                if ( !empty( $params['token'] ) ) {
                    return $this->_resetPassword( $request, $params['token'] );
                }

                return $this->_createPasswordResetToken( $request );

                break;
            case HttpConstant::METHOD_GET:

                if ( !empty( $params['token'] ) ) {
                    return $this->render( TemplateConstant::PAGE_RESET_PASSWORD, [ 'token' => $params['token'] ] );
                }

                return $this->render( TemplateConstant::PAGE_RESET_PASSWORD_REQUEST );

                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    public function closeAccount( Request $request ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->_requestLogin();
        }

        $this->_layout  = TemplateConstant::LAYOUT_DEFAULT;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                return $this->_closeAccount( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->render( TemplateConstant::PAGE_CLOSE_ACCOUNT );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    public function signOut( Request $request ) {

        if ( !$this->security->isAuthenticated() ) {
            return $this->redirect( $this->generateUrl( RouteConstant::LOGIN ) );
        }

        $http_method = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_GET:

                $this->user->signOut();
                return $this->redirect( $this->generateUrl( RouteConstant::LOGIN ) );

                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }

    private function _getSettings() {

        $response = $this->user->getSettings();

        return $this->render( TemplateConstant::PAGE_SETTINGS, $response->getData() );

    }

    private function _closeAccount( $request ) {

        $params = $this->validate->request( $request, [
            'current_password'  => 'isPassword|isRequired'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_CLOSE_ACCOUNT, $params );
        }

        $response = $this->user->closeAccount( $params );

        if ( !$response->isOk() ) {
            $this->flash( $response->getError( 'flash', 'Please try again. An error occurred' ) );
            return $this->render( TemplateConstant::PAGE_CLOSE_ACCOUNT );
        }

        $this->flash( 'Your account has been deleted. Thank you!' );

        return $this->redirect( $this->generateUrl( RouteConstant::LOGOUT ) );

    }

    private function _createPasswordResetToken( $request ) {

        $params = $this->validate->request( $request, [
            'email'  => 'isEmail|isRequired'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_RESET_PASSWORD, $params );
        }

        $response = $this->user->setPasswordResetToken( $params );

        $this->flash( 'Please check your email for further instructions!' );

        return $this->redirect( $this->generateUrl( RouteConstant::LOGIN ) );

    }

    private function _resetPassword( Request $request, $token ) {

        $params = $this->validate->request( $request, [
            'password'  => 'isPassword|isRequired',
            'cpassword' => 'isPassword|isRequired'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_RESET_PASSWORD, $params );
        }

        $params['token'] = $token;
        $response        = $this->user->resetPassword( $params );

        if ( !$response->isOk() ) {
            $params['errors'] = $response->getErrors();
            return $this->render( TemplateConstant::PAGE_RESET_PASSWORD, $params );
        }

        $this->flash( 'You may now login with your new password' );

        return $this->redirect( $this->generateUrl( RouteConstant::LOGIN ) );

    }

    private function _updateSettings( $request ) {

        $params = $this->validate->request( $request, [
            'email'             => 'isEmail',
            'current_password'  => 'isPassword|isRequired',
            'password'          => 'isPassword',
            'fname'             => 'isName',
            'lname'             => 'isName'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_SETTINGS, $params );
        }

        $response = $this->user->updateSettings( $params );

        if ( !$response->isOk() ) {
            $this->flash( $response->getError( 'flash', 'Please try again. An error occurred' ) );
            return $this->render( TemplateConstant::PAGE_SETTINGS, $params );
        }

        $this->flash( 'Your settings have been updated!' );

        return $this->redirect( $this->generateUrl( RouteConstant::SETTINGS ) );

    }

    private function _signIn( Request $request ) {

        if ( $this->security->isAuthenticated() ) {
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
        }

        $params = $this->validate->request( $request, [
            'email'    => 'isEmail',
            'password' => 'isPassword'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_LOGIN, $params );
        }

        $response = $this->user->signIn( $params );

        if ( !$response->isOk() ) {
            $this->flash( $response->getError( 'flash', MessageConstant::INVALID_LOGIN_CREDENTIALS ) );
            return $this->render( TemplateConstant::PAGE_LOGIN, $params );
        }

        if ( $this->session->has( SessionConstant::FORWARD ) ) {
            $forward = $this->session->get( SessionConstant::FORWARD  );
            $this->session->remove( SessionConstant::FORWARD  );
            return $this->redirect( $forward );
        }

        return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );

    }

    private function _signUp( Request $request ) {

        $params = $this->validate->request( $request, [
            'email'    => 'isEmail|isRequired',
            'password' => 'isPassword|isRequired',
            'fname'    => 'isName|isRequired',
            'lname'    => 'isName|isRequired'
        ] );

        if ( !empty( $params['errors'] ) ) {
            return $this->render( TemplateConstant::PAGE_SIGNUP, $params );
        }

        $response = $this->user->register( $params );

        if ( !$response->isOk() ) {
            $this->flash( $response->getError( 'flash', 'An error occurred. Please try again' ) );
            $params['errors'] = $response->getErrors();
            return $this->render( TemplateConstant::PAGE_SIGNUP, $params );
        }

        $this->flash( 'Congrats! You may now login!' );

        return $this->redirect( $this->generateUrl( RouteConstant::LOGIN ) );

    }

}