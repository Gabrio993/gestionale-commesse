<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ore extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Commessa_model');
        $this->load->model('Registrazione_ore_model');
    }

    public function mie()
    {
        $this->richiedi_login();

        $data['ore'] = $this->Registrazione_ore_model->per_utente($this->session->userdata('utente_id'));
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

        $data['commessa'] = $commessa;
        $this->load->view('ore/nuova', $data);
    }

    public function salva()
    {
        $this->richiedi_login();

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

        $utente = $this->Utente_model->trova_per_id((int) $utente_id);
        if ( ! $utente)
        {
            show_404();
            return;
        }

        $data = array(
            'utente' => $utente,
            'ore' => $this->Registrazione_ore_model->per_utente($utente_id),
        );

        $this->load->view('ore/utente', $data);
    }
}
