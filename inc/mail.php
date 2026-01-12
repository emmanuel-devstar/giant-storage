<?php

use PSpell\Config;

class Mail
{
    public $success;
    public $error;
    public $email_firm;
    public $delivery_emails;

    public $user_email;
    public $reply_to_user;
    public $subject;
    public $email_footer;

    public $data = array();

    public function __construct()
    {
        $this->email_firm = Configuration::$email;
        $this->delivery_emails = Configuration::$delivery_emails;
    }

    public function get_data()
    {

        foreach ($_POST as $key => $value) {


            $key = strtolower($key);
            if (((strpos($key, "email")) !== false) && ($key !== "recipient_email")) {
                $this->user_email = $value;
            }

            if ($key === "recipient_email") {
                if ($value) {
                    $this->delivery_emails = base64_decode($value);
                }
            }

            if ($key === "reply_to_user") {
                $this->reply_to_user = strtolower($value);
            }

            if ($value)
                if (is_string($value)) {
                    $this->data[$key] = htmlspecialchars($value);
                } else {
                    $this->data[$key] = $value;
                }
        }
    }

    public function clean_data()
    {
        empty($this->data);
    }

    public function check_fields()
    {

        $resp = true;

        if (!filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) {
            $resp = false;
            $this->error = 'The e-mail address entered is invalid.';
        }

        if (!$this->email_firm) {
            $resp = false;
            $this->error = "There was an error trying to send your message. Please try again later.";
        }

        if (!$this->delivery_emails) {
            $resp = false;
            $this->error = "There was an error trying to send your message. Please try again later.";
        }

        if (empty($this->data["g-recaptcha-response"])) {
            $resp = false;
            $this->error = 'Please, fill the captcha to send the message.';
        } else {
            $verifyResponse = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . Configuration::$rc_secret . '&response=' . $this->data["g-recaptcha-response"]));
            if (!$verifyResponse->success || $verifyResponse->score < 0.5) {
                $resp = false;
                $this->error = 'Please, fill the captcha to send the message.';
            }
        }

        return $resp;
    }


    public function send()
    {

        $body = "";

        $skip_fields = array("permissions", "g-recaptcha-response", "action", "title", "recipient_email", "reply_to_user", "service0", "service1", "service2", "service3");
        foreach ($this->data as $key => $value) {

            if (!in_array($key, $skip_fields)) {
                $name = ucfirst(str_replace('_', ' ', $key));

                $body  .= '<b>' . $name . ':</b><br> ' . $value . "<br><br>";
            }

            if ($key === "service0") {
                $body  .= '<b>Services intrested in:</b><br>';
            }

            if (preg_match("/service/", $key)) {
                $body .= $value . "<br><br>";
            }
        }

        $body .= $this->email_footer;

        $headers = 'From:' . $this->subject . '<' . $this->email_firm . ">\r\n";
        if ($this->reply_to_user !== "no") {
            $headers .= 'Reply-To: ' . htmlspecialchars($this->user_email) . "\r\n";
        }
        $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

        $this->success = wp_mail($this->delivery_emails, $this->subject, $body, $headers);
        //$this->success = true;

        if (!$this->success) {
            $this->error = "There was an error trying to send your message. Please try again later.";
        }

        return $this->success;
    }
}

add_action('wp_ajax_send_ajax', 'send_ajax');
add_action('wp_ajax_nopriv_send_ajax', 'send_ajax');

function send_ajax()
{
    $mail = new Mail();
    $mail->get_data();

    $mail->subject = Configuration::$company_name . ' - ' . $mail->data["title"];
    $mail->email_footer = "Email was sent from : <a href='" . home_url() . "'>" . Configuration::$company_name . " - " . $mail->data["title"] . "</a>.";


    if ($mail->check_fields()) {
        $mail->send();
    }

    if ($mail->success) {

        $mail->clean_data();

        echo "ok";
    } else {
        echo $mail->error;
    }
    exit;
}

add_action('phpmailer_init', function ($phpmailer) {
    if (!Configuration::$phpmailer["disable"]) {
        $phpmailer->isSMTP();
        $phpmailer->Host = Configuration::$phpmailer["host"];
        $phpmailer->SMTPAuth = Configuration::$phpmailer["smtpauth"];
        $phpmailer->Port = (int) Configuration::$phpmailer["port"];
        $phpmailer->Username = Configuration::$phpmailer["username"];
        $phpmailer->Password = Configuration::$phpmailer["password"];
        $phpmailer->SMTPSecure = Configuration::$phpmailer["smtpsecure"];
        $phpmailer->From = Configuration::$phpmailer["from"];
        $phpmailer->FromName = Configuration::$phpmailer["fromname"];
    }
});
