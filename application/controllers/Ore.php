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
        $this->load->model('Utente_model');
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

    public function export_mie_excel()
    {
        $this->richiedi_login();

        $filtri = $this->leggi_filtri_periodo(true, false);
        $utente_id = (int) $this->session->userdata('utente_id');
        $utente = $this->Utente_model->trova_per_id($utente_id);
        $righe = $this->Registrazione_ore_model->registrazioni_filtrate(array(
            'tipo' => 'personale',
            'utente_id' => $utente_id,
            'dal' => $filtri['dal'],
            'al' => $filtri['al'],
        ));

        $this->scarica_excel_registrazioni(
            $righe,
            array(
                'titolo_foglio' => 'Le mie ore',
                'nome_file' => $this->costruisci_nome_file_export('ore', $filtri, $utente),
                'utente' => $utente,
                'filtri' => $filtri,
                'commessa' => null,
            )
        );
    }

    public function export_utente_excel($utente_id)
    {
        $this->richiedi_admin();

        $utente = $this->Utente_model->trova_per_id((int) $utente_id);
        if ( ! $utente)
        {
            show_404();
            return;
        }

        $filtri = $this->leggi_filtri_periodo(false, true);
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
        else
        {
            $commessa_id = null;
        }

        $righe = $this->Registrazione_ore_model->registrazioni_filtrate(array(
            'tipo' => 'personale',
            'utente_id' => (int) $utente->id,
            'dal' => $filtri['dal'],
            'al' => $filtri['al'],
            'commessa_id' => $commessa_id,
        ));

        $this->scarica_excel_registrazioni(
            $righe,
            array(
                'titolo_foglio' => 'Ore utente',
                'nome_file' => $this->costruisci_nome_file_export('ore_' . $this->slug_export(trim($utente->nome . ' ' . $utente->cognome)), $filtri, $utente, $commessa_filtrata),
                'utente' => $utente,
                'filtri' => $filtri,
                'commessa' => $commessa_filtrata,
            )
        );
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

    private function scarica_excel_registrazioni(array $righe, array $opzioni)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($opzioni['titolo_foglio'] ?? 'Ore');

        $riga_excel = 1;
        $utente = $opzioni['utente'] ?? null;
        $filtri = $opzioni['filtri'] ?? array();
        $commessa = $opzioni['commessa'] ?? null;

        if ($utente)
        {
            $sheet->setCellValue('A' . $riga_excel, 'Utente');
            $sheet->setCellValue('B' . $riga_excel, trim((string) $utente->nome . ' ' . (string) $utente->cognome));
            $riga_excel++;

            $sheet->setCellValue('A' . $riga_excel, 'Email');
            $sheet->setCellValue('B' . $riga_excel, (string) $utente->email);
            $riga_excel++;
        }

        $sheet->setCellValue('A' . $riga_excel, 'Periodo');
        $sheet->setCellValue('B' . $riga_excel, $this->formatta_periodo_export($filtri['dal'] ?? null, $filtri['al'] ?? null));
        $riga_excel++;

        $sheet->setCellValue('A' . $riga_excel, 'Commessa');
        $sheet->setCellValue('B' . $riga_excel, $this->formatta_etichetta_commessa_export($commessa));
        $riga_excel += 2;

        $intestazioni = array('Data', 'Ore', 'Codice commessa', 'Attivita', 'Nome commessa', 'Cliente', 'Descrizione');
        $colonne = array('A', 'B', 'C', 'D', 'E', 'F', 'G');
        $riga_intestazioni = $riga_excel;

        foreach ($intestazioni as $indice => $intestazione)
        {
            $sheet->setCellValue($colonne[$indice] . $riga_intestazioni, $intestazione);
        }

        $sheet->getStyle('A1:B' . ($riga_intestazioni - 1))->getFont()->setBold(true);
        $sheet->getStyle('A' . $riga_intestazioni . ':G' . $riga_intestazioni)->getFont()->setBold(true);
        $sheet->freezePane('A' . ($riga_intestazioni + 1));
        $sheet->setAutoFilter('A' . $riga_intestazioni . ':G' . $riga_intestazioni);

        $riga_excel = $riga_intestazioni + 1;
        $totale_ore = 0;

        foreach ($righe as $riga)
        {
            $sheet->setCellValue('A' . $riga_excel, (string) $riga->data_lavoro);
            $sheet->setCellValue('B' . $riga_excel, (float) $riga->ore);
            $sheet->setCellValue('C' . $riga_excel, (string) $riga->commessa_codice);
            $sheet->setCellValue('D' . $riga_excel, (string) $riga->commessa_attivita);
            $sheet->setCellValue('E' . $riga_excel, (string) $riga->commessa_nome);
            $sheet->setCellValue('F' . $riga_excel, (string) $riga->cliente_ragione_sociale);
            $sheet->setCellValue('G' . $riga_excel, (string) ($riga->descrizione ?? ''));
            $totale_ore += (float) $riga->ore;
            $riga_excel++;
        }

        $sheet->setCellValue('A' . $riga_excel, 'Totale ore');
        $sheet->setCellValue('B' . $riga_excel, $totale_ore);
        $sheet->getStyle('A' . $riga_excel . ':B' . $riga_excel)->getFont()->setBold(true);
        $sheet->getStyle('B' . ($riga_intestazioni + 1) . ':B' . $riga_excel)
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        foreach ($colonne as $colonna)
        {
            $sheet->getColumnDimension($colonna)->setAutoSize(true);
        }

        while (ob_get_level() > 0)
        {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . ($opzioni['nome_file'] ?? 'export.xlsx') . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Expires: 0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function costruisci_nome_file_export($prefisso, array $filtri, $utente = null, $commessa = null)
    {
        $parti_nome_file = array($prefisso);

        if ($utente)
        {
            $parti_nome_file[] = $this->slug_export(trim((string) $utente->nome . ' ' . (string) $utente->cognome));
        }

        if ($commessa)
        {
            $parti_nome_file[] = $this->slug_export($this->formatta_etichetta_commessa_export($commessa));
        }

        if (! empty($filtri['dal']))
        {
            $parti_nome_file[] = $filtri['dal'];
        }

        if (! empty($filtri['al']) && $filtri['al'] !== $filtri['dal'])
        {
            $parti_nome_file[] = $filtri['al'];
        }

        return implode('_', array_filter($parti_nome_file)) . '.xlsx';
    }

    private function formatta_periodo_export($dal = null, $al = null)
    {
        if (! empty($dal) && ! empty($al))
        {
            return $dal . ' - ' . $al;
        }

        if (! empty($dal))
        {
            return 'Dal ' . $dal;
        }

        if (! empty($al))
        {
            return 'Fino al ' . $al;
        }

        return 'Tutto il periodo';
    }

    private function formatta_etichetta_commessa_export($commessa = null)
    {
        if (! $commessa)
        {
            return 'Tutte le commesse';
        }

        return trim(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita);
    }

    private function slug_export($valore)
    {
        $valore = strtolower((string) $valore);
        $valore = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valore);
        $valore = preg_replace('/[^a-z0-9]+/', '_', $valore ?? '');
        $valore = trim((string) $valore, '_');

        return $valore !== '' ? $valore : 'export';
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
