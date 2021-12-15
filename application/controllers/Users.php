<?php
// Purpose: Controller to manage users.

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
    $this->load->helper('url');
  }

  public function register() {
    if ($this->input->is_ajax_request()) {

      $name = strip_tags(trim($this->input->post('name')));
      $email = strip_tags(trim($this->input->post('email')));
      $password = trim($this->input->post('password'));
      $passconf = trim($this->input->post('passconf'));

      $name = substr($name, 0, 64);
      $name = preg_replace("/[^a-zA-Z ]/", '', $name);

      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      $password = substr($password, 0, 32);
      $passconf = substr($passconf, 0, 32);

      if ($password == $passconf) {
        $hashedpass = password_hash($password, PASSWORD_BCRYPT);

        if (!empty($name) && !empty($email) && !empty($password) && !empty($passconf)) {

          $data = array(
            'name' => $name,
            'email' => $email,
            'password' => $hashedpass
          );

          $response = $this->users_model->create_user($data);

          if ($response) {
            $headers = "From: kidsbank@kidsbank.live\r\n";
            $headers .= "Reply-To: no-reply@kidsbank.live\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $subject = "Verify your registration at the Kids' Bank";
            $body = "Hi " . $name . ",<br><br>Thanks for registering with Kids' Bank. You may now <a href=\"https://kidsbank.live/\">login</a>.<br><br>Regards,<br><br>Kids' Bank Team";

            if (mail(
              $email,
              $subject,
              $body,
              $headers,
            )) {
              echo "okay";
            } else {
              echo "";
            }
          } else {
            echo "userTaken";
          }
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

  public function login() {
    if ($this->input->is_ajax_request()) {
      $email = strip_tags(trim($this->input->post('email')));
      $password = trim($this->input->post('password'));

      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      $password = substr($password, 0, 32);

      $user_id = $this->users_model->login($email, $password);

      if ($user_id) {
        $user_data = array(
          'user_id' => $user_id,
          'email' => $email,
          'logged_in' => true
        );
        $this->session->set_userdata($user_data);

        echo 'okay';
      } else {
        echo '';
      }
    }
  }

  public function logout() {
    $this->session->sess_destroy();
    redirect('/', 'refresh');
  }
}
