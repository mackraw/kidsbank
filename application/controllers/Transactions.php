<?php
//Purpose: Controller for Transactions.

defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('users_model');
    $this->load->model('accounts_model');
  }

  public function new_transaction($account_id = NULL) {
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

        $account_id = strip_tags(trim($this->input->post('account')));
        $type = strip_tags(trim($this->input->post('type')));

        $amount = strip_tags(trim($this->input->post('amount')));
        $amount = (int) ($amount * 100);

        $name = strip_tags(trim($this->input->post('name')));
        $name = substr($name, 0, 64);
        $name = preg_replace("/[^a-zA-Z0-9 ]/", '', $name);

        if ($amount > 0) {

          if ($this->accounts_model->match_user_account($account_id)) {

            if (!empty($name) && !empty($type)) {

              $account = $this->accounts_model->get_account($account_id);
              $current_balance = (int) $account['balance'];

              if ($type == 'deposit' || $amount <= $current_balance) {

                switch ($type) {
                  case 'deposit':
                    $type = 1;
                    $new_balance = $current_balance + $amount;
                    break;
                  case 'withdrawal':
                    $type = 2;
                    $new_balance = $current_balance - $amount;
                    break;
                }

                $data = array(
                  'account_id' => $account_id,
                  'trans_type_code' => $type,
                  'name' => $name,
                  'trans_amount' => $amount,
                  'balance_after' => $new_balance
                );

                $response = $this->accounts_model->create_transaction($data);

                if ($response) {
                  echo "okay";
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
        }
      }
    }
  }
}
