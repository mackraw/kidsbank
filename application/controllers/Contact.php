<?php
// Controller for the contact page.

defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function contact() {
    if ($this->input->is_ajax_request()) {

      $name = strip_tags(trim($this->input->post('name')));
      $email = $this->input->post('email');
      $subject = trim($this->input->post('subject'));
      $message = trim($this->input->post('message'));

      $name = substr($name, 0, 64);
      $name = preg_replace("/[^a-zA-Z ]/", '', $name);

      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      $subject = substr($subject, 0, 64);
      $message = substr($message, 0, 1000);

      if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
        $to = "maciekrawczyk@gmail.com";
        $body = $name . " sent a message from the Kids' Bank website:\n\n" . $message;

        if (mail(
          $to,
          $subject,
          $body,
          $headers,
        )) {
          echo "okay";
        } else {
          echo "";
        }
      } else {
        echo "";
      }
    } else {
      echo "";
    }
  }
}
