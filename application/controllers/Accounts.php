<?php
//Purpose: Controller for Accounts.

defined('BASEPATH') or exit('No direct script access allowed');

class Accounts extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('users_model');
    $this->load->model('accounts_model');
  }

  public function new_account() {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      if ($this->input->is_ajax_request()) {

        $name = strip_tags(trim($this->input->post('name')));
        $type = strip_tags(trim($this->input->post('type')));
        $interest = strip_tags(trim($this->input->post('interest')));

        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-ZÀ-ſ ]/", '', $name);

        if (!empty($name) && !empty($type) && !empty($interest)) {

          switch ($type) {
            case 'checking':
              $type = 1;
              break;
            case 'credit':
              $type = 2;
              break;
            case 'savings':
              $type = 3;
              break;
          }

          $data = array(
            'user_id' => (int) $this->session->userdata('user_id'),
            'type' => $type,
            'name' => $name,
            'interest_rate' => $interest * 100
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

  public function close_account($account_id = NULL) {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      if ($this->accounts_model->close_account($account_id)) {
        redirect('/dashboard');
      }
    }
  }

  public function view($account_id = NULL) {
    $account = $this->accounts_model->get_account($account_id);
    $user_account_match = $this->accounts_model->match_user_account($account_id);
    if (empty($account_id) || !$user_account_match) {
      show_404();
    }

    // check when last interest was calculated
    $tz = new DateTimeZone("CST");
    $current_date = new DateTime('NOW', $tz);
    $last_interest = new DateTime($account['last_interest'], $tz);
    $last_y = $last_interest->format('Y');
    $last_m = $last_interest->format('n');
    $next_interest = new DateTime;
    $next_interest->setTimezone($tz);
    $next_interest->setDate($last_y, $last_m + 1, 1);

    // $mock_date = new DateTime('2022-04-10');
    while ($current_date > $next_interest) {
      // add interest
      $account = $this->accounts_model->get_account($account_id);
      $bal = $account['balance'];
      $rate = $account['interest_rate'] / 10000 / 12;
      $interest_amount = (int) ($bal * $rate);
      if ($interest_amount > 0) {
        $bal_after = $bal + $interest_amount;
        $data = array(
          'account_id' => $account_id,
          'date_time' => $next_interest->format('Y-m-d H:i:s'),
          'trans_type_code' => '3',
          'name' => 'Interest',
          'trans_amount' => (int) $interest_amount,
          'balance_after' => $bal_after
        );
        $this->accounts_model->create_transaction($data);

        $interest_date_data = array(
          'account_id' => $account_id,
          'last_interest' => $next_interest->format('Y-m-d H:i:s')
        );
        $this->accounts_model->update_interest_date($interest_date_data);

        $next_interest->add(new DateInterval('P1M'));
      } else {
        $data = array(
          'account_id' => $account_id,
          'last_interest' => $next_interest->format('Y-m-d H:i:s')
        );
        $this->accounts_model->update_interest_date($data);
        $next_interest->add(new DateInterval('P1M'));
      }
    }
    $account = $this->accounts_model->get_account($account_id);

    $headerdata['pagetitle'] = $account['name'] . ' Account at Kids\' Bank';
    $bodydata['name'] = $account['name'];

    $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
    $bodydata['balance'] = numfmt_format_currency($fmt, $account['balance'] / 100, "USD");

    $bodydata['account_id'] = $account['id'];
    $created = new DateTime($account['created_date']);
    $bodydata['created'] = $created->format('D, M j, Y');
    $bodydata['rate'] = (float) $account['interest_rate'] / 100;
    $bodydata['owner'] = $this->users_model->get_name();

    switch ($account['type']) {
      case '1':
        $bodydata['type'] = 'Checking';
        $bodydata['buttons'][0] = array(
          'href' => '/account/' . $account['id'] . '/newtransaction',
          'elem_id' => 'newTransactionBtn',
          'elem_label' => '+ New Transaction'
        );
        break;
      case '2':
        $bodydata['type'] = 'Credit Card';
        $bodydata['buttons'][0] = array(
          'href' => '/account/' . $account['id'] . '/newpurchase',
          'elem_id' => 'newPurchaseBtn',
          'elem_label' => '+ New Purchase'
        );
        $bodydata['buttons'][1] = array(
          'href' => '/account/' . $account['id'] . '/newpayment',
          'elem_id' => 'newPaymentBtn',
          'elem_label' => 'Pay Your Bill'
        );
        break;
      case '3':
        $bodydata['type'] = 'Savings';
        $bodydata['buttons'][0] = array(
          'href' => '/account/' . $account['id'] . '/newtransaction',
          'elem_id' => 'newTransactionBtn',
          'elem_label' => '+ New Transaction'
        );
        break;
    }

    $transactions = [];
    for ($i = 0; $i < count($account['transactions']); $i++) {
      foreach ($account['transactions'][$i] as $key => $value) {
        if ($key == 'trans_type_code') {
          if ($account['type'] == '2') {
            switch ($value) {
              case '1':
                $transactions[$i]['type'] = 'Purchase';
                break;
              case '2':
                $transactions[$i]['type'] = 'Payment';
                break;
              case '3':
                $transactions[$i]['type'] = 'Interest';
                break;
            }
          } else {
            switch ($value) {
              case '1':
                $transactions[$i]['type'] = 'Deposit';
                break;
              case '2':
                $transactions[$i]['type'] = 'Withdrawal';
                break;
              case '3':
                $transactions[$i]['type'] = 'Interest';
                break;
            }
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
          $transactions[$i]['year'] = $date->format('Y');
        }
        if ($key == 'balance_after') {
          $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
          $transactions[$i]['balance_after'] = numfmt_format_currency($fmt, $value / 100, "USD");
        }
      }
    }

    $reversed_transactions = array_reverse($transactions);
    $bodydata['transactions'] = $reversed_transactions;

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
