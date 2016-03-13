<?php

namespace app\library;
define( "TWILIO_PATH",VENDOR_PATH."twilio".DS."twilio-php".DS."Services".DS );
define( "PHAXIO_PATH",VENDOR_PATH."phaxio".DS."phaxio-php".DS."lib".DS."Phaxio".DS );
require_once TWILIO_PATH.'Twilio.php';
require_once PHAXIO_PATH.'Phaxio.php';
require_once PHAXIO_PATH.'PhaxioException.php';
require_once PHAXIO_PATH.'PhaxioOperationResult.php';

use app\Constants\OrderNotificationConstant;
use Phaxio\Phaxio;
use \Services_Twilio;
use \Services_Twilio_Twiml;


class OrderNotification extends Controller {
    private $_twilio;
    private $_phaxio;

    public function _initService() {

                   
        $twilio = $this->getParameter( 'twilio' );
        $phaxio = $this->getParameter( 'phaxio' );
       
        $this->_phaxio = new Phaxio($phaxio['phaxio_key'], $phaxio['phaxio_secret'], $phaxio['phaxio_api_host']);
        $this->_twilio = new Services_Twilio($twilio['twilio_sid'],$twilio['twilio_token']);
        
        
        

    }

    public function queue( $order_id ) {

        $this->ordernotifications->queue( $order_id );
        
        //$orderNotification = $this->ordernotificatios->read( [ 'order_id' => $order_id ] );
        
        //$this->_generateFaxFile($orderNotification['fax_file'], $orderNotification);
      


    }

    public function _generateFaxFile( $fileName, array $content ) {
        
        
        $faxFile  = fopen(realpath(OrderNotificationConstant::FAX_FILE_PATH.$fileName),"w+");
        
        
        
        
        $faxContent = $this->render(OrderNotificationConstant::FAX_FILE_PATH,$content);
        
        fwrite( $faxFile, $faxContent );
        close( $faxFile );
    }

    public function sendFax( $to_phone, array $files, array $options ) {
        
        $this->_initService();
        
        if($files || $options){
            $result = $this->_phaxio->sendFax($to_phone, $files, $options);
           
            return $result;
        }    
        return false;

    }
    public function batchSendFax(array $orderNotifications){
        
        
        if($orderNotifications){
          $this->_layout = OrderNotificationConstant::FAX_LAYOUT;  
          
            for($x=0; $x<count($orderNotifications); $x++){
                
                $fax_message = $this->render(OrderNotificationConstant::FAX_FILE_TEMPLATE,$orderNotifications[$x]);
                
                $result = $this->sendFax( $orderNotifications[$x]['restaurant_to_phone'], 
                                [], 
                                [  'string_data'=> $fax_message, 'string_data_type' => 'html', 'batch' => true , 'batch_delay' => 60 ]);
            }
            //$result = $this->_phaxio->fireBatch(uniqid());
            return $result;
        }
        return false;
    }
    public function sendSMS( $to_phone, $message ) {
        
        
        $this->_initService();
        
        $endOfHeaderPos = strpos($message,'~');
        
        if( $endOfHeaderPos !== false ){
            
            $message =  substr_replace($message, '',0,$endOfHeaderPos +1);
        }
        $messageResult = $this->_twilio->account->messages->sendMessage(
           OrderNotificationConstant::SMS_CUSTOMER_FROM_PHONE, // From a valid Twilio number
           $to_phone, // Text this number

         // text Message
         $message
        );
       return $messageResult->sid?true:false;
    }
    public function batchSendSMS( array $orderNotifications ){
        
        if($orderNotifications){
          
            $this->_layout = OrderNotificationConstant::SMS_CALL_LAYOUT;
            
            for($x=0; $x<count($orderNotifications); $x++){
                
                $message = $this->sendSMS(  
                                            $orderNotifications[$x]['customer_to_phone']
                                            ,$this->render(OrderNotificationConstant::SMS_TEMPLATE,$orderNotifications[$x])
                                         );
            }
            
            return true; 
        }
        return false;        
    }
    public function sendPhoneCall( $to_phone, $orderNotification_ID  ) {

            $this->_initService();
            
           $call = $this->_twilio->account->calls->create(
              OrderNotificationConstant::VOICE_RESTAURANT_FROM_PHONE, // From a valid Twilio number
              $to_phone, // Call this number

            // Read TwiML at this URL when a call connects (hold music)
            $this->generateUrl( OrderNotificationConstant::ORDER_CONFIRMATION_URL ,['orderNotification_ID'=>$orderNotification_ID] )
          );


    }
    public function BatchSendPhoneCall( array $orderNotifications ) {

            $this->_initService();
            
            if($orderNotifications){

                for($x=0; $x<count($orderNotifications); $x++){

                    $message = $this->sendPhoneCall($orderNotifications[$x]['customer_to_phone'], $orderNotifications[$x]['orderNotification_ID'] );
                    //added because twilio can make one phone call per second
                    //unless more expensive package is purchase
                    sleep(1);
                }

                return true; 
            }


    }
     
    public function createVoiceConfirmation( array $orderNotification ){
        
        $response = new Services_Twilio_Twiml();

        if(!$orderNotification){
            $response->say(OrderNotificationConstant::STATUS_ERROR_NOT_FOUND, ['voice'=>'alice','loop'=>'5']);
            $response->hangup();
            return $response;
        }

        $response->gather(  [   'method'=>'POST'
                                ,'action'=>$this->generateUrl(OrderNotificationConstant::ORDER_CONFIRMATION_URL_RESPONSE,['orderNotification_ID'=>$orderNotification['orderNotification_ID']] )
                                , 'numDigits'=>1
                            ]
                        )
                    ->say( 'This is Grubbler calling to confirm that you receive order number '.$orderNotification['order_id'].' Please press 1 to enter the order confirmation or press 2 to reject this order'
                        ,['voice'=>'alice','loop'=>'5']);
        
        
        return $response;

    }
   public function enterOrderConfirmation( array $orderNotification ){
        
        $response = new Services_Twilio_Twiml();
        
        if(!$orderNotification){
            $response->say(OrderNotificationConstant::STATUS_ERROR_NOT_FOUND, ['voice'=>'alice','loop'=>'5']);
            $response->hangup();
            return $response;
        }
        $response->gather(  [   'method'=>'POST'
                                ,'action'=> $this->generateUrl(OrderNotificationConstant::ORDER_CONFIRMATION_URL_ENTER_NUMBER,['orderNotification_ID'=>$orderNotification['orderNotification_ID']] )
                                , 'finishOnKey'=>'#'
                            ]
                          )
                  ->say( 'Please enter the order confirmation number found on the fax for  '. 
                        ' order # '.$orderNotification['order_id'].'  Please enter your 4 digits confirmation number followed by the number sign'
                        ,['voice'=>'alice','language'=>'en-US', 'loop'=>'5']
                        );
        $response->say(  'you have not entered a confirmation.'
                            ,[
                                'loop'=>1
                             ]);
        $response->redirect($this->generateUrl(OrderNotificationConstant::ORDER_CONFIRMATION_URL_RESPONSE,['orderNotification_ID'=>$orderNotification['orderNotification_ID']]));
                             
       
        
        return $response;

    }
      public function rejectOrder( array $orderNotification ){
        
        $response = new Services_Twilio_Twiml();
        
        if(!$orderNotification){
            $response->say(OrderNotificationConstant::STATUS_ERROR_NOT_FOUND,['voice'=>'alice','loop'=>'5']);
            $response->hangup();
            return $response;
        }
        
        $response->say( 'You\'ve cancelled the order.  You will connect shortly with the customer named  '. 
                        $orderNotification['customer_first_name']. ' '.
                        $orderNotification['customer_last_name'].'.'.
                        ' order number '.$orderNotification['order_id']
                        ,['voice'=>'alice','language'=>'en-US', 'loop'=>'5']
                        );
        $response->dial($orderNotification['customer_to_phone']);
        $response->say(  'The call failed or the customer hanged up.  Please speak to them directly at '.$orderNotification['customer_to_phone'],["loop" => 7]);
        
        
        return $response;

    } 
    public function Error( $orderNotification_ID, $msg = 'There was an error with your entry.  Please try again. '  ){
        
        $response = new Services_Twilio_Twiml();
        

        
        $response->say( $msg
              
                        ,['voice'=>'alice','language'=>'en-US']
                        );
        $response->redirect($this->generateUrl(OrderNotificationConstant::ORDER_CONFIRMATION_URL_RESPONSE,['orderNotification_ID'=> $orderNotification_ID] ));
        
        
        return $response;

    }
    public function Success( array $orderNotification ){
        
        $response = new Services_Twilio_Twiml();
        

        
        

        $response->say( 'This order has being confirmed.' 
  
                        ,['voice'=>'alice','language'=>'en-US']
                        );
        $response->hangup();
        
        
        return $response;

    }    
}
