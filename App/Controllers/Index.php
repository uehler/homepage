<?php

namespace App\Controllers;

use App\Components\Controller;

class Index extends Controller
{
    public function index()
    {
        if (!empty($_POST)) {
            $formData['name'] = trim(strip_tags($_POST['name']));
            $formData['email'] = trim(strip_tags($_POST['email']));
            $formData['content'] = trim(strip_tags($_POST['content']));

            if (empty($formData['name'])) {
                $error['name'] = true;
            }

            if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $error['email'] = true;
            }

            if (empty($formData['content'])) {
                $error['content'] = true;
            }

            if ((!empty($_SESSION['mailSent']) && $_SESSION['mailSent'] === true && $_SESSION['mailSentTime'] > time())
                || (!empty($_COOKIE['mailSent']) && $_COOKIE['mailSent'] === true)
            ) {
                $error['mailSent'] = true;
            }

            if (empty($error)) {
                mail('m@uli.io', 'neue Anfrage via uli.io', $formData['content'], 'From: ' . $formData['name'] . ' <' . $formData['email'] . '>');
                $mailSentTime = time() + 300;
                $_SESSION['mailSent'] = true;
                $_SESSION['mailSentTime'] = $mailSentTime;
                setcookie('mailSent', true, $mailSentTime);
                $success = true;
            } else {
                $success = false;
            }
        }

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

        if (!empty($success) && $success === true) {
            $htmlsuccess = '<p class="success">Thank you for your email. I will answer it as soon as possible.</p>';
        } else {
            $htmlsuccess = '';
        }


        $birthday = new \DateTime('1991-10-02');
        $today = new \DateTime('today');
        $age = $birthday->diff($today)->y;

        $this->view->addTemplate('index.html');
        $this->view->assign('age', $age);

        $this->view->assign('error', $htmlerrors);
        $this->view->assign('success', $htmlsuccess);

        foreach (array('name', 'email', 'content') as $item) {
            $this->view->assign($item, isset($formData[$item]) ? $formData[$item] : '');
            $this->view->assign($item . 'Error', isset($error[$item]) ? 'error' : '');
        }
    }
}