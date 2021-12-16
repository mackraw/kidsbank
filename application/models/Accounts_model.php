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
}
