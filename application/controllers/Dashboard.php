<?php
//Purpose: Controller for the dashboard page.

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('users_model');
    $this->load->model('accounts_model');
  }

  public function index() {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      $headerdata['pagetitle'] = 'My Dashboard - Kids\' Bank';

      $user_name = $this->users_model->get_name();

      $bodydata['user_name'] = $user_name;

      $footerdata = array(
        'localtime' => date('Y'),
        'pagename' => 'Kid\'s Bank',
        'scripts' => array(
          array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
          array('script' => './../../../assets/js/bootstrap.min.js'),
          array('script' => './../../../assets/js/main.js'),
          array('script' => './../../../assets/js/dashboard.js')
        )
      );

      $this->load->view('templates/header', $headerdata);
      $this->load->view('pages/dashboard', $bodydata);
      $this->parser->parse('templates/footer', $footerdata);
    }
  }

  public function new_account() {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      if ($this->input->is_ajax_request()) {

        $name = strip_tags(trim($this->input->post('name')));
        $type = strip_tags(trim($this->input->post('type')));

        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-Z ]/", '', $name);

        if (!empty($name) && !empty($type)) {

          switch ($type) {
            case 'checking':
              $type = 1;
              break;
            case 'credit':
              $type = 2;
              break;
          }

          $data = array(
            'user_id' => (int) $this->session->userdata('user_id'),
            'type' => $type,
            'name' => $name
          );

          $response = $this->accounts_model->create_account($data);

          if ($response) {
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
}
