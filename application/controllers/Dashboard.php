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

      $user_accounts_db = $this->accounts_model->get_accounts();
      $accounts = [];
      $total = 0;
      for ($i = 0; $i < count($user_accounts_db); $i++) {
        if ($user_accounts_db[$i]['status'] === '1') {
          foreach ($user_accounts_db[$i] as $key => $value) {
            if ($key == 'type') {
              switch ($value) {
                case '1':
                  $accounts[$i]['type'] = 'Checking';
                  break;
                case '2':
                  $accounts[$i]['type'] = 'Credit Card';
                  break;
                case '3':
                  $accounts[$i]['type'] = 'Savings';
                  break;
              }
            }
            if ($key == 'name') {
              $accounts[$i]['name'] = $value;
            }
            if ($key == 'id') {
              $accounts[$i]['account_id'] = $value;
            }
            if ($key == 'balance') {
              $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
              $accounts[$i]['balance'] = numfmt_format_currency($fmt, $value / 100, "USD");
            }
            if ($key == 'created_date') {
              $date = new DateTime($value);
              $accounts[$i]['created_date'] = $date->format('F j, Y');
            }
          }
          if ($user_accounts_db[$i]['balance']) {
            $dollars = $user_accounts_db[$i]['balance'] / 100;
            $total += $dollars;
          }
        }
      }
      $bodydata['accounts'] = $accounts;

      $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
      $bodydata['total'] = numfmt_format_currency($fmt, (float) $total, "USD");

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
      $this->parser->parse('pages/dashboard', $bodydata);
      $this->parser->parse('templates/footer', $footerdata);
    }
  }
}
