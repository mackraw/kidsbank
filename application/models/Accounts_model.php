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
}
