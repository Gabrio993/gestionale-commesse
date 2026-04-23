<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Commesse extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('Commessa_model');
        $this->load->model('Cliente_model');
        $this->load->model('Registrazione_ore_model');
    }

    public function index()
    {
        $this->richiedi_login();

        // Mostriamo sempre le commesse attive, così l'utente arriva subito al punto di inserimento ore.
        $data['commesse'] = $this->Commessa_model->tutte(true);
        $this->load->view('commesse/index', $data);
    }

    public function dettaglio($id)
    {
        $this->richiedi_login();

        $commessa = $this->Commessa_model->trova($id);
        if ( ! $commessa)
        {
            show_404();
            return;
        }

        // Anche il dettaglio commessa deve poter essere letto per periodo, altrimenti lo storico diventa troppo rumoroso.
        $filtri = $this->leggi_filtri_periodo(false, true);

        // Gli admin vedono tutte le ore, gli utenti normali solo le proprie.
        $ruolo = $this->ruolo_utente();
        $nav_active = trim((string) $this->input->get('nav', true));
        if (! in_array($nav_active, array('report_commesse', 'report_utenti', 'report', 'commesse', 'ore', 'dashboard'), true))
        {
            $nav_active = null;
        }
        $ore_commessa = in_array($ruolo, array('admin', 'superadmin'), true)
            ? $this->Registrazione_ore_model->per_commessa($id, $filtri['dal'], $filtri['al'])
            : $this->Registrazione_ore_model->per_commessa_e_utente($id, $this->session->userdata('utente_id'), $filtri['dal'], $filtri['al']);

        $data = array(
            'commessa' => $commessa,
            'ore_commessa' => $ore_commessa,
            'filtri' => $filtri,
            'totale_ore' => $this->Registrazione_ore_model->totale_ore_globali($filtri['dal'], $filtri['al'], $id),
            'totale_registrazioni' => count($ore_commessa),
            'puo_modificare' => true,
            'ruolo_utente' => $ruolo,
            'nav_active' => $nav_active,
        );

        $this->load->view('commesse/dettaglio', $data);
    }

    public function nuova()
    {
        $this->richiedi_admin();

        $data['clienti'] = $this->Cliente_model->tutti(true);
        $this->load->view('commesse/nuova', $data);
    }

    public function salva()
    {
        $this->richiedi_admin();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        // Il campo visibile è "attivita"; il campo "nome" resta solo tecnico e viene popolato in automatico.
        $this->form_validation->set_rules('cliente_id', 'Cliente', 'required|integer');
        $this->form_validation->set_rules('codice', 'Codice', 'trim|max_length[50]');
        $this->form_validation->set_rules('attivita', 'Attività', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('descrizione', 'Descrizione', 'trim');

        if ($this->form_validation->run() === false)
        {
            $data['clienti'] = $this->Cliente_model->tutti(true);
            $this->load->view('commesse/nuova', $data);
            return;
        }

        $attivita = trim((string) $this->input->post('attivita', true));

        $this->Commessa_model->crea(array(
            'cliente_id' => (int) $this->input->post('cliente_id', true),
            'codice' => trim((string) $this->input->post('codice', true)) ?: null,
            'attivita' => $attivita,
            'nome' => $attivita,
            'descrizione' => trim((string) $this->input->post('descrizione', true)) ?: null,
            'stato' => 'attiva',
            'attivo' => 1,
        ));

        redirect('commesse');
    }

    private function leggi_filtri_periodo($default_today = false, $default_last_30_days = false)
    {
        $dal = trim((string) $this->input->get('dal', true));
        $al = trim((string) $this->input->get('al', true));

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
        );
    }
}
