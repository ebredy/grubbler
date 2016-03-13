<?php

namespace app\Constants;

class OrderNotificationConstant {

    const SMS_CUSTOMER_FROM_PHONE  = '(203)680-3280';
    const VOICE_RESTAURANT_FROM_PHONE = '(203)680-3280';
    const FAX_RESTAURANT_FROM_PHONE = '(203)680-3280';
    const ORDER_CONFIRMATION_URL = 'order_notification_call';
    const ORDER_CONFIRMATION_URL_RESPONSE = 'order_notification_voice_response';
    const ORDER_CONFIRMATION_URL_ENTER_NUMBER = 'order_notification_voice_confirm';
    const ORDER_CONFIRMATION_URL_ORDER_REJECT = 'order_notification_voice_reject';
    const FAX_FILE_PATH = 'app/Views/Pages/OrderNotifications/Fax/';
    const FAX_FILE_TEMPLATE = '/app/Views/Pages/OrderNotifications/FaxOrder.php';
    const SMS_TEMPLATE = '/app/Views/Pages/OrderNotifications/SMS.php';
    const FAX_LAYOUT = 'app/Views/Layouts/fax.php';
    const SMS_CALL_LAYOUT = 'app/Views/Layouts/empty.php';
    const SLEEP_TIME_FORMAT = '"Y-m-d H:i:s';
    const STATUS_ERROR_NOT_FOUND = 'The order is not in our database.  If there this is an error please contact grubbler at 1 8 0 0 g r u b b l e r';
    const STATUS_SEND_CUSTOMER_TEXT_ERROR = -4;
    const STATUS_RESTAURANT_CONFIRM_CALL_CANCEL_ORDER = -3;
    const STATUS_SEND_RESTAURANT_CONFIRM_CALL_ERROR = -2;
    const STATUS_SEND_FAX_ERROR  = -1;
    const STATUS_SEND_RESTAURANT_FAX = 0;
    const STATUS_SEND_RESTAURANT_CONFIRM_CALL = 1;
    const STATUS_SEND_CUSTOMER_TEXT = 2;
    const STATUS_COMPLETE = 3;

}