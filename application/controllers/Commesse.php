<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Commesse extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Commessa_model');
        $this->load->model('Cliente_model');
        $this->load->model('Registrazione_ore_model');
    }

    public function index()
    {
        $this->richiedi_login();

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

        $data = array(
            'commessa' => $commessa,
            'ore_commessa' => $this->Registrazione_ore_model->per_commessa($id),
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

        $this->form_validation->set_rules('cliente_id', 'Cliente', 'required|integer');
        $this->form_validation->set_rules('codice', 'Codice', 'trim|max_length[50]');
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('descrizione', 'Descrizione', 'trim');

        if ($this->form_validation->run() === false)
        {
            $data['clienti'] = $this->Cliente_model->tutti(true);
            $this->load->view('commesse/nuova', $data);
            return;
        }

        $this->Commessa_model->crea(array(
            'cliente_id' => (int) $this->input->post('cliente_id', true),
            'codice' => trim((string) $this->input->post('codice', true)) ?: null,
            'nome' => trim($this->input->post('nome', true)),
            'descrizione' => trim((string) $this->input->post('descrizione', true)) ?: null,
            'stato' => 'attiva',
            'attivo' => 1,
        ));

        redirect('commesse');
    }
}
