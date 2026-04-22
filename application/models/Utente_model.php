<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Utente_model extends MY_Model
{
    protected $table = 'utenti';

    public function trova_per_email($email)
    {
        return $this->db
            ->where('email', $email)
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function crea(array $dati)
    {
        return $this->db->insert($this->table, $dati);
    }
}
