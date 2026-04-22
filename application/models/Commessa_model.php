<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Commessa_model extends MY_Model
{
    protected $table = 'commesse';

    public function tutte($solo_attive = true)
    {
        $this->db->select('commesse.*, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');

        if ($solo_attive)
        {
            $this->db->where('commesse.attivo', 1);
        }

        return $this->db
            ->order_by('commesse.codice', 'ASC')
            ->order_by('commesse.nome', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function tutte_con_cliente()
    {
        return $this->tutte(false);
    }

    public function trova($id)
    {
        $this->db->select('commesse.*, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');

        return $this->db
            ->where('commesse.id', (int) $id)
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function crea(array $dati)
    {
        return $this->db->insert($this->table, $dati);
    }
}
