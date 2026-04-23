<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reporti extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Registrazione_ore_model');
        $this->load->model('Utente_model');
        $this->load->model('Commessa_model');
    }

    public function index()
    {
        $this->richiedi_login();

        // Il report principale è sempre personale, anche per admin e superadmin.
        $filtri = $this->leggi_filtri_report(true, false);
        $commesse = $this->Commessa_model->tutte_con_cliente();
        $utente_id = $this->session->userdata('utente_id');

        $filtro_query = array(
            'dal' => $filtri['dal'],
            'al' => $filtri['al'],
            'utente_id' => $utente_id,
            'commessa_id' => $filtri['commessa_id'],
        );

        $riepilogo_commesse = $this->Registrazione_ore_model->riepilogo_ore_per_commessa_utente(
            $utente_id,
            $filtri['dal'],
            $filtri['al'],
            $filtri['commessa_id']
        );

        $data = array(
            'titolo' => 'I miei report',
            'globale' => false,
            'filtri' => $filtri,
            'commesse' => $commesse,
            'totale_ore' => $this->Registrazione_ore_model->totale_filtrato($filtro_query),
            'totale_registrazioni' => count($this->Registrazione_ore_model->registrazioni_filtrate(array_merge($filtro_query, array('tipo' => 'personale')))),
            'riepilogo_commesse' => $riepilogo_commesse,
            'grafico_commesse' => $this->prepara_grafico_commesse($riepilogo_commesse),
            'grafico_giorni' => $this->prepara_grafico_giorni(
                $this->Registrazione_ore_model->riepilogo_ore_per_giorno($filtri['dal'], $filtri['al'], $utente_id, $filtri['commessa_id'])
            ),
            'ultime_registrazioni' => $this->Registrazione_ore_model->registrazioni_filtrate(array(
                'tipo' => 'personale',
                'utente_id' => $utente_id,
                'dal' => $filtri['dal'],
                'al' => $filtri['al'],
                'commessa_id' => $filtri['commessa_id'],
                'limite' => 12,
            )),
        );

        $this->load->view('reporti/index', $data);
    }

    public function utenti()
    {
        $this->richiedi_admin();
        $filtri = $this->leggi_filtri_report(false, true);
        $commesse = $this->Commessa_model->tutte_con_cliente();
        $filtro_query = array(
            'dal' => $filtri['dal'],
            'al' => $filtri['al'],
            'commessa_id' => $filtri['commessa_id'],
        );

        $data = array(
            'titolo' => 'Report per utenti',
            'globale' => true,
            'filtri' => $filtri,
            'commesse' => $commesse,
            'totale_ore' => $this->Registrazione_ore_model->totale_filtrato($filtro_query),
            'totale_registrazioni' => count($this->Registrazione_ore_model->registrazioni_filtrate(array(
                'tipo' => 'globale',
                'dal' => $filtri['dal'],
                'al' => $filtri['al'],
                'commessa_id' => $filtri['commessa_id'],
            ))),
            'riepilogo_utenti' => $this->Registrazione_ore_model->riepilogo_ore_per_utente($filtri['dal'], $filtri['al'], $filtri['commessa_id']),
        );

        $this->load->view('reporti/utenti', $data);
    }

    public function commesse()
    {
        $this->richiedi_admin();
        $filtri = $this->leggi_filtri_report(false, true);

        $data = array(
            'titolo' => 'Report per commesse',
            'globale' => true,
            'filtri' => $filtri,
            'totale_ore' => $this->Registrazione_ore_model->totale_filtrato(array(
                'dal' => $filtri['dal'],
                'al' => $filtri['al'],
            )),
            'totale_registrazioni' => count($this->Registrazione_ore_model->registrazioni_filtrate(array(
                'tipo' => 'globale',
                'dal' => $filtri['dal'],
                'al' => $filtri['al'],
            ))),
            'riepilogo_commesse' => $this->Registrazione_ore_model->riepilogo_ore_per_commessa_globale($filtri['dal'], $filtri['al']),
        );

        $this->load->view('reporti/commesse', $data);
    }

    private function leggi_filtri_report($default_today = false, $default_last_30_days = false)
    {
        // I filtri usano GET così l'URL racconta sempre il report che si sta guardando.
        $dal = trim((string) $this->input->get('dal', true));
        $al = trim((string) $this->input->get('al', true));
        $commessa_id = trim((string) $this->input->get('commessa_id', true));

        if ($dal === '' && $al === '')
        {
            if ($default_today)
            {
                $dal = date('Y-m-d');
                $al = date('Y-m-d');
            }
            elseif ($default_last_30_days)
            {
                $al = date('Y-m-d');
                $dal = date('Y-m-d', strtotime('-30 days'));
            }
        }

        return array(
            'dal' => $dal ?: null,
            'al' => $al ?: null,
            'commessa_id' => $commessa_id !== '' ? (int) $commessa_id : null,
        );
    }

    private function prepara_grafico_commesse(array $riepilogo_commesse)
    {
        $labels = array();
        $valori = array();

        foreach ($riepilogo_commesse as $riga)
        {
            $label = trim((string) $riga->codice);
            if ($label !== '' && ! empty($riga->attivita))
            {
                $label .= ' - ' . $riga->attivita;
            }
            elseif (! empty($riga->attivita))
            {
                $label = $riga->attivita;
            }

            $labels[] = $label;
            $valori[] = (float) $riga->totale_ore;
        }

        return array(
            'labels' => $labels,
            'values' => $valori,
        );
    }

    private function prepara_grafico_giorni(array $riepilogo_giorni)
    {
        $labels = array();
        $valori = array();

        foreach ($riepilogo_giorni as $riga)
        {
            $labels[] = $riga->data_lavoro;
            $valori[] = (float) $riga->totale_ore;
        }

        return array(
            'labels' => $labels,
            'values' => $valori,
        );
    }
}
