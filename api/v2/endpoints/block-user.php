<?php
// +------------------------------------------------------------------------+
// | @author Onkar Yaglewad (Sparck Tech Pvt. Ltd.)
// | @author_url 1: https://www.drip.sparck.tk/yaglewad_onkar
// | @author_url 2: https://sparck.tk/founder
// | @author_email: founder@sparck.tk  
// +------------------------------------------------------------------------+
// | Drip Limitless - Connecting Mankind Together
// | Copyright (c) 2021-22 Sparck Tech Pvt. Ltd. All rights reserved.
// +------------------------------------------------------------------------+
$response_data = array(
    'api_status' => 200,
    'block_status' => 'invalid'
);
if (empty($_POST['user_id'])) {
    $error_code    = 3;
    $error_message = 'user_id (POST) is missing';
}
if (empty($_POST['block_action'])) {
    $error_code    = 4;
    $error_message = 'block_action (POST) is missing';
} else {
    if (!in_array($_POST['block_action'], array('block','un-block'))) {
        $error_code    = 5;
        $error_message = 'Incorrect value for (block_action), allowed: block|un-block';
    }
}
if (empty($error_code)) {
    $recipient_id   = Wo_Secure($_POST['user_id']);
    $recipient_data = Wo_UserData($recipient_id);
    if (empty($recipient_data)) {
        $error_code    = 6;
        $error_message = 'Recipient user not found';
    } else {
        $is_blocked = Wo_IsBlocked($recipient_id);
        if ($_POST['block_action'] == 'block' && $is_blocked === false && Wo_IsAdmin($recipient_id) === false) {
            $block = Wo_RegisterBlock($recipient_id);
            if ($block) {
                $response_data = array(
                    'api_status' => 200,
                    'block_status' => 'blocked'
                );
            }
        } else if ($_POST['block_action'] == 'un-block' && $is_blocked === true) {
            $unblock = Wo_RemoveBlock($recipient_id);
            if ($unblock) {
                $response_data = array(
                    'api_status' => 200,
                    'block_status' => 'un-blocked'
                );
            }
        }
    }
}