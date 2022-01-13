<?php
//Purpose: Controller for Credit transactions.

defined('BASEPATH') or exit('No direct script access allowed');

class Credit extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('users_model');
    $this->load->model('accounts_model');
  }

  public function new_purchase($account_id = NULL) {
    $account = $this->accounts_model->get_account($account_id);
    $user_account_match = $this->accounts_model->match_user_account($account_id);
    if (empty($account_id) || !$user_account_match) {
      show_404();
    }

    $headerdata['pagetitle'] = 'New Purchase - Kids\' Bank';

    $bodydata['account_id'] = $account_id;
    $bodydata['account_name'] = $account['name'];

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js'),
        array('script' => './../../../assets/js/main.js'),
        array('script' => './../../../assets/js/credit.js')
      )
    );

    $this->load->library('parser');
    $this->load->view('templates/header', $headerdata);
    $this->parser->parse('pages/newpurchase', $bodydata);
    $this->parser->parse('templates/footer', $footerdata);
  }

  public function new_payment($account_id = NULL) {
    $account = $this->accounts_model->get_account($account_id);
    $user_account_match = $this->accounts_model->match_user_account($account_id);
    if (empty($account_id) || !$user_account_match) {
      show_404();
    }

    $headerdata['pagetitle'] = 'New Payment - Kids\' Bank';

    $bodydata['account_id'] = $account_id;
    $bodydata['account_name'] = $account['name'];

    $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
    $bodydata['account_balance'] = numfmt_format_currency($fmt, $account['balance'] / 100, "USD");

    $accounts_db = $this->accounts_model->get_accounts();
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
              $accounts[$i]['account_balance'] = numfmt_format_currency($fmt, $value / 100, "USD");
            }
          }
        }
      }
    }
    $bodydata['from_accounts'] = $accounts;

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js'),
        array('script' => './../../../assets/js/main.js'),
        array('script' => './../../../assets/js/credit.js')
      )
    );

    $this->load->library('parser');
    $this->load->view('templates/header', $headerdata);
    $this->parser->parse('pages/newpayment', $bodydata);
    $this->parser->parse('templates/footer', $footerdata);
  }

  public function add_payment() {
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

            $account_to_db = $this->accounts_model->get_account($account_to);
            if ($amount  > 0 && $amount <= (int) $account_to_db['balance']) {

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

                $to_new_balance = $to_balance - $amount;
                $to_data = array(
                  'account_id' => $account_to,
                  'trans_type_code' => 2,
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
              echo "2";
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
