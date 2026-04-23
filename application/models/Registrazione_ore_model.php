<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Registrazione_ore_model extends MY_Model
{
    protected $table = 'registrazioni_ore';

    // I filtri data sono usati in quasi tutti i report, quindi li centralizziamo qui.
    private function applichi_filtri_data($dal = null, $al = null)
    {
        if (! empty($dal))
        {
            $this->db->where('registrazioni_ore.data_lavoro >=', $dal);
        }

        if (! empty($al))
        {
            $this->db->where('registrazioni_ore.data_lavoro <=', $al);
        }
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

    public function aggiorna($id, array $dati)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $dati);
    }

    public function elimina($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    public function per_utente($utente_id, $dal = null, $al = null, $limite = null)
    {
        // Qui portiamo dentro anche commessa e cliente: serve per mostrare righe leggibili in UI.
        $this->db->select('registrazioni_ore.*, commesse.codice as commessa_codice, commesse.attivita as commessa_attivita, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->where('registrazioni_ore.utente_id', (int) $utente_id);
        $this->applichi_filtri_data($dal, $al);

        $query = $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->get($this->table);

        if ($limite !== null)
        {
            return $query->result();
        }

        return $query->result();
    }

    public function per_commessa($commessa_id, $dal = null, $al = null)
    {
        $this->db->select('registrazioni_ore.*, utenti.nome, utenti.cognome, utenti.email, commesse.codice as commessa_codice, commesse.attivita as commessa_attivita, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('utenti', 'utenti.id = registrazioni_ore.utente_id', 'left');
        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);
        $this->applichi_filtri_data($dal, $al);

        return $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function per_commessa_e_utente($commessa_id, $utente_id, $dal = null, $al = null)
    {
        $this->db->select('registrazioni_ore.*, utenti.nome, utenti.cognome, utenti.email, commesse.codice as commessa_codice, commesse.attivita as commessa_attivita, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('utenti', 'utenti.id = registrazioni_ore.utente_id', 'left');
        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);
        $this->db->where('registrazioni_ore.utente_id', (int) $utente_id);
        $this->applichi_filtri_data($dal, $al);

        return $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function per_utente_e_commessa($utente_id, $commessa_id, $dal = null, $al = null)
    {
        $this->db->where('utente_id', (int) $utente_id);
        $this->db->where('commessa_id', (int) $commessa_id);
        $this->applichi_filtri_data($dal, $al);

        return $this->db
            ->order_by('data_lavoro', 'DESC')
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    // I totali sono usati nelle card dei riepiloghi e nei report.
    public function totale_ore_utente($utente_id, $dal = null, $al = null)
    {
        $this->applichi_filtri_data($dal, $al);
        $row = $this->db
            ->select_sum('ore', 'totale_ore')
            ->where('utente_id', (int) $utente_id)
            ->get($this->table)
            ->row();

        return (float) ($row->totale_ore ?? 0);
    }

    public function totale_ore_utente_mese($utente_id, $anno, $mese)
    {
        $row = $this->db
            ->select_sum('ore', 'totale_ore')
            ->where('utente_id', (int) $utente_id)
            ->where('YEAR(data_lavoro) =', (int) $anno, false)
            ->where('MONTH(data_lavoro) =', (int) $mese, false)
            ->get($this->table)
            ->row();

        return (float) ($row->totale_ore ?? 0);
    }

    public function totale_ore_globali($dal = null, $al = null, $commessa_id = null)
    {
        $this->applichi_filtri_data($dal, $al);
        if (! empty($commessa_id))
        {
            $this->db->where('commessa_id', (int) $commessa_id);
        }

        $row = $this->db
            ->select_sum('ore', 'totale_ore')
            ->get($this->table)
            ->row();

        return (float) ($row->totale_ore ?? 0);
    }

    public function totale_ore_globali_mese($anno, $mese)
    {
        $row = $this->db
            ->select_sum('ore', 'totale_ore')
            ->where('YEAR(data_lavoro) =', (int) $anno, false)
            ->where('MONTH(data_lavoro) =', (int) $mese, false)
            ->get($this->table)
            ->row();

        return (float) ($row->totale_ore ?? 0);
    }

    // I riepiloghi raggruppano le ore con SUM e GROUP BY per ottenere report sintetici.
    public function riepilogo_ore_per_commessa_utente($utente_id, $dal = null, $al = null, $commessa_id = null)
    {
        $this->applichi_filtri_data($dal, $al);
        if (! empty($commessa_id))
        {
            $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);
        }

        return $this->db
            ->select('commesse.id, commesse.codice, commesse.attivita, commesse.nome, clienti.ragione_sociale as cliente_ragione_sociale, SUM(registrazioni_ore.ore) as totale_ore', false)
            ->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left')
            ->join('clienti', 'clienti.id = commesse.cliente_id', 'left')
            ->where('registrazioni_ore.utente_id', (int) $utente_id)
            ->group_by('commesse.id')
            ->order_by('totale_ore', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function riepilogo_ore_per_utente($dal = null, $al = null, $commessa_id = null)
    {
        $this->applichi_filtri_data($dal, $al);
        if (! empty($commessa_id))
        {
            $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);
        }

        return $this->db
            ->select('utenti.id, utenti.nome, utenti.cognome, utenti.email, utenti.ruolo, SUM(registrazioni_ore.ore) as totale_ore', false)
            ->join('utenti', 'utenti.id = registrazioni_ore.utente_id', 'left')
            ->group_by('utenti.id')
            ->order_by('totale_ore', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function riepilogo_ore_per_commessa_globale($dal = null, $al = null)
    {
        $this->applichi_filtri_data($dal, $al);
        return $this->db
            ->select('commesse.id, commesse.codice, commesse.attivita, commesse.nome, clienti.ragione_sociale as cliente_ragione_sociale, SUM(registrazioni_ore.ore) as totale_ore', false)
            ->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left')
            ->join('clienti', 'clienti.id = commesse.cliente_id', 'left')
            ->group_by('commesse.id')
            ->order_by('totale_ore', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function ultime_registrazioni_utente($utente_id, $limite = 10, $dal = null, $al = null)
    {
        $this->db->select('registrazioni_ore.*, commesse.codice as commessa_codice, commesse.attivita as commessa_attivita, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale');
        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->where('registrazioni_ore.utente_id', (int) $utente_id);
        $this->applichi_filtri_data($dal, $al);

        return $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->limit((int) $limite)
            ->get($this->table)
            ->result();
    }

    public function ultime_registrazioni_globali($limite = 10, $dal = null, $al = null, $commessa_id = null)
    {
        $this->db->select('registrazioni_ore.*, commesse.codice as commessa_codice, commesse.attivita as commessa_attivita, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale, utenti.nome as utente_nome, utenti.cognome as utente_cognome');
        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        $this->db->join('utenti', 'utenti.id = registrazioni_ore.utente_id', 'left');
        $this->applichi_filtri_data($dal, $al);
        if (! empty($commessa_id))
        {
            $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);
        }

        return $this->db
            ->order_by('registrazioni_ore.data_lavoro', 'DESC')
            ->order_by('registrazioni_ore.id', 'DESC')
            ->limit((int) $limite)
            ->get($this->table)
            ->result();
    }

    public function registrazioni_filtrate($filtro = array())
    {
        $tipo = $filtro['tipo'] ?? 'globale';
        $utente_id = $filtro['utente_id'] ?? null;
        $commessa_id = $filtro['commessa_id'] ?? null;
        $dal = $filtro['dal'] ?? null;
        $al = $filtro['al'] ?? null;
        $limite = $filtro['limite'] ?? null;
        $con_utenti = $tipo === 'globale';

        $this->db->select('registrazioni_ore.*, commesse.codice as commessa_codice, commesse.attivita as commessa_attivita, commesse.nome as commessa_nome, clienti.ragione_sociale as cliente_ragione_sociale');
        if ($con_utenti)
        {
            $this->db->select('utenti.nome as utente_nome, utenti.cognome as utente_cognome, utenti.email as utente_email');
        }

        $this->db->join('commesse', 'commesse.id = registrazioni_ore.commessa_id', 'left');
        $this->db->join('clienti', 'clienti.id = commesse.cliente_id', 'left');
        if ($con_utenti)
        {
            $this->db->join('utenti', 'utenti.id = registrazioni_ore.utente_id', 'left');
        }

        if (! empty($utente_id))
        {
            $this->db->where('registrazioni_ore.utente_id', (int) $utente_id);
        }

        if (! empty($commessa_id))
        {
            $this->db->where('registrazioni_ore.commessa_id', (int) $commessa_id);
        }

        $this->applichi_filtri_data($dal, $al);

        $this->db->order_by('registrazioni_ore.data_lavoro', 'DESC');
        $this->db->order_by('registrazioni_ore.id', 'DESC');

        if ($limite !== null)
        {
            $this->db->limit((int) $limite);
        }

        return $this->db->get($this->table)->result();
    }

    public function totale_filtrato($filtro = array())
    {
        $this->db->select_sum('ore', 'totale_ore');
        $this->db->from($this->table);

        if (! empty($filtro['utente_id']))
        {
            $this->db->where('utente_id', (int) $filtro['utente_id']);
        }

        if (! empty($filtro['commessa_id']))
        {
            $this->db->where('commessa_id', (int) $filtro['commessa_id']);
        }

        $this->applichi_filtri_data($filtro['dal'] ?? null, $filtro['al'] ?? null);

        $row = $this->db->get()->row();
        return (float) ($row->totale_ore ?? 0);
    }
}
