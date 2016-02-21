<?php
session_start();

if (!empty($_POST)) {
    $name = trim(strip_tags($_POST['name']));
    $email = trim(strip_tags($_POST['email']));
    $content = trim(strip_tags($_POST['content']));

    if (empty($name)) {
        $error['name'] = true;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = true;
    }

    if (empty($content)) {
        $error['content'] = true;
    }

    if (($_SESSION['mailSent'] === true && $_SESSION['mailSentTime'] > time()) || $_COOKIE['mailSent'] === true) {
        $error['mailSent'] = true;
    }

    if (empty($error)) {
        mail('m@uli.io', 'neue Anfrage via uli.io', $content, 'From: ' . $name . ' <' . $email . '>');
        $mailSentTime = time() + 300;
        $_SESSION['mailSent'] = true;
        $_SESSION['mailSentTime'] = $mailSentTime;
        setcookie('mailSent', true, $mailSentTime);
        $success = true;
    } else {
        $success = false;
    }
}

$htmlcontent = file_get_contents('_resources/tpl/content.html');


if (empty($error)) {
    $htmlerrors = '';
} else {
    $htmlerrors = '<p class="error">';
    if (!empty($error['mailSent'])) {
        $htmlerrors .= '- you just sent me an email, please wait a few minutes<br>';
    }
    if (!empty($error['name'])) {
        $htmlerrors .= '- please tell me your name<br>';
    }
    if (!empty($error['email'])) {
        $htmlerrors .= '- please enter a valid email adress<br>';
    }
    if (!empty($error['content'])) {
        $htmlerrors .= '- please tell something about you<br>';
    }
    $htmlerrors .= '</p>';
}

if ($success === true) {
    $htmlsuccess = '<p class="success">Thank you for your email. I will answer it as soon as possible.</p>';
} else {
    $htmlsuccess = '';
}

$htmlMailError = array('name' => '', 'email' => '', 'content' => array('class' => '', 'value' => ''));
if (!empty($error['name'])) {
    $htmlMailError['name'] = 'class="error"';
}
if (!empty($name)) {
    $htmlMailError['name'] .= ' value="' . $name . '"';
}

if (!empty($error['email'])) {
    $htmlMailError['email'] = 'class="error"';
}
if (!empty($email)) {
    $htmlMailError['email'] .= ' value="' . $email . '"';
}

if (!empty($error['content'])) {
    $htmlMailError['content']['class'] = 'class="error"';
}
if (!empty($content)) {
    $htmlMailError['content']['value'] = $content;
}


$htmlcontent = str_replace('$$$error$$$', $htmlerrors, $htmlcontent);
$htmlcontent = str_replace('$$$success$$$', $htmlsuccess, $htmlcontent);
$htmlcontent = str_replace('data-mailerror="name"', $htmlMailError['name'], $htmlcontent);
$htmlcontent = str_replace('data-mailerror="email"', $htmlMailError['email'], $htmlcontent);
$htmlcontent = str_replace('data-mailerror="content"', $htmlMailError['content']['class'], $htmlcontent);
$htmlcontent = str_replace('$$$content$$$', $htmlMailError['content']['value'], $htmlcontent);

echo $htmlcontent;