<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends MY_Model
{
    protected $table = 'users';

    public function get_all()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }


}
