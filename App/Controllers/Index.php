<?php

namespace App\Controllers;

use App\Components\Controller;

class Index extends Controller
{
    public function index()
    {
        $formFields = array('name', 'email', 'content');
        $htmlerrors = '';
        $htmlsuccess = '';

        if (!empty($_POST)) {
            $formData = array();
            foreach ($formFields as $item) {
                $formData[$item] = trim(strip_tags($_POST[$item]));
                if (empty($formData[$item])
                    || ($item == 'email' && !filter_var($formData[$item], FILTER_VALIDATE_EMAIL))
                ) {
                    $error[$item] = true;
                }
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
                $htmlsuccess = '<p class="success">Thank you for your email. I will answer it as soon as possible.</p>';
            } else {
                $htmlerrors = $this->getErrorMessages($error);
            }
        }


        $this->view->addTemplate('index.html');
        $this->view->assign('age', $this->getAge());

        $this->view->assign('error', $htmlerrors);
        $this->view->assign('success', $htmlsuccess);

        foreach ($formFields as $item) {
            $this->view->assign($item, isset($formData[$item]) ? $formData[$item] : '');
            $this->view->assign($item . 'Error', isset($error[$item]) ? 'error' : '');
        }
    }


    private function getErrorMessages($errors)
    {
        $errors = array_keys($errors);
        $htmlerrors = '<p class="error">';
        foreach ($errors as $error) {
            switch ($error) {
                case 'name':
                    $htmlerrors .= '- please tell me your name<br>';
                    break;
                case 'email':
                    $htmlerrors .= '- please enter a valid email adress<br>';
                    break;
                case 'content':
                    $htmlerrors .= '- please tell something about you<br>';
                    break;
                case 'mailSent':
                    $htmlerrors .= '- you just sent me an email, please wait a few minutes<br>';
                    break;
            }
        }
        $htmlerrors .= '</p>';

        return $htmlerrors;
    }


    private function getAge()
    {
        $birthday = new \DateTime('1991-10-02');
        $today = new \DateTime('today');

        return $birthday->diff($today)->y;
    }
}