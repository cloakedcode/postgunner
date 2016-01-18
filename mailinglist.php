<?php

require 'vendor/autoload.php';
use Mailgun\Mailgun;
use paragonie\random_compat;

if (php_sapi_name() == "cli") {
    define("IS_CLI", true);
} else {
    define("IS_CLI", false);
}

function config($name) {
    static $config = [];
    if (empty($config)) {
        require 'config.php';
    }
    
    return $config[$name];
}

function confirm_subscription($email, $doodle) {
    $mg = new Mailgun(config('PRIVATE_API_KEY'));

    if (is_email_valid($email)) {
        $rand = substr($doodle, 0, 8);
        $hash = substr($doodle, 8);

        if (hash_it($rand . $email) == $hash) {
            $list = config('MAILINGLIST');
            $result = $mg->post("lists/${list}/members", array(
                'address'     => $email,
                'name'        => '',
                'subscribed'  => true
            ));
            
            if ($result->http_response_code == 200) {
                success("User confirmed", config('CONFIRM_REDIRECT'));
            } else {
                failure("Could not add user to the mailinglist");
            }
        } else {
            failure("Invalid confirmation link");
        }
    }
}

function subscribe($email) {
    $mg = new Mailgun(config('PRIVATE_API_KEY'));

    $rand = random_str(8);
    $doodle = $rand . hash_it($rand . $email);
    $link = url(array(
                'm' => 'verify',
                'e' => $email,
                'c' => $doodle
    ));

    if (is_email_valid($email)) {
        $body = str_replace('%link%', $link, config('CONFIRM_BODY'));

        $result = $mg->sendMessage(config('DOMAIN'), array(
            'from'    => config('FROM'),
            'to'      => $email,
            'subject' => config('CONFIRM_SUBJECT'),
            'html'    => $body
        ));
        
        if ($result->http_response_code == 200) {
            success("Sent confirmation email");
        } else {
            failure("Could not send confirmation email");
        }
    }
}

function validate_email($email) {
    (is_email_valid($email)) ? success("Valid email") : failure("Invalid email");
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

function respond($success, $msg, $redirect='') {
    if (IS_CLI === true) {
        if (empty($msg) === false) {
            echo "${msg}\n";
        }

        if ($success) {
            exit(0);
        } else {
            exit(1);
        }
    } else {
        if (empty($redirect)) {
            die(json_encode(array("success" => $success, "message" => $msg)));
        } else {
            header("Location: ${redirect}");
            exit;
        }
    }
}

function success($msg, $redirect='') {
    respond(true, $msg, $redirect);
}

function failure($msg, $redirect='') {
    respond(false, $msg, $redirect);
}

function hash_it($key) {
    return md5($key . config('HASH_SALT'));
}

function url($query_params) {
    $args = "";
    foreach ($query_params as $k => $v) {
        if ($args === "") {
            $args .= "?${k}=${v}";
        } else {
            $args .= "&${k}=${v}";
        }
    }

    return "${_SERVER['PHP_SELF']}${args}";
}

function route($method, $params) {
    switch ($method) {
        case "verify":
            confirm_subscription($params['e'], $params['c']);
            break;
        case "subscribe":
            subscribe($params['e']);
            break;
        case "valid_email":
            validate_email($params['e']);
            break;
        default:
            failure("Method not found");
    }
}

// https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

// the main func
function run() {
    if (IS_CLI === true) {
        $argv = $_SERVER['argv'];
        $m = (count($argv) > 1) ? $argv[1] : '';
        $e = (count($argv) > 2) ? $argv[2] : '';
        $c = (count($argv) > 3) ? $argv[3] : '';

        route($m, array('e' => $e, 'c' => $c));
    } else {
        if (isset($_REQUEST['m'])) {
            $m = $_REQUEST['m'];
            unset($_REQUEST['m']);

            route($m, $_REQUEST);
        }
    }
}

run();
