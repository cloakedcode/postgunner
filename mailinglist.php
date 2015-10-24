<?php

require 'json';
require 'vendor/autoload.php';
use Mailgun\Mailgun;

$IS_CLI = true;

function config($name) {
    static $config = [];
    if (empty($config)) {
        require 'config.php';
    }
    
    return $config[$name];
}

function confirm_subscription($email, $name) {
    $mg = new Mailgun(config('PRIVATE_API_KEY'));

    if (is_email_valid($email)) {
        # Issue the call to the client.
        $result = $mg->post("lists/${config('MAILINGLIST')}/members", array(
            'address'     => $email,
            'name'        => $name,
            'subscribed'  => true
        ));
        
        if ($result->http_response_code == 200) {
            success("User confirmed");
        } else {
            failure("Could not add user to the mailinglist");
        }
    }
}

function subscribe($email) {
    $mg = new Mailgun(config('PRIVATE_API_KEY'));

    // @TODO: generate link
    $link = "";

    if (is_email_valid($to)) {
        $body = str_replace(config('CONFIRM_BODY'), 'LINK', $link);

        $result = $mg->sendMessage(config('DOMAIN'), array(
            'from'    => config('FROM'),
            'to'      => $email,
            'subject' => config('CONFIRM_SUBJECT'),
            'html'    => "<html>${body}${config('FOOTER')}</html>"
        ));
        
        if ($result->http_response_code == 200) {
            success("Sent confirmation email");
        } else {
            failure("Could not send confirmation email");
        }
    }
}

function is_email_valid($email) {
    $key = config('PUBLIC_API_KEY');

    if (empty($key)) {
        return true;
    }

    $mg = new Mailgun(config('PUBLIC_API_KEY'));

    $result = $mg->get("address/validate", array('address' => $email));
    
    return ($result->http_response_code == 200 && $result->http_response_body->is_valid === true);
}

function respond($success, $msg) {
    global $IS_CLI;

    if ($IS_CLI === true) {
        if (empty($msg) === false) {
            echo $msg;
        }

        if ($success) {
            exit 0;
        } else {
            exit 1;
        }
    } else {
        die(json_encode(array("success" => $success, "message" => $msg)));
    }
}

function success($msg) {
    respond(true, $msg);
}

function failure($msg) {
    respond(false, $msg);
}

subscribe("alan@cloakedcode.com");
