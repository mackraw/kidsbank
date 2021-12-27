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
          $total += $dollars;
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

  public function new_account() {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      if ($this->input->is_ajax_request()) {

        $name = strip_tags(trim($this->input->post('name')));
        $type = strip_tags(trim($this->input->post('type')));
        $interest = strip_tags(trim($this->input->post('interest')));

        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-Z ]/", '', $name);

        if (!empty($name) && !empty($type) && !empty($interest)) {

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

  public function view($account_id = NULL) {
    $account = $this->accounts_model->get_account($account_id);
    $user_account_match = $this->accounts_model->match_user_account($account_id);
    if (empty($account_id) || !$user_account_match) {
      show_404();
    }

    // check when last interest was calculated
    $current_date = new DateTime('NOW');
    $last_interest = new DateTime($account['last_interest']);
    $last_y = $last_interest->format('Y');
    $last_m = $last_interest->format('n');
    $next_interest = new DateTime;
    $next_interest->setDate($last_y, $last_m + 1, 1);

    // $mock_date = new DateTime('2022-02-10');
    while ($current_date > $next_interest) {
      // add interest
      $account = $this->accounts_model->get_account($account_id);
      $bal = $account['balance'];
      $rate = $account['interest_rate'] / 10000;
      $interest_amount = $bal * $rate;
      $bal_after = $bal + (int) $interest_amount;
      $data = array(
        'account_id' => $account_id,
        'date_time' => $next_interest->format('Y-m-d H:i:s'),
        'trans_type_code' => '3',
        'name' => 'Interest',
        'trans_amount' => (int) $interest_amount,
        'balance_after' => $bal_after
      );
      $this->accounts_model->create_transaction($data);
      $next_interest->add(new DateInterval('P1M'));
    }

    $account = $this->accounts_model->get_account($account_id);

    $headerdata['pagetitle'] = $account['name'] . ' Account at Kids\' Bank';

    $bodydata['name'] = $account['name'];

    $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
    $bodydata['balance'] = numfmt_format_currency($fmt, $account['balance'] / 100, "USD");

    $bodydata['account_id'] = $account['id'];
    $created = new DateTime($account['created_date']);
    $bodydata['created'] = $created->format('D, M j, Y');
    $bodydata['last_interest'] = $last_interest->format('D, M j, Y');
    $bodydata['rate'] = (float) $account['interest_rate'] / 100;
    $bodydata['owner'] = $this->users_model->get_name();

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
            case '3':
              $transactions[$i]['type'] = 'Interest';
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

  public function new_transaction($account_id = NULL) {
    $account = $this->accounts_model->get_account($account_id);
    $user_account_match = $this->accounts_model->match_user_account($account_id);
    if (empty($account_id) || !$user_account_match) {
      show_404();
    }

    $headerdata['pagetitle'] = 'New Transaction - Kids\' Bank';

    $bodydata['account_id'] = $account_id;

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js'),
        array('script' => './../../../assets/js/main.js'),
        array('script' => './../../../assets/js/transactions.js')
      )
    );

    $this->load->library('parser');
    $this->load->view('templates/header', $headerdata);
    $this->parser->parse('pages/newtransaction', $bodydata);
    $this->parser->parse('templates/footer', $footerdata);
  }

  public function add_transaction() {
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    } else {
      if ($this->input->is_ajax_request()) {

        $name = strip_tags(trim($this->input->post('name')));
        $type = strip_tags(trim($this->input->post('type')));
        $amount = strip_tags(trim($this->input->post('amount')));
        $account_id = strip_tags(trim($this->input->post('account')));

        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-Z ]/", '', $name);

        if ($amount > 0) {

          if ($this->accounts_model->match_user_account($account_id)) {

            if (!empty($name) && !empty($type)) {

              $account = $this->accounts_model->get_account($account_id);
              $current_balance = $account['balance'];

              switch ($type) {
                case 'deposit':
                  $type = 1;
                  $new_balance = $current_balance + $amount * 100;
                  break;
                case 'withdrawal':
                  $type = 2;
                  $new_balance = $current_balance - $amount * 100;
                  break;
              }

              $data = array(
                'account_id' => $account_id,
                'trans_type_code' => $type,
                'name' => $name,
                'trans_amount' => $amount * 100,
                'balance_after' => $new_balance
              );

              $response = $this->accounts_model->create_transaction($data);

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
        foreach ($accounts_db[$i] as $key => $value) {
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
        $date = strip_tags(trim($this->input->post('date')));
        $name = strip_tags(trim($this->input->post('name')));

        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-Z ]/", '', $name);

        if (!empty($account_from) && !empty($account_to) && !empty($amount) && !empty($date) && !empty($name)) {
          $from_valid = $this->accounts_model->match_user_account($account_from);
          $to_valid = $this->accounts_model->match_user_account($account_to);

          if ($from_valid && $to_valid) {
            if ($amount > 0) {
              $now = new DateTime('NOW');
              $hr = $now->format('H');
              $min = $now->format('i');
              $sec = $now->format('s') + 1;
              $fullDate = $date . 'T' . $hr . ':' . $min . ':' . $sec;
              $transfer_date = new DateTime($fullDate);
              if ($transfer_date >= $now) {
                $from_acc_data = $this->accounts_model->get_account($account_from);
                $from_balance = $from_acc_data['balance'];

                if ($from_balance >= $amount) {
                  $to_acc_data = $this->accounts_model->get_account($account_to);
                  $to_balance = $to_acc_data['balance'];

                  $from_new_balance = $from_balance - $amount * 100;
                  $from_data = array(
                    'account_id' => $account_from,
                    'trans_type_code' => 2,
                    'date_time' => $transfer_date->format('Y-m-d H:i:s'),
                    'name' => $name,
                    'trans_amount' => $amount * 100,
                    'balance_after' => $from_new_balance
                  );

                  $to_new_balance = $to_balance + $amount * 100;
                  $to_data = array(
                    'account_id' => $account_to,
                    'trans_type_code' => 1,
                    'date_time' => $transfer_date->format('Y-m-d H:i:s'),
                    'name' => $name,
                    'trans_amount' => $amount * 100,
                    'balance_after' => $to_new_balance
                  );

                  $from_response = $this->accounts_model->create_transaction($from_data);
                  $to_response = $this->accounts_model->create_transaction($to_data);

                  if ($from_response && $to_response) {
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
}
