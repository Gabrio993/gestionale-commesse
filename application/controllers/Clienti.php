<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clienti extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('Cliente_model');
    }

    public function index()
    {
        $this->richiedi_admin();

        $data['clienti'] = $this->Cliente_model->tutti(false);
        $this->load->view('clienti/index', $data);
    }

    public function nuovo()
    {
        $this->richiedi_admin();
        $this->load->view('clienti/nuovo');
    }

    public function salva()
    {
        $this->richiedi_admin();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        $this->form_validation->set_rules('ragione_sociale', 'Ragione sociale', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('partita_iva', 'Partita IVA', 'trim|max_length[20]');
        $this->form_validation->set_rules('codice_fiscale', 'Codice fiscale', 'trim|max_length[20]');
        $this->form_validation->set_rules('indirizzo', 'Indirizzo', 'trim|max_length[255]');

        if ($this->form_validation->run() === false)
        {
            $this->load->view('clienti/nuovo');
            return;
        }

        $this->Cliente_model->crea(array(
            'ragione_sociale' => trim($this->input->post('ragione_sociale', true)),
            'partita_iva' => trim((string) $this->input->post('partita_iva', true)) ?: null,
            'codice_fiscale' => trim((string) $this->input->post('codice_fiscale', true)) ?: null,
            'indirizzo' => trim((string) $this->input->post('indirizzo', true)) ?: null,
            'note' => trim((string) $this->input->post('note', true)) ?: null,
            'attivo' => 1,
        ));

        redirect('clienti');
    }
}
