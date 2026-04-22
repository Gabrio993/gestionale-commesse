<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Registrazione_ore_model extends MY_Model
{
    protected $table = 'registrazioni_ore';

    public function crea(array $dati)
    {
        return $this->db->insert($this->table, $dati);
    }

    public function per_utente($utente_id)
    {
        $this->db->select('registrazioni_ore.*, commesse.codice as commessa_codice, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->where('registrazioni_ore.utente_id', (int) $utente_id);

        return $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function per_commessa($commessa_id)
    {
        $this->db->select('registrazioni_ore.*, utenti.nome, utenti.cognome, utenti.email');
        $this->db->join('utenti', 'utenti.id = registrazioni_ore.utente_id', 'left');
        $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);

        return $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function per_utente_e_commessa($utente_id, $commessa_id)
    {
        $this->db->where('utente_id', (int) $utente_id);
        $this->db->where('commessa_id', (int) $commessa_id);

        return $this->db
            ->order_by('data_lavoro', 'DESC')
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }
}
