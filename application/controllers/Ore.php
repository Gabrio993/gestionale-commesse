<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ore extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('Commessa_model');
        $this->load->model('Registrazione_ore_model');
    }

    public function mie()
    {
        $this->richiedi_login();

        // Per la vista personale partiamo da oggi, così chi entra vede subito la giornata corrente.
        $filtri = $this->leggi_filtri_periodo(true, false);
        $utente_id = $this->session->userdata('utente_id');
        $anno_corrente = (int) date('Y');
        $mese_corrente = (int) date('n');
        $data = array(
            'ore' => $this->Registrazione_ore_model->per_utente($utente_id, $filtri['dal'], $filtri['al']),
            'totale_ore' => $this->Registrazione_ore_model->totale_ore_utente($utente_id, $filtri['dal'], $filtri['al']),
            'totale_ore_mese' => $this->Registrazione_ore_model->totale_ore_utente_mese($utente_id, $anno_corrente, $mese_corrente),
            'riepilogo_commesse' => $this->Registrazione_ore_model->riepilogo_ore_per_commessa_utente($utente_id, $filtri['dal'], $filtri['al']),
            'filtri' => $filtri,
        );
        $this->load->view('ore/mie', $data);
    }

    public function nuova($commessa_id)
    {
        $this->richiedi_login();

        $commessa = $this->Commessa_model->trova($commessa_id);
        if ( ! $commessa)
        {
            show_404();
            return;
        }

        // Gli utenti normali possono inserire ore solo sulle commesse assegnate.
        if ( ! in_array($this->ruolo_utente(), array('admin', 'superadmin'), true) && ! $this->Commessa_model->utente_ha_commessa($this->session->userdata('utente_id'), $commessa_id))
        {
            show_error('Questa commessa non è assegnata al tuo account.', 403);
            return;
        }

        $data['commessa'] = $commessa;
        $this->load->view('ore/nuova', $data);
    }

    public function salva()
    {
        $this->richiedi_login();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        // Il record ore è minimo: commessa, data, quantità ore e nota libera.
        $this->form_validation->set_rules('commessa_id', 'Commessa', 'required|integer');
        $this->form_validation->set_rules('data_lavoro', 'Data lavoro', 'required');
        $this->form_validation->set_rules('ore', 'Ore', 'required|numeric');

        if ($this->form_validation->run() === false)
        {
            show_error('Dati non validi.', 400);
            return;
        }

        $commessa_id = (int) $this->input->post('commessa_id', true);
        $commessa = $this->Commessa_model->trova($commessa_id);
        if ( ! $commessa)
        {
            show_404();
            return;
        }

        if ( ! in_array($this->ruolo_utente(), array('admin', 'superadmin'), true) && ! $this->Commessa_model->utente_ha_commessa($this->session->userdata('utente_id'), $commessa_id))
        {
            show_error('Questa commessa non è assegnata al tuo account.', 403);
            return;
        }

        $this->Registrazione_ore_model->crea(array(
            'utente_id' => (int) $this->session->userdata('utente_id'),
            'commessa_id' => $commessa_id,
            'data_lavoro' => $this->input->post('data_lavoro', true),
            'ore' => $this->input->post('ore', true),
            'descrizione' => trim((string) $this->input->post('descrizione', true)) ?: null,
        ));

        redirect('ore/mie');
    }

    public function utente($utente_id)
    {
        $this->richiedi_admin();
        $this->load->model('Utente_model');
        $this->load->model('Commessa_model');

        // Sul dettaglio utente partiamo da un intervallo ampio: è più utile per l'overview.
        $filtri = $this->leggi_filtri_periodo(false, true);
        $utente = $this->Utente_model->trova_per_id((int) $utente_id);
        if ( ! $utente)
        {
            show_404();
            return;
        }

        $commessa_id = trim((string) $this->input->get('commessa_id', true));
        $commessa_filtrata = null;
        if ($commessa_id !== '')
        {
            $commessa_filtrata = $this->Commessa_model->trova((int) $commessa_id);
            if ($commessa_filtrata)
            {
                $commessa_id = (int) $commessa_id;
            }
            else
            {
                $commessa_id = null;
            }
        }

        $anno_corrente = (int) date('Y');
        $mese_corrente = (int) date('n');
        $nav_active = trim((string) $this->input->get('nav', true));
        if (! in_array($nav_active, array('report_utenti', 'report_commesse', 'utenti', 'ruoli', 'report', 'ore', 'dashboard'), true))
        {
            $nav_active = null;
        }
        $data = array(
            'utente' => $utente,
            'commesse' => $this->Commessa_model->tutte_con_cliente(),
            'commessa_filtrata' => $commessa_filtrata,
            'ore' => $this->Registrazione_ore_model->per_utente($utente_id, $filtri['dal'], $filtri['al'], null, $commessa_id),
            'totale_ore' => $this->Registrazione_ore_model->totale_ore_utente($utente_id, $filtri['dal'], $filtri['al'], $commessa_id),
            'totale_ore_mese' => $this->Registrazione_ore_model->totale_ore_utente_mese($utente_id, $anno_corrente, $mese_corrente),
            'riepilogo_commesse' => $this->Registrazione_ore_model->riepilogo_ore_per_commessa_utente($utente_id, $filtri['dal'], $filtri['al'], $commessa_id),
            'filtri' => $filtri,
            'commessa_id' => $commessa_id,
            'nav_active' => $nav_active,
        );

        $this->load->view('ore/utente', $data);
    }

    public function modifica($id)
    {
        $this->richiedi_login();

        $registrazione = $this->Registrazione_ore_model->trova($id);
        if ( ! $registrazione)
        {
            show_404();
            return;
        }

        // Gli utenti normali possono cambiare solo le proprie righe; admin e superadmin possono vedere tutto.
        if ( ! $this->puo_gestire_registrazione($registrazione))
        {
            show_error('Accesso non autorizzato.', 403);
            return;
        }

        $data = array(
            'registrazione' => $registrazione,
            'commessa' => $this->Commessa_model->trova($registrazione->commessa_id),
        );

        $this->load->view('ore/modifica', $data);
    }

    public function aggiorna($id)
    {
        $this->richiedi_login();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        $registrazione = $this->Registrazione_ore_model->trova($id);
        if ( ! $registrazione)
        {
            show_404();
            return;
        }

        if ( ! $this->puo_gestire_registrazione($registrazione))
        {
            show_error('Accesso non autorizzato.', 403);
            return;
        }

        $this->form_validation->set_rules('data_lavoro', 'Data lavoro', 'required');
        $this->form_validation->set_rules('ore', 'Ore', 'required|numeric');

        if ($this->form_validation->run() === false)
        {
            $data = array(
                'registrazione' => $registrazione,
                'commessa' => $this->Commessa_model->trova($registrazione->commessa_id),
            );

            $this->load->view('ore/modifica', $data);
            return;
        }

        $this->Registrazione_ore_model->aggiorna($id, array(
            'data_lavoro' => $this->input->post('data_lavoro', true),
            'ore' => $this->input->post('ore', true),
            'descrizione' => trim((string) $this->input->post('descrizione', true)) ?: null,
        ));

        redirect('ore/mie');
    }

    public function elimina($id)
    {
        $this->richiedi_login();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        $registrazione = $this->Registrazione_ore_model->trova($id);
        if ( ! $registrazione)
        {
            show_404();
            return;
        }

        if ( ! $this->puo_gestire_registrazione($registrazione))
        {
            show_error('Accesso non autorizzato.', 403);
            return;
        }

        $this->Registrazione_ore_model->elimina($id);
        redirect('ore/mie');
    }

    private function puo_gestire_registrazione($registrazione)
    {
        // Admin e superadmin possono agire su tutte le righe; l'utente normale solo sulle sue.
        $ruolo = $this->ruolo_utente();
        if (in_array($ruolo, array('admin', 'superadmin'), true))
        {
            return true;
        }

        return (int) $registrazione->utente_id === (int) $this->session->userdata('utente_id');
    }

    private function leggi_filtri_periodo($default_today = false, $default_last_30_days = false)
    {
        // I filtri arrivano via GET così si possono condividere e ricaricare facilmente.
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
