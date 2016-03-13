<?php

namespace app\Library;

use app\Constants\MessageConstant;
use app\Constants\SessionConstant;
use app\Constants\AdminConstant;
use Symfony\Component\HttpFoundation\Request;
use app\Library\Upload;

class Admin extends Controller {



    public function setPermissionList( array $permissionList ) {

        $service_response   = $this->_getServiceResponse();
        
        $oldPermissionList               = $this->permissions->lookup_read( [ 'id' => $permissionList['id'] ] );

        if ( !empty( $oldPermissionList ) ) {
            
            $this->permissions->lookup_update( $permissionList['page'], $permissionList );
        }
        else{
            
            $permissionListCreated = $this->permissions->lookup_save( $permissionList );
            
        }



        if ( !$permissionListCreated ) {
            return $service_response->setError( 'flash', 'Error creating Permission List. Please try again'  );
        }

        return $service_response;

    }
    public function hasPermissions(request $request){
        
        $service_response = $this->_getServiceResponse();
        $permission = $this->_getUser( 'permissions' );
        
        if( $permission ){
            
            
        }
        else{
            $response =  $this->getPermissions();
            
            $permissions = $response->getData();
        }
        if( empty( $permissions ) ){
            
            return $service_response->setError("permissions","You do not have admin rights.");
        }
        
        
        
        $currentRoute = $request->get('_route');
        
        foreach($permissions as $permission){
            
            if($currentRoute == $this->generateUrl( $permission['route'], json_decode( $permission['route_var'] )? json_decode($permission['route_var']):[] )  ){
                
                return $service_response;
            }
        }
        
        return $service_response->setError("permissions","You do not have permission to this page: ".$currentRoute);
      
    }
    public function getPermissions() {

        $service_response = $this->_getServiceResponse();
        
        $user_id          = $this->_getUser( 'id' );
        
        $permissions      = $this->permissions->read( [ 'user_id' => $user_id ] );
        
        $this->_setUserAttr( 'permissions' , $permissions );
        
        return $service_response->setData( $permissions );

    }

    public function setPermissions( array $permissions ){
        
        $service_response   = $this->_getServiceResponse();
        
        if(!isset($permissions['user_id'])){
            
            return $service_response->setError( 'flash', 'Error identifying admin credentials'  );
        }
        $oldpermissions    = $this->permissions->read( [ 'user_id' => $permissions['user_id'] ] );
        
        if( empty( $oldpermissions ) ){
            
            $permission_Created    =  $this->permissions->save( $permissions );
        }
        else
        {
            
            $permission    = $this->permissions->update( [ 'user_id' => $permissions['user_id'] ], $permissions );
        }
        
        

        if ( !$permission_Created ) {
            return $service_response->setError( 'flash', 'Error creating permissions. Please try again'  );
        }

        return $service_response;        
           
    }
 
        public function setMenuUpload($restaurant_id, $memuItem){
        
        $service_response   = $this->_getServiceResponse();
        
        if(!$restaurant_id){
            
            return $service_response->setError( 'restaurant', 'restaurant id is not set'  );
        }
        
        $restaurant = $this->restaurants->read([
                                        'id'=> $resaurant_id 
                                    ]);
        
        if(empty($restaurant['id'])){
            
            return $service_response->setError('restaurant', 'unable to find restaurant!');
        }
        $memuItem['restaurant_id'] = $restaurant['id'];
        
        if( empty( $memuItem['id'] ) ){
            
            
            
            $isMenuSaved = $this->menus->save($memuItem);
            
            if(!$isMenuSaved){
                
                return $service_response->setError('menu', 'unable to save menu item for restaurant: '.$restaurant['restaurant']);
            }
            
            $menu_id =  $this->menus->lastInsertId();
        }
        else{
            $this->menus->update([ 'id' => $menuItem['id'] ], $menuItem);
            
            $menu_id = $memuItem['id'];
        }
        if (empty($_FILES[AdminConstant::MENU_FILE_NAME])) { 
            
            
            return $service_response->setError( 'file_name', 'Unable to find file name'  );
            
            
        }
        else{

            $upload = Upload::factory(AdminConstant::MENU_UPLOAD_PATH);
            $upload->file($_FILES[AdminConstant::MENU_FILE_NAME]);

            //$validation = new validation;

            //$upload->callbacks($validation, array('check_name_length'));

            $results = $upload->upload();

            if ( !$result['status'] ) {
                
                return $service_response->setError( 'error', 'Error Uploading image:'  );
            }
            $memuItem['image'] = $result['full_path'];
            
            $this->menus->update([ 'id' => $menuItem['id'] ], $menuItem);


        }


        return $service_response;        
           
    }
    
    public function setRestaurant($restaurant, $restaurant_id = null){
        
       $service_response   = $this->_getServiceResponse();
       
        if( $restaurant_id ){ 
            
          $is_saved = $this->restaurants->update( [ 'id' => $restaurant_id ], $restaurant );
          
        }
        else{
            
           $is_saved = $this->restaurants->save( $restaurant ); 
           
        }
        if ( !$is_saved  ) {
            
            return $service_response->setError( 'restaurants', 'Error saving restaurant' );
        }
        else{
            $restaurant_id = !$restaurant_id? $this->restaurants->lastInsertId() : $restaurant_id;
            return $service_response->setData(["restaurant_id" => $restaurant_id ]);
        }
        
        
        return $service_response;
    }

    
}