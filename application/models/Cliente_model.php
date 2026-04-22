<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cliente_model extends MY_Model
{
    protected $table = 'clienti';

    public function tutti($solo_attivi = false)
    {
        if ($solo_attivi)
        {
            $this->db->where('attivo', 1);
        }

        return $this->db
            ->order_by('ragione_sociale', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function trova($id)
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
}
