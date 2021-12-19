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
        if ($user_accounts_db[$i]['balance']) {
          $dollars = $user_accounts_db[$i]['balance'] / 100;
          $total .= $dollars;
        }
        foreach ($user_accounts_db[$i] as $key => $value) {
          if ($key == 'type') {
            switch ($value) {
              case '1':
                $accounts[$i]['type'] = 'Checking';
                break;
              case '2':
                $accounts[$i]['type'] = 'Credit';
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
      }
      $bodydata['accounts'] = $accounts;

      $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
      $bodydata['total'] = numfmt_format_currency($fmt, $total, "USD");

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

  public function view($account_id = NULL) {
    $account = $this->accounts_model->get_account($account_id);

    if (empty($account_id)) {
      show_404();
    }

    // add check to view only own account

    $headerdata['pagetitle'] = $account['name'] . ' Account at Kids\' Bank';

    $bodydata['name'] = $account['name'];

    $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
    $bodydata['balance'] = numfmt_format_currency($fmt, $account['balance'] / 100, "USD");

    $transactions = [];

    for ($i = 0; $i < count($account['transactions']); $i++) {
      foreach ($account['transactions'][$i] as $key => $value) {
        if ($key == 'trans_type_code') {
          switch ($value) {
            case '1':
              $transactions[$i]['type'] = 'Deposit';
              break;
            case '2':
              $transactions[$i]['type'] = 'Withdrawal';
              break;
          }
        }
        if ($key == 'trans_amount') {
          $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
          $transactions[$i]['amount'] = numfmt_format_currency($fmt, $value / 100, "USD");
        }
        if ($key == 'name') {
          $transactions[$i]['name'] = $value;
        }
        if ($key == 'date_time') {
          $date = new DateTime($value);
          $transactions[$i]['month'] = $date->format('M');
          $transactions[$i]['day'] = $date->format('j');
        }
        if ($key == 'balance_after') {
          $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
          $transactions[$i]['balance_after'] = numfmt_format_currency($fmt, $value / 100, "USD");
        }
      }
    }

    $bodydata['transactions'] = $transactions;

    var_dump($bodydata);

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js')
      )
    );

    $this->load->view('templates/header', $headerdata);
    $this->parser->parse('pages/account', $bodydata);
    $this->parser->parse('templates/footer', $footerdata);
  }
}
