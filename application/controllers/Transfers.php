<?php
//Purpose: Controller for Transfers.

defined('BASEPATH') or exit('No direct script access allowed');

class Transfers extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('users_model');
    $this->load->model('accounts_model');
  }


  public function new_transfer($account_id = NULL) {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      $accounts_db = $this->accounts_model->get_accounts();
      if (empty($accounts_db)) {
        // you don't seem to have any open bank accounts
        // terminate
      }

      $headerdata['pagetitle'] = 'New Transfer - Kids\' Bank';

      $accounts = [];
      for ($i = 0; $i < count($accounts_db); $i++) {
        if ($accounts_db[$i]['status'] === '1') {
          foreach ($accounts_db[$i] as $key => $value) {
            if ($accounts_db[$i]['type'] == '1' || $accounts_db[$i]['type'] == '3') {
              if ($key == 'id') {
                $accounts[$i]['account_id'] = $value;
              }
              if ($key == 'name') {
                $accounts[$i]['account_name'] = $value;
              }
              if ($key == 'balance') {
                $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
                $accounts[$i]['account_balance'] = numfmt_format_currency($fmt, $value / 100, "USD");
              }
            }
          }
        }
      }
      $bodydata['accounts'] = $accounts;

      $footerdata = array(
        'localtime' => date('Y'),
        'pagename' => 'Kid\'s Bank',
        'scripts' => array(
          array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
          array('script' => './../../../assets/js/bootstrap.min.js'),
          array('script' => './../../../assets/js/main.js'),
          array('script' => './../../../assets/js/transfers.js')
        )
      );

      $this->load->library('parser');
      $this->load->view('templates/header', $headerdata);
      $this->parser->parse('pages/newtransfer', $bodydata);
      $this->parser->parse('templates/footer', $footerdata);
    }
  }

  public function add_transfer() {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      if ($this->input->is_ajax_request()) {

        $account_from = strip_tags(trim($this->input->post('accountFrom')));
        $account_to = strip_tags(trim($this->input->post('accountTo')));

        $amount = strip_tags(trim($this->input->post('amount')));
        $amount = (int) ($amount * 100);

        $name = strip_tags(trim($this->input->post('name')));
        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-ZÀ-ſ0-9 ]/", '', $name);

        if (!empty($account_from) && !empty($account_to) && !empty($amount) && !empty($name)) {
          $from_valid = $this->accounts_model->match_user_account($account_from);
          $to_valid = $this->accounts_model->match_user_account($account_to);

          if ($from_valid && $to_valid) {
            if ($amount  > 0) {

              $from_acc_data = $this->accounts_model->get_account($account_from);
              $from_balance = (int) $from_acc_data['balance'];
              if ($from_balance >= $amount) {

                $to_acc_data = $this->accounts_model->get_account($account_to);
                $to_balance = (int) $to_acc_data['balance'];

                $from_new_balance = $from_balance - $amount;
                $from_data = array(
                  'account_id' => $account_from,
                  'trans_type_code' => 2,
                  'name' => $name,
                  'trans_amount' => $amount,
                  'balance_after' => $from_new_balance
                );

                $to_new_balance = $to_balance + $amount;
                $to_data = array(
                  'account_id' => $account_to,
                  'trans_type_code' => 1,
                  'name' => $name,
                  'trans_amount' => $amount,
                  'balance_after' => $to_new_balance
                );

                $from_response = $this->accounts_model->create_transaction($from_data);
                $to_response = $this->accounts_model->create_transaction($to_data);

                if ($from_response && $to_response) {
                  echo "okay"; //created
                } else {
                  echo "";
                }
              } else {
                echo "1";
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
    }
  }
}
