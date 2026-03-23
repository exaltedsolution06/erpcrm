<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

    public function get_all() {
        return $this->db->get(COMPANY_SETTINGS)->row_array();
    }
}
