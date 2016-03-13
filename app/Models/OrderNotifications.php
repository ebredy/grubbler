<?php

namespace app\Models;

use TinyPress\Abstracts\ModelAbstract;

class OrderNotifications extends ModelAbstract {

    const TABLE_NAME = 'ordernotifications';

    public function getTableName() {

        return self::TABLE_NAME;

    }
    public function queue( $order_id ) {

        $SQL="insert into ordernotifications
                        (
                        user_id,
                        order_id,
                        customer_first_name,
                        customer_last_name,
                        customer_address,
                        customer_apt_number,
                        customer_address_2,
                        customer_city,
                        customer_state,
                        customer_zipcode,
                        customer_instructions,
                        customer_to_phone,
                        customer_from_phone,
                        restaurant_to_phone,
                        restaurant_from_phone,
                        restaurant_to_fax_phone,
                        restaurant_from_fax_phone,
                        sms_message,
                        phone_message,
                        fax_message,
                        order_confirmation_number,
                        process_status
                        )
                    select
                            a.user_id,
                            o.id,
                            a.fname as customer_first_name,
                            a.lname as customer_last_name,
                            a.address_1,
                            a.apt_number,
                            a.address_2,
                            c.name,
                            s.name,
                            a.zip_code,
                            a.instructions,
                            a.phone as customer_to_phone,
                            '' as customer_from_phone,
                            r.phone as restaurant_to_phone,
                            '' as restaurant_from_phone,
                            r.fax as restaurant_to_fax,
                            '' as restaurant_from_fax,
                            '' as sms_message,
                            '' as phone_message,
                            o.details as fax_message,
                            RPAD(FLOOR(10000*RAND()), 4, '0000'),
                            '0'
                    from orders o
                        inner join addresses a
                            on o.user_id = a.user_id
                        inner join  restaurants r
                            on o.restaurant_id = r.id
                        inner join cities c
                                on a.city_id = c.id
                        inner join states s
                                on a.state_id = s.id	    
                    where o.id = {$order_id}";

        return $this->execute($SQL);

    }
    public function updateProcessStatus($newProcessStatus, array $orderNotificationIDs, $sleep_until = NULL){
               
                $SQL="  update  ordernotifications
                        set     process_status = ".$newProcessStatus.",";
                            if($sleep_until){
                                $SQL.=" sleep_until = FROM_UNIXTIME(".$sleep_until.")";
                            }
                            else{
                                $SQL.=" sleep_until = NULL";
                            }
                        $SQL.=" where   orderNotification_ID in (".\join(',', $orderNotificationIDs).")";

        return $this->execute($SQL,
                                [
                                   $newProcessStatus,
                                   $sleep_until, 
                                   join(",", $orderNotificationIDs)
                                ] );
    }
    public function getQueue( $status,$sleep_until=NULL, $limit = 200 ) {

         $SQL= "  select * ".
                "from ordernotifications ".
                "where process_status = ".$status;
                if($sleep_until){
                 $SQL.= " and sleep_until <= FROM_UNIXTIME(".$sleep_until.") ";
                }
                else{
                  $SQL.= " and sleep_until is NULL ";  
                }
         $SQL.= "order by restaurant_to_phone asc ".
                'limit '.$limit;

        return $this->execute($SQL);
    

    }

}