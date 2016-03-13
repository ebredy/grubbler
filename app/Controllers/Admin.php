<?php

namespace app\Controllers;

use app\Constants\MessageConstant;
use app\Constants\SessionConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use app\Constants\RouteConstant;
use app\Constants\TemplateConstant;
use app\Constants\AdminConstant;
use app\Constants\HttpConstant;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class Admin extends WebController {

    
    public function index( Request $request ) {

//        if ( $this->security->isAuthenticated() ) {
//            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
//        }
//        if ( $this->admin->hasPermissions() ) {
//            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
//        }
        $this->_layout  = TemplateConstant::LAYOUT_ADMIN;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                return $this->_signIn( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->render( TemplateConstant::PAGE_ADMIN_DASHBOARD );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }
    public function search( Request $request, $search_type ) {

        if ( $this->security->isAuthenticated() ) {
            
            return $this->redirect( $this->generateUrl( RouteConstant::INDEX ) );
            
        }

        $this->_layout  = TemplateConstant::LAYOUT_ADMIN;
        
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                
            case HttpConstant::METHOD_GET:    
                
                return $this->_search($request, $search_type);
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }
    public function _search( $request,  $model ){
        
        
        
        $params = $this->validate->query( $request, [
            'keyword'  => 'isAlphaNum|isRequired',
            'page'  => 'isNumeric|isRequired',
            'per_page'  => 'isNumeric|isRequired'

        ] );
        
        if(! empty( $params['errors'] ) ){
            
            $this->flash( $params['errors'] );
        }
        
        $limit =  $params['per_page'];
        
        $offset =  ($params['page']>0?$params['page']-1:$params['page'])*$limit;
        
        
        
        $search_result[$model]=[];
        
        switch ( $model ){

           case AdminConstant::USER_SEARCH:
              
                $search_result[$model] = $this->$model->search( $params['keyword'], $limit, $offset  );
                
                $search_result['pagination'] = $this->_pagination(  $params['per_page'], 
                                                                    $params['page'], 
                                                                    $search_result[$model][0]['total'], 
                                                                    $params['keyword'], 
                                                                    DS.AdminConstant::BASE_URL.DS.AdminConstant::BASE_SEARCH.DS.AdminConstant::USER_SEARCH );
                
                return $this->render( TemplateConstant::PAGE_ADMIN_USERS, $search_result );
                
                break;
            
           case AdminConstant::RESTAURANT_SEARCH:
               
                $search_result[$model] = $this->$model->search( $params['keyword'], $limit, $offset  );
                
                $search_result['pagination'] = $this->_pagination( $params['per_page'], 
                                                                   $params['page'], 
                                                                   $search_result[$model][0]['total'], 
                                                                   $params['keyword'], 
                                                                   DS.AdminConstant::BASE_URL.DS.AdminConstant::BASE_SEARCH.DS.AdminConstant::RESTAURANT_SEARCH );
                
                return $this->render( TemplateConstant::PAGE_ADMIN_RESTAURANTS, $search_result );
                
                break;
            
           case AdminConstant::MENU_SEARCH:
              
               
                $search_result[$model] = $this->$model->search( $params['keyword'] , $limit, $offset );
                
                $search_result['pagination'] = $this->_pagination(  $params['per_page'], 
                                                                    $params['page'], 
                                                                    $search_result[$model][0]['total'], 
                                                                    $params['keyword'], 
                                                                    DS.AdminConstant::BASE_URL.DS.AdminConstant::BASE_SEARCH.DS.AdminConstant::MENU_SEARCH );
                
                return $this->render( TemplateConstant::PAGE_ADMIN_MENUS, $search_result );
                
                break;
           default:
                
               $model =  null;
                
               $this->flash('invalid search parameters');
                
                break;

        }
        
        
    }
    
    public function _pagination( $per_page, $page, $total = 1, $keyword = null , $path = null ){
        
        $pagination = [];
        
        $pagination['per_page'] = $per_page;
        
        $pagination['page'] = $page;
        
        $pagination['total'] = $total;
        
        $pagination['total_page'] = $total > 0? ceil( $total/ $per_page ): 1;
        
        $pagination['display_page_min'] = $page > 0 ?( floor( $page/10 ) *10 )+1: 1;
        
        $pagination['display_page_max'] = ( ceil( $page/10 ) *10 ) < $total ?( ceil( $page/10 ) *10 ): $total;
        
        $pagination['pagination_url'] = $path? $path."?per_page=".$pagination['per_page']:DS.AdminConstant::BASE_URL.DS.AdminConstant::BASE_SEARCH.DS.AdminConstant::RESTAURANT_SEARCH."?per_page=".$pagination['per_page'];
        
        $pagination['pagination_url'] =  $keyword? $pagination['pagination_url'] ."&keyword=".$keyword."&page=": $pagination['pagination_url']."&page="; 
        
        return $pagination;
    }
    
    public function restaurants( Request $request ,  $restaurant_id  = null ){
        
        
        $this->_layout  = TemplateConstant::LAYOUT_ADMIN;
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
            case HttpConstant::METHOD_GET:
                return $this->_restaurants( $request, $restaurant_id );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }
    public function restaurantAdd( Request $request  ){
        
        
        
        
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                return $this->_restaurantAddEdit( $request );
                break;
            case HttpConstant::METHOD_GET:
                return $this->_getRestaurant( $request );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }  
    public function _getRestaurant( Request $request,  $restaurant_id =  null ){
        
        $this->_layout  = TemplateConstant::LAYOUT_ADMIN_WIZARD;
        
        $data = [];
        
        $data['restaurant'] = $restaurant_id? $this->restaurants->find(['id'=>$restaurant_id]):[];
        
        $data['cities'] = $this->cities->find();
        
        $data['states'] = $this->states->find();
        
        return $this->render( TemplateConstant::PAGE_ADMIN_RESTAURANT_ADD , $data );
    }
    
    
    public function restaurantEdit( Request $request, $restaurant_id  ){
        
        
        $this->_layout  = TemplateConstant::LAYOUT_ADMIN_WIZARD;
        
        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST:
                
                return $this->_restaurantAddEdit( $request, $restaurant_id );
                
                break;
            
            case HttpConstant::METHOD_GET:
                
                return $this->_getRestaurant( $request, $restaurant_id );
                
                break;
            
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }

    }    
     
    public function _restaurants( Request $request ,  $restaurant_id  = null ){
        
        //implement get method pagination
        $params = $this->validate->query( $request, [
   
            'page'  => 'isNumeric',
            'per_page'  => 'isNumeric'

        ] );   

        $params['page'] = isset($params['page'])?$params['page']:1;
        
        $params['per_page'] = isset($params['per_page'])?$params['per_page']:25;
        
        $start = isset($params['page']) && $params['page']-1>0?($params['page']-1)*$params['per_page']:0;
        
        $limit = isset($params['per_page']) ?$params['per_page']:25;
        
        $args  = [
            'order' => 'id asc',
            'limit' => "$start,$limit",

        ];
        
        if( $restaurant_id ) $args['conditions'] = [
                'id' => $restaurant_id
        ];
       
        
        $total =  $this->restaurants->count();   
        
        $admin['restaurants'] = $this->restaurants->find( $args );
        
        $admin['pagination'] = $this->_pagination( $limit, $params['page'], $total, null, DS.AdminConstant::BASE_URL.DS.AdminConstant::RESTAURANT );
        
        return $this->render( TemplateConstant::PAGE_ADMIN_RESTAURANTS, $admin );
        
    }
    public function _restaurantAddEdit( Request $request ,  $restaurant_id  = null ){
        
        //implement get method pagination
        //$rules = 
        
        
        
        $params = $this->validate->request( $request, [
                'restaurant'   =>  'isAlphaNum|isRequired'
                ,'phone'        => 'isAlphaNum|isRequired'
                ,'fax'          => 'isAlphaNum|isRequired'
                ,'address'      => 'isAlphaNum|isRequired'
                ,'city_id'      => 'isNumeric|isRequired'
                ,'state_id'     => 'isNumeric|isRequired'
                ,'zipcode'      => 'isAlphaNum|isRequired'
                ,'opens'        => 'isAlphaNum|isRequired'
                ,'closes'       => 'isAlphaNum|isRequired' 
                ,'delivery_radius' => 'isNumeric|isRequired'
                ,'latitude'     => 'isFloat|isRequired'
                ,'longitude'    => 'isFloat|isRequired'
                ,'rating'       => 'isNumeric|isRequired'
                ,'price'        => 'isNumeric|isRequired'
                ,'full_address' => 'isAlphaNum|isRequired'
            
        ]);   
        
        if(!empty($params['errors'])){
                
            if ( $request->isXmlHttpRequest() ) {
            
                return $this->_ajaxError($params['errors']);
            
            }
            else{
                $this->flash($params['errors']);
            }
        }
        

        $response = $this->admin->setRestaurant( $params, $restaurant_id );
        
        if ( $request->isXmlHttpRequest() ) {
            
            return $response->isOk()  ? $this->_ajaxSuccess( $response->getData() ) 
                                    : $this->_ajaxError( $response->getErrors() );
            
        }
        else{
            
            return $this->flash("non ajax request is not allowed!");
        }

        
    }    
    
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
    public function getCoordinates( Request $request ){

        $http_method    = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_POST: 
            case HttpConstant::METHOD_GET:    
                return $this->_getCoordinates( $request );
                break;
            default:
                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }
        
    }
    public function _getCoordinates( Request $request ){
        
         $params = $this->validate->request( $request, [
           'full_address' => 'isAlphaNum|isRequired'
            
        ]);   
       
       if(!isset($params['full_address'])){
            
            return $this->_ajaxError( 'missing full address to complete this request: '.json_encode($params['errors']));

        }
        $response =  $this->address->getCoordinates($params['full_address']);
        
        
            
         return $response->isOk()  ? $this->_ajaxSuccess( $response->getData() ) 
                                    : $this->_ajaxError( $response->getErrors() );  
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