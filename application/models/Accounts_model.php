<?php
// Accounts access logic.

defined('BASEPATH') or exit('No direct script access allowed');

class Accounts_model extends CI_Model {

  public function __construct() {
    $this->load->database();
  }

  public function create_account($data) {
    return $this->db->insert('accounts', $data);
  }

  public function get_accounts() {
    $user_id = $this->session->userdata('user_id');
    $this->db->where('user_id', $user_id);
    $query = $this->db->get('accounts');
    return $query->result_array();
  }

  public function get_account($account_id) {
    $user_id = $this->session->userdata('user_id');
    $this->db->where('user_id', $user_id);
    $this->db->where('id', $account_id);
    $query_account = $this->db->get('accounts');
    $result = $query_account->row_array();

    $this->db->where('account_id', $account_id);
    $query_transactions = $this->db->get('transactions');
    $result['transactions'] = $query_transactions->result_array();

    return $result;
  }

  public function match_user_account($account_id) {
    $user_id = $this->session->userdata('user_id');
    $this->db->where('user_id', $user_id);
    $this->db->where('id', $account_id);
    $query_account = $this->db->get('accounts');
    if ($query_account->row_array()) {
      return true;
    } else {
      return false;
    }
  }

  public function create_transaction($data) {
    $this->db->set('balance', $data['balance_after']);
    $this->db->where('id', $data['account_id']);
    $this->db->update('accounts');
    return $this->db->insert('transactions', $data);
  }

  public function schedule_transaction($data) {
    return $this->db->insert('scheduled', $data);
  }

  public function check_scheduled($account_id) {
    $this->db->where('account_id', (int) $account_id);
    $query = $this->db->get('scheduled');
    return $query->result_array();
  }

  public function remove_scheduled_transaction($trans_id) {
    $this->db->where('id', $trans_id);
    return $this->db->delete('scheduled');
  }

  public function close_account($account_id) {
    if ($this->match_user_account($account_id)) {
      $this->db->where('id', $account_id);
      return $this->db->delete('accounts');
    }
  }
}
