<?php

namespace app\Controllers;

use app\Constants\HttpConstant;
use app\Constants\OrderNotificationConstant;
use app\Library\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TinyPress\Exceptions\HttpMethodNotAllowedException;

class OrderNotification extends WebController {

    public function index( Request $request, $process_status = 0 ) {

        $http_method = $request->getMethod();

        switch ( $http_method ) {

            case HttpConstant::METHOD_GET:
            case HttpConstant::METHOD_POST:    
                $response = $this->_batch($process_status);

                break;

            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }
        return new Response($response,
                    Response::HTTP_OK,
                    ['content-type' => 'text/html']

        );
    }

    private function _batch($process_status) {

            $sleepTime =  new \datetime();
            $orderNotifications    = $this->ordernotifications->getQueue( 
                 $process_status
                ,$process_status == 1 ? $sleepTime->getTimestamp(): NULL    
            );
            echo '<pre>';
            var_dump($orderNotifications);
            echo '</pre>';
            
            
            switch($process_status){

                case    OrderNotificationConstant::STATUS_SEND_RESTAURANT_FAX:
                   
                   $result = $this->ordernotification->batchSendFax( $orderNotifications ); 
                   $response ='fax sent..';
                   $orderNotificationIDs = array_map(function($element){return $element['orderNotification_ID'];}, $orderNotifications);
                   var_dump($orderNotificationIDs);
                   //sleep for 4 minutes
                   $sleepTime =  new \datetime();
                   $sleepTime->add(new \DateInterval('PT4M'));
                   $this->ordernotifications->updateProcessStatus( 
                                                            OrderNotificationConstant::STATUS_SEND_RESTAURANT_CONFIRM_CALL
                                                            ,$orderNotificationIDs
                                                            ,$sleepTime->getTimestamp()
                    );
                     
                    break;
                case    OrderNotificationConstant::STATUS_SEND_RESTAURANT_CONFIRM_CALL:
                   $response = 'confirmation call sent...';
                    $this->ordernotification->BatchSendPhoneCall($orderNotifications);
                    $orderNotificationIDs = array_map(function($element){return $element['orderNotification_ID'];}, $orderNotifications);
                    $this->ordernotifications->updateProcessStatus( 
                                                            OrderNotificationConstant::STATUS_SEND_CUSTOMER_TEXT
                                                            ,$orderNotificationIDs
                                                            
                    );
                    break;
                case    OrderNotificationConstant::STATUS_SEND_CUSTOMER_TEXT:
                    $this->ordernotification->batchSendSMS( $orderNotifications );
                   $response = 'customer text sent...';
                    $orderNotificationIDs = array_map(function($element){return $element['orderNotification_ID'];}, $orderNotifications);
                    $this->ordernotifications->updateProcessStatus( 
                                                            OrderNotificationConstant::STATUS_COMPLETE
                                                            ,$orderNotificationIDs
                                                            
                           );                     
                    break;
                default: 
                    break;
            }
            return $response;
    }
    public function call(Request $request, $orderNotification_ID  ) {

            $http_method = $request->getMethod();
            
        switch ( $http_method ) {

            case HttpConstant::METHOD_GET:
            case HttpConstant::METHOD_POST:  
                
               $orderNotification = $this->ordernotifications->read([
                   'orderNotification_ID' => $orderNotification_ID
               ]);
                
                $response = $this->ordernotification->createVoiceConfirmation($orderNotification);
                break;

            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }
        
        return new Response($response,
                    Response::HTTP_OK,
                    ['content-type' => 'text/xml']

        );
    } 
    public function response(Request $request, $orderNotification_ID  ) {
        
        $http_method = $request->getMethod();
        
        switch ( $http_method ) {

            
            case HttpConstant::METHOD_GET:
            case HttpConstant::METHOD_POST:   

                $response = $this->_response($request, $orderNotification_ID);
                
                break;

            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }
        
        return new Response($response,
            Response::HTTP_OK,
            ['content-type' => 'text/xml']

        );
    }
    private function _response(Request $request, $orderNotification_ID){
        
        $orderNotification = $this->ordernotifications->read([
                   'orderNotification_ID' => $orderNotification_ID
        ]);
        
        $params = $this->validate->request( $request, [
            'Digits'     => 'isInt'
        ] );
        
        mail('ebredy@gmail.com', 'digits _confirm', json_encode($params));
        
        $params['Digits'] =  isset($params['Digits']) ? $params['Digits']: 0;
       

        switch($params['Digits']){
            case 2:
                $response = $this->ordernotification->rejectOrder( $orderNotification );
            break;
            case 1:
            default:
                $response = $this->ordernotification->enterOrderConfirmation( $orderNotification );
            break;    
        }
        
        return $response;
    }
    public function confirm(Request $request, $orderNotification_ID  ) {

        $http_method = $request->getMethod();
        switch ( $http_method ) {

            
            case HttpConstant::METHOD_GET:
            case HttpConstant::METHOD_POST:   
               $response = $this->_confirm( $request, $orderNotification_ID );

                break;

            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }
        
        return new Response($response,
            Response::HTTP_OK,
            ['content-type' => 'text/xml']

        );
    }
    
    
    private function _confirm( Request $request, $orderNotification_ID){
        

        $params = $this->validate->request( $request, [
            'Digits'     => 'isInt'
        ] );
       

        
        $params['Digits'] = isset($params['Digits']) && strlen($params['Digits'])!=4?null: $params['Digits'];
          
        if( strlen($params['Digits']) == 4 ){
            
            $orderNotification = $this->ordernotifications->read([
                'order_confirmation_number' => $params['Digits']
            ]);
            
            if( $orderNotification['orderNotification_ID'] == $orderNotification_ID ){
                
                $this->ordernotifications->updateProcessStatus( 
                    OrderNotificationConstant::STATUS_SEND_CUSTOMER_TEXT  
                    ,array($orderNotification_ID)
                                                    
                );
                $response = $this->ordernotification->Success( $orderNotification );
            }
            else
            {
                $response = $this->ordernotification->Error( $orderNotification_ID, 'Error! The order confirmation number did not match what we have in our database. $orderNotification_ID: ' );
            }
        }
        else
        {
            $response = $this->_response($request, $orderNotification_ID);
        }
        return $response;
    }    
    public function reject(Request $request, $orderNotification_ID  ) {
        
        $http_method = $request->getMethod();

        switch ( $http_method ) {

            
            case HttpConstant::METHOD_GET:
            case HttpConstant::METHOD_POST:    
               $orderNotification = $this->ordernotifications->read([
                   'orderNotification_ID' => $orderNotification_ID
               ]);
                
                $response = $this->ordernotification->rejectOrder( $orderNotification );
                
                break;

            default:

                throw new HttpMethodNotAllowedException( "Http method [$http_method] not allowed" );

        }
        
        
        return new Response($response,
            Response::HTTP_OK,
            ['content-type' => 'text/xml']

        );
    }    
}