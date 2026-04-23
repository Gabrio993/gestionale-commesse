<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Commessa_model extends MY_Model
{
    protected $table = 'commesse';

    // Le query sulle commesse includono quasi sempre il cliente, per questo facciamo la join qui.

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
            ->order_by('commesse.attivita', 'ASC')
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

    public function commesse_assegnate_a_utente($utente_id, $solo_attive = true)
    {
        $this->db->select('commesse.*, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('assegnazioni_commesse', 'assegnazioni_commesse.commessa_id = commesse.id', 'inner');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->where('assegnazioni_commesse.utente_id', (int) $utente_id);

        if ($solo_attive)
        {
            $this->db->where('commesse.attivo', 1);
        }

        return $this->db
            ->order_by('commesse.codice', 'ASC')
            ->order_by('commesse.attivita', 'ASC')
            ->order_by('commesse.nome', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function utente_ha_commessa($utente_id, $commessa_id)
    {
        return (bool) $this->db
            ->where('utente_id', (int) $utente_id)
            ->where('commessa_id', (int) $commessa_id)
            ->limit(1)
            ->get('assegnazioni_commesse')
            ->row();
    }

    public function ids_commesse_assegnate_a_utente($utente_id)
    {
        $rows = $this->db
            ->select('commessa_id')
            ->where('utente_id', (int) $utente_id)
            ->get('assegnazioni_commesse')
            ->result();

        $ids = array();
        foreach ($rows as $row)
        {
            $ids[] = (int) $row->commessa_id;
        }

        return $ids;
    }

    public function sincronizza_assegnazioni_utente($utente_id, array $commessa_ids)
    {
        $utente_id = (int) $utente_id;
        $commessa_ids = array_values(array_unique(array_filter(array_map('intval', $commessa_ids))));

        $this->db->trans_start();
        $this->db->where('utente_id', $utente_id)->delete('assegnazioni_commesse');

        foreach ($commessa_ids as $commessa_id)
        {
            $this->db->insert('assegnazioni_commesse', array(
                'utente_id' => $utente_id,
                'commessa_id' => (int) $commessa_id,
            ));
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
