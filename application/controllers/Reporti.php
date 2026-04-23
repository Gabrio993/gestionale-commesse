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

        $ruolo = $this->ruolo_utente();
        $filtri = $this->leggi_filtri_report();
        $commesse = $this->Commessa_model->tutte_con_cliente();

        // Il report generale cambia in base al ruolo: gli admin vedono tutto, gli utenti solo i propri dati.
        if (in_array($ruolo, array('admin', 'superadmin'), true))
        {
            $filtro_query = array(
                'dal' => $filtri['dal'],
                'al' => $filtri['al'],
                'commessa_id' => $filtri['commessa_id'],
            );

            $data = array(
                'titolo' => 'Report generali',
                'globale' => true,
                'filtri' => $filtri,
                'commesse' => $commesse,
                'totale_ore' => $this->Registrazione_ore_model->totale_filtrato($filtro_query),
                'totale_registrazioni' => count($this->Registrazione_ore_model->registrazioni_filtrate(array_merge($filtro_query, array('tipo' => 'globale')))),
                'riepilogo_utenti' => $this->Registrazione_ore_model->riepilogo_ore_per_utente($filtri['dal'], $filtri['al'], $filtri['commessa_id']),
                'riepilogo_commesse' => $this->Registrazione_ore_model->riepilogo_ore_per_commessa_globale($filtri['dal'], $filtri['al']),
                'ultime_registrazioni' => $this->Registrazione_ore_model->registrazioni_filtrate(array(
                    'tipo' => 'globale',
                    'dal' => $filtri['dal'],
                    'al' => $filtri['al'],
                    'commessa_id' => $filtri['commessa_id'],
                    'limite' => 12,
                )),
            );

            $this->load->view('reporti/index', $data);
            return;
        }

        $utente_id = $this->session->userdata('utente_id');
        $filtro_query = array(
            'dal' => $filtri['dal'],
            'al' => $filtri['al'],
            'utente_id' => $utente_id,
        );

        $data = array(
            'titolo' => 'I miei report',
            'globale' => false,
            'filtri' => $filtri,
            'commesse' => $commesse,
            'totale_ore' => $this->Registrazione_ore_model->totale_filtrato($filtro_query),
            'totale_registrazioni' => count($this->Registrazione_ore_model->registrazioni_filtrate(array_merge($filtro_query, array('tipo' => 'personale')))),
            'riepilogo_commesse' => $this->Registrazione_ore_model->riepilogo_ore_per_commessa_utente($utente_id, $filtri['dal'], $filtri['al']),
            'ultime_registrazioni' => $this->Registrazione_ore_model->registrazioni_filtrate(array(
                'tipo' => 'personale',
                'utente_id' => $utente_id,
                'dal' => $filtri['dal'],
                'al' => $filtri['al'],
                'limite' => 12,
            )),
        );

        $this->load->view('reporti/index', $data);
    }

    public function utenti()
    {
        $this->richiedi_admin();

        $data = array(
            'titolo' => 'Report per utenti',
            'globale' => true,
            'riepilogo_utenti' => $this->Registrazione_ore_model->riepilogo_ore_per_utente(),
        );

        $this->load->view('reporti/utenti', $data);
    }

    public function commesse()
    {
        $this->richiedi_admin();

        $data = array(
            'titolo' => 'Report per commesse',
            'globale' => true,
            'riepilogo_commesse' => $this->Registrazione_ore_model->riepilogo_ore_per_commessa_globale(),
        );

        $this->load->view('reporti/commesse', $data);
    }

    private function leggi_filtri_report()
    {
        // Anche qui i filtri usano GET, così l'URL racconta sempre il report che si sta guardando.
        $dal = trim((string) $this->input->get('dal', true));
        $al = trim((string) $this->input->get('al', true));
        $commessa_id = trim((string) $this->input->get('commessa_id', true));

        if ($dal === '' && $al === '')
        {
            $al = date('Y-m-d');
            $dal = date('Y-m-d', strtotime('-30 days'));
        }

        return array(
            'dal' => $dal ?: null,
            'al' => $al ?: null,
            'commessa_id' => $commessa_id !== '' ? (int) $commessa_id : null,
        );
    }
}
