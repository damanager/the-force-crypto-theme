<?php

function redirect($location, $attr = []) {
    $url = $location;
    
    if (! empty($attr)) {
        $url .= "?" . http_build_query($attr);
    }
    
    header("Location: " . $url);
    exit;
}

if (isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        redirect(
            $_SERVER['HTTP_REFERER'],
            ['message' => 'invalid_email']
        );
    }
    
    file_put_contents('emails-list', $email . PHP_EOL , FILE_APPEND | LOCK_EX);
    
    $message =  "Hello,\r\n\r\n";
    $message .= "A new user has subscribed on the website with the email address provided below.\r\n";
    $message .= "Email address: " . $email . "\r\n\r\n";
    $message .= "Thanks.";
    
    $mail = mail("support@koinkoin.com", "New User Signed Up.", $message);

    if ($mail) {
        redirect("http://da-manager.com/templates/the-force-crypto-theme/success.html");
    }
}

redirect("index.html");