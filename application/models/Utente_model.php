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

    public function trova_per_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function crea(array $dati)
    {
        return $this->db->insert($this->table, $dati);
    }

    public function tutti()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function aggiorna_ruolo($utente_id, $ruolo)
    {
        return $this->db
            ->where('id', (int) $utente_id)
            ->update($this->table, array('ruolo' => $ruolo));
    }
}
