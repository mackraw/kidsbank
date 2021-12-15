<?php
// User access model.

defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model {

  public function __construct() {
    $this->load->database();
  }

  public function create_user($data) {
    $this->db->where('email', $data['email']);
    $query = $this->db->get('users');
    if ($query->num_rows()) {
      return false;
    } else {
      return $this->db->insert('users', $data);
    }
  }

  public function login($email, $password) {
    $this->db->where('email', $email);
    $query = $this->db->get('users');
    $db_password = $query->row()->password;
    if (password_verify($password, $db_password)) {
      return $query->row(0)->id;
    } else {
      return false;
    }
  }

  public function get_name() {
    $user_id = $this->session->userdata('user_id');
    $this->db->where('id', $user_id);
    $query = $this->db->get('users');
    return $query->row()->name;
  }
}
