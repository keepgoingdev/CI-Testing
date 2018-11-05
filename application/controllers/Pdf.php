<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));
		$this->load->model(array('pdf_model','report_model','participant_model','course_event_model'));

		if (!$this->ion_auth->logged_in())
		{ 
			print_r('Du är inte inloggad. Vänligen logga in.');
            exit;
		}
	}
    
    public function index()
    {
        print_r('Ogilltig funktion. Försök igen.');
    }
    
    public function generate_list($event_id = null)
    {
        $formd = array(
            'event_id' => $event_id
        );

        $this->form_validation->set_data($formd);
        
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric');
        
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            $event_id = $this->security->xss_clean($event_id);
            $event_data = $this->pdf_model->get_course_event($event_id);
            $course_data = $this->pdf_model->get_course($event_data->course_id);
            $participants = $this->pdf_model->get_by_event($event_id);
            $ghosts = $this->pdf_model->get_ghosts($event_id);
            $teacher = $this->pdf_model->get_teachers($event_id);

            $created_by = '';
            
            if (!is_null($this->ion_auth->user($event_data->user_id)->row()))
            {
                $user = $this->ion_auth->user($event_data->user_id)->row();
                $created_by = $user->first_name." ".$user->last_name;
            }
            
            $html = '<h4 style="font-size:20px;">Utbildare: '.$teacher->teachers.'</h4>';
            $html .= '<table CELLSPACING="0" cellpadding="2" border="0.5">';
            $html .= '<thead style="font-size:13px;"><tr style="background-color:#d3104c;">';
            $html .= '<td colspan="2"></td>';
            $html .= '<td colspan="5" style="text-align:center;color:#ffffff;"><strong>P E R S O N U P P G I F T E R</strong></td>';
            $html .= '<td colspan="4" style="text-align:center;color:#ffffff;"><strong>A R B E T S G I V A R U P P G I F T E R</strong></td>';
            $html .= '<td colspan="5" style="text-align:center;color:#ffffff;"><strong>A D R E S S U P P G I F T E R</strong></td>';
            $html .= '<td colspan="4"></td>';
            $html .= '</tr><tr style="background-color:#00ffff;">';
            $html .= '<td style="padding:5px;"><strong>Intyg</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Kallelse</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Förnamn<br>deltagare*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Efternamn<br>deltagare*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Pers.nr*<br>(YYMMDD-NNNN)</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Telefon</strong></td>';
            $html .= '<td style="padding:5px;"><strong>E-post</strong></td>';
            $html .= '<td style="padding:5px;"></td>';
            $html .= '<td style="padding:5px;"><strong>Arbetsgivare*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Organisationsnr*</strong></td>';
            $html .= '<td style="padding:5px;"></td>';
            $html .= '<td style="padding:5px;"><strong>Adresstyp*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Mottagare<br>rad 1*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Mottagare<br>rad 2</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Gatuadress/box*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Postnr*</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Postort</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Beställare</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Pris</strong></td>';
            $html .= '<td style="padding:5px;"><strong>Säljare</strong></td>';
            $html .= '</tr></thead>';
            $html .= '<tbody style="font-size:13px;">';            
            
            foreach ($participants as $pp)
            {
                $html .= '<tr>';
            
                    if ($pp->diploma_generated != 0)
                    {
                        $html .= '<td style="padding:5px;">'.$pp->diploma_generated.'</td>';
                    }
                    else 
                    {
                        $html .= '<td style="padding:5px;"></td>';
                    }
                    
                    if ($pp->mail_sent != '-1')
                    {
                        $html .= '<td style="padding:5px;">'.$pp->mail_sent.'</td>';
                    }
                    else
                    {
                        $html .= '<td style="padding:5px;"></td>';
                    }
                
                    $html .= '<td style="padding:5px;">'.trim($pp->first_name).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->last_name).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->personalnumber).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->phone).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->email).'</td>';
                    $html .= '<td style="padding:5px;"></td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->company_name).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->company_registration).'</td>';
                    $html .= '<td style="padding:5px;"></td>';
                    $html .= '<td style="padding:5px;">Företagsadress</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->company_name).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->contact_person).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->company_postal_address).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->company_postal_zip).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->company_postal_city).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->contact_person).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->price).'</td>';
                    $html .= '<td style="padding:5px;">'.trim($pp->sales_person).'</td>';
            
                $html .= '</tr>';
            }
            
            foreach ($ghosts as $gg)
            {
                for ($i = 1; $i <= $gg->amount; $i++) {
                    
                    $html .= '<tr>';
            
                        $html .= '<td style="padding:5px;"></td>';
                        
                        if ($gg->mail_sent != '-1')
                        {
                            $html .= '<td style="padding:5px;">'.$gg->mail_sent.'</td>';
                        }
                        else
                        {
                            $html .= '<td style="padding:5px;"></td>';
                        }
                    
                        $html .= '<td style="padding:5px;"></td>';
                        $html .= '<td style="padding:5px;"></td>';
                        $html .= '<td style="padding:5px;"></td>';
                        $html .= '<td style="padding:5px;"></td>';
                        $html .= '<td style="padding:5px;"></td>';                                                
                        $html .= '<td style="padding:5px;"></td>';
                        $html .= '<td style="padding:5px;">'.$gg->company_name.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->company_registration.'</td>';
                        $html .= '<td style="padding:5px;"></td>';
                        $html .= '<td style="padding:5px;">Företagsadress</td>';
                        $html .= '<td style="padding:5px;">'.$gg->company_name.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->contact_person.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->company_postal_address.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->company_postal_zip.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->company_postal_city.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->contact_person.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->price.'</td>';
                        $html .= '<td style="padding:5px;">'.$gg->sales_person.'</td>';

                    $html .= '</tr>';
                    
                }
            }                        
            
            $html .= '</tbody>';
            $html .= '</table>';
            
            $html .= '<br /><table CELLSPACING="0" cellpadding="2" border="0.5" style="background-color:yellow;">';
                
                $html .= '<tr>';
                    $html .= '<td></td>';
                    $html .= '<td><strong>Leverantör/spec</strong></td>';                    
                    $html .= '<td><strong>Datum</strong></td>';
                    $html .= '<td><strong>Signatur</strong></td>';            
                $html .= '</tr>';
            
                $html .= '<tr>';                                
                    $html .= '<td><strong>Skapad av</strong></td>';
                    $html .= '<td>'.$created_by.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';                                
                $html .= '</tr>';
            
                $html .= '<tr>';
                    $html .= '<td><strong>Tider</strong></td>';
                    
                    if (!empty($event_data->course_date_end))
                    {
                        $html .= '<td>'.$event_data->course_date.' - '.$event_data->course_date_end.'</td>';
                    }
                    else 
                    {
                        $html .= '<td>'.$event_data->course_date.'</td>';
                    }
                                
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';
                    $html .= '<td><strong>Längd</strong></td>';
                    $html .= '<td>'.$course_data->course_time.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';
                    $html .= '<td><strong>Lunch, fika</strong></td>';
                    $html .= '<td>'.$event_data->food.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';
                    $html .= '<td><strong>Kursmaterial</strong></td>';
                    $html .= '<td>'.$event_data->course_material.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
            
                $html .= '<tr>';                                
                    $html .= '<td><strong>Adress att skicka material till</strong></td>';
                    $html .= '<td>'.$event_data->send_material_to.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';                                
                    $html .= '<td><strong>Kontaktperson + tel</strong></td>';
                    $html .= '<td>'.$event_data->event_contact.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';
                    $html .= '<td><strong>Övrigt</strong></td>';
                    $html .= '<td>'.$event_data->living.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';                                
                    $html .= '<td><strong>Utbildningsplats</strong></td>';
                    $html .= '<td>'.$event_data->location.'<br>'.$event_data->city.', '.$event_data->zip.'</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                $html .= '</tr>';
            
                $html .= '<tr>';                                
                    $html .= '<td style="height:100px;"><strong>Övrigt</strong></td>';
                    $html .= '<td colspan="3" style="height:100px;width:400px;">'.$event_data->freetext.'</td>';
                $html .= '</tr>';
            
            $html .= '</table>';
            
            require_once(APPPATH.'third_party/html2pdf/html2pdf.class.php');
            
            $html2pdf = new HTML2PDF('L', 'A2', 'en', true, 'UTF-8', array(5, 5, 5, 5));
            $html2pdf->WriteHTML($html);
            $html2pdf->Output('exemple.pdf');
            
        }
    }
    
    public function generate_label($event_id = null, $participant_id = null)
    {
        $formd = array(
            'event_id' => $event_id,
            'participant_id' => $participant_id
        );

        $this->form_validation->set_data($formd);
        
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|max_length[11]|trim');
        $this->form_validation->set_rules('participant_id', 'Deltagar ID', 'required|numeric|max_length[11]|trim');
        
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            $event_id = $this->security->xss_clean($event_id);
            $participant_id = $this->security->xss_clean($participant_id);
            $company_detail = $this->pdf_model->get_contact_detail($event_id, $participant_id);
            
            if (empty($company_detail))
            {
                ('Inga deltagare hittades. Kontrollera om utbildningen är slutförd.');
            }
            
            $filename = 'etikett_'.date('Ymd').'.pdf';
            
            require_once(APPPATH.'third_party/fpdi/fpdf.php');
            require_once(APPPATH.'third_party/fpdi/textbox.php');
            require_once(APPPATH.'third_party/fpdi/fpdi.php');
            
            $error = false;
            
            try {
                
                $pdf = new FPDI();
                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                
                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/labels/label62x29mm.pdf');
                $tplIdx = $pdf->importPage(1);
                $pdf->addPage();
                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                $pdf->SetFont('CenturyGothicBold','B',10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->text(2, 8, utf8_decode(trim($company_detail->company_name)));
                $pdf->text(2, 13, utf8_decode(trim($company_detail->contact_person)));
                $pdf->text(2, 18, utf8_decode(trim($company_detail->company_postal_address)));
                $pdf->text(2, 23, utf8_decode(trim($company_detail->company_postal_zip)));
                $pdf->text(15, 23, utf8_decode(trim($company_detail->company_postal_city)));

                $pdf->Output("D", utf8_decode($filename));
            }
            catch(Exception $e) {
                $error = true;
            }
            finally {
                if ($error != false) 
                {
                    print_r("Tyvärr kunde inte etiketterna genereras. Försök igen.");
                }
            }
        }
    }
    
    public function generate_labels($event_id = null)
    {
        $formd = array(
            'event_id' => $event_id
        );

        $this->form_validation->set_data($formd);
        
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|max_length[11]|trim');
        
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            $event_id = $this->security->xss_clean($event_id);
            $company_details = $this->pdf_model->get_contact_details($event_id);
            
            if (empty($company_details))
            {
                ('Inga deltagare hittades. Kontrollera om utbildningen är slutförd.');
            }
            
            $filename = 'etiketter_'.date('Ymd').'.pdf';
            
            require_once(APPPATH.'third_party/fpdi/fpdf.php');
            require_once(APPPATH.'third_party/fpdi/textbox.php');
            require_once(APPPATH.'third_party/fpdi/fpdi.php');
            
            $error = false;
            
            try {
                
                $pdf = new FPDI();
                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                
                foreach ($company_details as $cd)
                {
                    $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/labels/label62x29mm.pdf');
                    $tplIdx = $pdf->importPage(1);
                    $pdf->addPage();
                    $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                    
                    $pdf->SetFont('CenturyGothicBold','B',10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->text(2, 8, utf8_decode(trim($cd->company_name)));
                    $pdf->text(2, 13, utf8_decode(trim($cd->contact_person)));
                    $pdf->text(2, 18, utf8_decode(trim($cd->company_postal_address)));
                    $pdf->text(2, 23, utf8_decode(trim($cd->company_postal_zip)));
                    $pdf->text(15, 23, utf8_decode(trim($cd->company_postal_city)));
                    
                }

                $pdf->Output("D", utf8_decode($filename));
            }
            catch(Exception $e) {
                $error = true;
            }
            finally {
                if ($error != false) 
                {
                    print_r("Tyvärr kunde inte etiketterna genereras. Försök igen.");
                }
            }
        }
    }

    public function generate_cert($event_id = null, $participant_id = null)
    {
        $formd = array(
            'event_id' => $event_id,
            'participant_id' => $participant_id            
        );

        $this->form_validation->set_data($formd);
        
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric');
		$this->form_validation->set_rules('participant_id', 'ID', 'required|numeric');
        
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            $event_id = $this->security->xss_clean($event_id);
            $participant_id = $this->security->xss_clean($participant_id);
            
            $event_data = $this->pdf_model->get_course_event($event_id);
            $course_data = $this->pdf_model->get_course($event_data->course_id);
            $participant_data = $this->pdf_model->get_participant($participant_id);
            $participant_event_details = $this->pdf_model->get_participant_event_details($event_id, $participant_id);
            
            $full_name = trim($participant_data->full_name);
            $personalnumber = $participant_data->personalnumber;
            $fp_combined = $full_name." ".$personalnumber;
            
            $cert_generated = $participant_event_details->cert_generated;
            
            // Date from course date
            $date = substr($event_data->course_date, 0, 10);
            $dateyr3 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 3 year"));
            $dateyr5 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 5 year"));
            
            $teacher = $this->pdf_model->get_teachers($event_id);
            $fname = str_replace(" ", "", $full_name);
            $fname = strtolower($fname);
            $fname = $fname.".pdf";
            
            $cert_template = $course_data->cert_template;
            
            $error = false;
            
            require_once(APPPATH.'third_party/fpdi/fpdf.php');
            require_once(APPPATH.'third_party/fpdi/textbox.php');
            require_once(APPPATH.'third_party/fpdi/fpdi.php');
            
            
            switch($cert_template)
            {
                case "adr13":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/adr13/adr_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 16, utf8_decode("Namn:"));
                        $pdf->text(48, 16, utf8_decode($full_name));

                        $pdf->text(10, 26, utf8_decode("Personnummer:"));
                        $pdf->text(48, 26, utf8_decode($personalnumber));

                        $pdf->text(10, 36, utf8_decode("Utfärdat:"));
                        $pdf->text(48, 36, utf8_decode($date));                                        

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(10, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/adr13/adr_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "ams":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/ams/ams_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 16, utf8_decode("Certifierad:"));
                        $pdf->text(53, 16, utf8_decode($full_name));

                        $pdf->text(10, 24, utf8_decode("Personnummer:"));
                        $pdf->text(53, 24, utf8_decode($personalnumber));

                        $pdf->text(10, 31, utf8_decode("Utbildningsdatum:"));
                        $pdf->text(53, 31, utf8_decode($date));

                        $pdf->text(10, 34, utf8_decode("Uppdateras rek. senast:"));
                        $pdf->text(53, 34, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(10, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/ams/ams_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "apv12":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv12/apv12_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 13);
                        $pdf->Write(0, utf8_decode($full_name));
                        $pdf->SetXY(48, 13);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 20, utf8_decode("Utbildning"));
                        $pdf->text(50, 20, utf8_decode("Utfärdat"));
                        $pdf->text(14, 24, utf8_decode("APV 1"));
                        $pdf->text(14, 29, utf8_decode("APV 2"));
                        $pdf->text(14, 34, utf8_decode("APV 3A"));
                        $pdf->text(14, 39, utf8_decode("APV 3B"));
                        $pdf->text(14, 44, utf8_decode("SPV"));

                        $pdf->text(50, 24, utf8_decode($date));
                        $pdf->text(50, 29, utf8_decode($date));
                        $pdf->text(50, 44, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv12/apv12_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "apv123a":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123a/apv123a_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 13);
                        $pdf->Write(0, utf8_decode($full_name));
                        $pdf->SetXY(48, 13);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 20, utf8_decode("Utbildning"));
                        $pdf->text(50, 20, utf8_decode("Utfärdat"));
                        $pdf->text(14, 24, utf8_decode("APV 1"));
                        $pdf->text(14, 29, utf8_decode("APV 2"));
                        $pdf->text(14, 34, utf8_decode("APV 3A"));
                        $pdf->text(14, 39, utf8_decode("APV 3B"));
                        $pdf->text(14, 44, utf8_decode("SPV"));

                        $pdf->text(50, 24, utf8_decode($date));
                        $pdf->text(50, 29, utf8_decode($date));
                        $pdf->text(50, 34, utf8_decode($date));
                        $pdf->text(50, 44, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123a/apv123a_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "apv123b":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123b/apv123b_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 13);
                        $pdf->Write(0, utf8_decode($full_name));
                        $pdf->SetXY(48, 13);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 20, utf8_decode("Utbildning"));
                        $pdf->text(50, 20, utf8_decode("Utfärdat"));
                        $pdf->text(14, 23.5, utf8_decode("APV 1"));
                        $pdf->text(14, 27.5, utf8_decode("APV 2"));
                        $pdf->text(14, 31.5, utf8_decode("APV 3A"));
                        $pdf->text(14, 36, utf8_decode("APV 3B"));
                        $pdf->text(14, 40, utf8_decode("SPV"));

                        $pdf->text(50, 23.5, utf8_decode($date));
                        $pdf->text(50, 27.5, utf8_decode($date));
                        $pdf->text(50, 36, utf8_decode($date));
                        $pdf->text(50, 40, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123b/apv123b_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "apv3ab":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3ab/apv3ab_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 13);
                        $pdf->Write(0, utf8_decode($full_name));
                        $pdf->SetXY(48, 13);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 20, utf8_decode("Utbildning"));
                        $pdf->text(50, 20, utf8_decode("Utfärdat"));
                        $pdf->text(14, 24, utf8_decode("APV 1"));
                        $pdf->text(14, 29, utf8_decode("APV 2"));
                        $pdf->text(14, 34, utf8_decode("APV 3A"));
                        $pdf->text(14, 39, utf8_decode("APV 3B"));
                        $pdf->text(14, 44, utf8_decode("SPV"));

                        $pdf->text(50, 24, utf8_decode($date));
                        $pdf->text(50, 29, utf8_decode($date));
                        $pdf->text(50, 34, utf8_decode($date));
                        $pdf->text(50, 39, utf8_decode($date));
                        $pdf->text(50, 44, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3ab/apv3ab_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "apv3a":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3a/apv3a_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 13);
                        $pdf->Write(0, utf8_decode($full_name));
                        $pdf->SetXY(48, 13);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 20, utf8_decode("Utbildning"));
                        $pdf->text(53, 20, utf8_decode("Utfärdat"));
                        $pdf->text(14, 24, utf8_decode("APV 1"));
                        $pdf->text(14, 29, utf8_decode("APV 2"));
                        $pdf->text(14, 34, utf8_decode("APV 3A"));
                        $pdf->text(14, 39, utf8_decode("APV 3B"));
                        $pdf->text(14, 44, utf8_decode("SPV"));

                        $pdf->text(53, 34, utf8_decode($date));
                        $pdf->text(53, 44, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3a/apv3a_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "apv3b":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3b/apv3b_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 13);
                        $pdf->Write(0, utf8_decode($full_name));
                        $pdf->SetXY(48, 13);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(10, 20, utf8_decode("Utbildning"));
                        $pdf->text(50, 20, utf8_decode("Utfärdat"));
                        $pdf->text(14, 24, utf8_decode("APV 1"));
                        $pdf->text(14, 29, utf8_decode("APV 2"));
                        $pdf->text(14, 34, utf8_decode("APV 3A"));
                        $pdf->text(14, 39, utf8_decode("APV 3B"));
                        $pdf->text(14, 44, utf8_decode("SPV"));
                        $pdf->text(50, 39, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3b/apv3b_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "esa14industri":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14industri/esa14industri_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(40, 17);
                        $pdf->Write(0, utf8_decode($full_name));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(40, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                        $pdf->text(41, 33, utf8_decode($date));
                        $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                        $pdf->text(41, 39, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 48, utf8_decode("Utbildare:"));
                        $pdf->text(6, 52, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14industri/esa14industri_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "esa14":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14/esa14_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));
                        
                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(41.1, 17.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(30, 17.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(40, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                        $pdf->text(41, 33, utf8_decode($date));
                        $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                        $pdf->text(41, 39, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 48, utf8_decode("Utbildare:"));
                        $pdf->text(6, 52, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14/esa14_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "esa14tilltrade":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14tilltrade/esa14tilltrade_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));
                        
                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(41.1, 17.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(30, 17.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(40, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                        $pdf->text(41, 33, utf8_decode($date));
                        $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                        $pdf->text(41, 39, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 48, utf8_decode("Utbildare:"));
                        $pdf->text(6, 52, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14tilltrade/esa14tilltrade_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "esaroj":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esaroj/esaroj_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(39.1, 18.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(30, 18.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(38, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 33, utf8_decode("Utfärdat:"));
                        $pdf->text(39, 33, utf8_decode($date));
                        $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                        $pdf->text(39, 39, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 48, utf8_decode("Utbildare:"));
                        $pdf->text(6, 52, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esaroj/esaroj_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "esavattenvagar":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esavattenvagar/esavattenvagar_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(39.1, 18.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(30, 18.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(38, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 33, utf8_decode("Utfärdat:"));
                        $pdf->text(39, 33, utf8_decode($date));
                        $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                        $pdf->text(39, 39, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 48, utf8_decode("Utbildare:"));
                        $pdf->text(6, 52, utf8_decode($teacher->teachers));

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "elsakerhetbegrelinstall":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/elsakerhetbegrelinstall/elsakerhetbegrelinstall_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 16);
                        $pdf->Write(0, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 16, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 24);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 24);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 32);
                        $pdf->Write(0, utf8_decode("Utfärdat:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 32);
                        $pdf->Write(0, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 44, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/elsakerhetbegrelinstall/elsakerhetbegrelinstall_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "fallskydd":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/fallskydd/fallskydd_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 16, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(30, 16, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 24, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(40, 24, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 32, utf8_decode("Utbildningsdatum"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(40, 32, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(5, 42, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/fallskydd/fallskydd_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "hjullastare":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/hjullastare/hjullastare_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 16);
                        $pdf->Write(0, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 16);
                        $pdf->Write(0, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 24);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 24);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 32);
                        $pdf->Write(0, utf8_decode("Utfärdat:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 32);
                        $pdf->Write(0, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 44, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/hjullastare/hjullastare_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "kj4115":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/kj4115/kj4115_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));
                        
                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(41.1, 17.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(255, 153, 0);
                            $pdf->text(30, 17.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(40, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                        $pdf->text(41, 33, utf8_decode($date));
                        $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                        $pdf->text(41, 39, utf8_decode($dateyr3));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 48, utf8_decode("Utbildare:"));
                        $pdf->text(6, 52, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/kj4115/kj4115_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "lift":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift/lift_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Behörighet:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 17);
                        $pdf->Write(0, utf8_decode("A1 ** A3, B1 ** B3"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->text(6, 27.2, utf8_decode($full_name));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 30);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                        $pdf->text(30, 40, utf8_decode($date));
                        $pdf->text(6, 44, utf8_decode("Giltigt till:"));
                        $pdf->text(30, 44, utf8_decode($dateyr5));

                        $pdf->SetFont('CenturyGothic','',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift/lift_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "lift3a":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift3a/lift3a_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Behörighet:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 17);
                        $pdf->Write(0, utf8_decode("A3"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->text(6, 27.2, utf8_decode($full_name));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 30);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothicBold','B',10);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                        $pdf->text(30, 40, utf8_decode($date));
                        $pdf->text(6, 44, utf8_decode("Giltigt till:"));
                        $pdf->text(30, 44, utf8_decode($dateyr5));

                        $pdf->SetFont('CenturyGothic','',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift3a/lift3a_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "liftlur":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlur/liftlur_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->text(39, 18.2, utf8_decode($full_name));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(38, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',10);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 32, utf8_decode("Behörighet:"));
                        $pdf->text(30, 32, utf8_decode("A1 ** A3, B1 ** B3"));
                        $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                        $pdf->text(30, 40, utf8_decode($date));
                        $pdf->text(6, 46, utf8_decode("Giltigt till:"));
                        $pdf->text(30, 46, utf8_decode($dateyr5));

                        $pdf->SetFont('CenturyGothic','',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlur/liftlur_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "liftlurtorbjornalla":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlurtorbjornalla/liftlurtorbjornalla_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(38, 17);
                        $pdf->Write(0, utf8_decode($full_name));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(38, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',10);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 32, utf8_decode("Behörighet:"));
                        $pdf->text(30, 32, utf8_decode("A1 A2 A3, B1 B2 B3"));
                        $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                        $pdf->text(30, 40, utf8_decode($date));
                        $pdf->text(6, 46, utf8_decode("Giltigt till:"));
                        $pdf->text(30, 46, utf8_decode($dateyr5));

                        $pdf->SetFont('CenturyGothic','',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlurtorbjornalla/liftlurtorbjornalla_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "liftfallskyddlur":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftfallskyddlur/liftfallskyddlur_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 17);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->text(39, 18.2, utf8_decode($full_name));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(5, 23);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(255, 153, 0);
                        $pdf->SetXY(38, 23);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',10);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 32, utf8_decode("Behörighet:"));
                        $pdf->text(30, 32, utf8_decode("A1 ** A3, B1 ** B3"));
                        $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                        $pdf->text(30, 40, utf8_decode($date));
                        $pdf->text(6, 46, utf8_decode("Giltigt till:"));
                        $pdf->text(30, 46, utf8_decode($dateyr5));

                        $pdf->SetFont('CenturyGothic','',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftfallskyddlur/liftfallskyddlur_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "stallningallman":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningallman/stallningallman_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 20);
                        $pdf->Write(0, utf8_decode("Certifierad:"));                        
                        
                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(51, 153, 255);
                            $pdf->text(41.1, 21.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(51, 153, 255);
                            $pdf->text(30, 21.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 28);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(40, 28);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 38, utf8_decode("Utbildningsdatum:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(41, 38, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 45, utf8_decode("Utbildare:"));
                        $pdf->text(6, 50, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningallman/stallningallman_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "stallningsarskild":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarskild/stallningsarskild_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 20);
                        $pdf->Write(0, utf8_decode("Certifierad:"));
                                                
                        // Check name length
                        if (strlen($full_name) <= 21)
                        {
                            $pdf->SetTextColor(51, 153, 255);
                            $pdf->text(41.1, 21.2, utf8_decode($full_name));
                        }
                        else
                        {
                            $pdf->SetTextColor(51, 153, 255);
                            $pdf->text(30, 21.2, utf8_decode($full_name));
                        }

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 28);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(40, 28);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 38, utf8_decode("Utbildningsdatum:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(41, 38, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 45, utf8_decode("Utbildare:"));
                        $pdf->text(6, 50, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarskild/stallningsarskild_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "stallningsarvader":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarvader/stallningsarvader_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 20);
                        $pdf->Write(0, utf8_decode("Certifierad:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 20);
                        $pdf->Write(0, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 28);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 28);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 38, utf8_decode("Utfärdat:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 38, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',8);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 45, utf8_decode("Utbildare:"));
                        $pdf->text(6, 50, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarvader/stallningsarvader_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "sakralyft":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/sakralyft/sakralyft_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 17.15, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 17.15, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 24, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 24, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 31, utf8_decode("Utfärdat:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 31, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(5, 42, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/sakralyft/sakralyft_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "travers":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/travers/travers_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 16);
                        $pdf->Write(0, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 16, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 24);
                        $pdf->Write(0, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 24);
                        $pdf->Write(0, utf8_decode($personalnumber));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(5, 32);
                        $pdf->Write(0, utf8_decode("Utfärdat:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(38, 32);
                        $pdf->Write(0, utf8_decode($date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(6, 44, utf8_decode("Utbildare: ".$teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/travers/travers_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "truck":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truck/truck_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 15, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 15, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 21, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 21, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(5, 50, utf8_decode("Utbildare: "));
                        $pdf->text(5, 53, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truck/truck_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "trucka2a4":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4/trucka2a4_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 15, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 15, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 21, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 21, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(5, 50, utf8_decode("Utbildare: "));
                        $pdf->text(5, 53, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4/trucka2a4_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "trucka2a4b1":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4b1/trucka2a4b1_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 15, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 15, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 21, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 21, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(5, 50, utf8_decode("Utbildare: "));
                        $pdf->text(5, 53, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4b1/trucka2a4b1_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                case "truckb1":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truckb1/truckb1_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothicBold','B',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 15, utf8_decode("Namn:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 15, utf8_decode($full_name));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(6, 21, utf8_decode("Personnummer:"));

                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(39, 21, utf8_decode($personalnumber));

                        $pdf->SetFont('CenturyGothic','',11);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                        $pdf->SetFont('CenturyGothicBold','B',9);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->text(5, 50, utf8_decode("Utbildare: "));
                        $pdf->text(5, 53, utf8_decode($teacher->teachers));

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truckb1/truckb1_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($cert_generated == '0') {
                                $this->pdf_model->update_cert_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                        }
                    }
                break;
                default:
                    print_r("Certifikat är inaktiverat för detta utbildningstillfälle.");
                break;
            }
            
        }
    }
    
    public function generate_diploma($event_id = null, $participant_id = null)
    {
        $formd = array(
            'event_id' => $event_id,
            'participant_id' => $participant_id            
        );

        $this->form_validation->set_data($formd);
        
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric');
		$this->form_validation->set_rules('participant_id', 'ID', 'required|numeric');
        
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            $event_id = $this->security->xss_clean($event_id);
            $participant_id = $this->security->xss_clean($participant_id);
            
            $event_data = $this->pdf_model->get_course_event($event_id);
            $course_data = $this->pdf_model->get_course($event_data->course_id);
            $participant_data = $this->pdf_model->get_participant($participant_id);
            $participant_event_details = $this->pdf_model->get_participant_event_details($event_id, $participant_id);
            
            $full_name = trim($participant_data->full_name);
            $personalnumber = $participant_data->personalnumber;
            $fp_combined = $full_name." ".$personalnumber;
            $teacher = $this->pdf_model->get_teachers($event_id);
            $fname = str_replace(" ", "", $full_name);
            $fname = strtolower($fname);
            $fname = $fname.".pdf";
            $city = $event_data->city;
            
            $diploma_generated = $participant_event_details->diploma_generated;
            
            // Date from course date
            $date = substr($event_data->course_date, 0, 10);
            $dateyr3 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 3 year"));
            $dateyr5 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 5 year"));
                        
            $diploma_template = $course_data->diploma_template;
            
            $error = false;
            
            require_once(APPPATH.'third_party/fpdi/fpdf.php');
            require_once(APPPATH.'third_party/fpdi/textbox.php');
            require_once(APPPATH.'third_party/fpdi/fpdi.php');
            
            switch($diploma_template)
            {
                case "ab04abt06":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ab04abt06/ab04abt06_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 171);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 177);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ab04abt06/ab04abt06_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "abs09":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/abs09/abs09_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 171);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 177);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/abs09/abs09_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "allmanstallning":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/allmanstallning/allmanstallning_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "amaanlaggning":
                    try {    
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/amaanlaggning/amaanlaggning_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/amaanlaggning/amaanlaggning_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "ams":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ams/ams_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ams/ams_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "apv12":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv12/apv12_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 190);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv12/apv12_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "apv123a":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123a/apv123a_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 190);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123a/apv123a_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "apv123b":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123b/apv123b_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 190);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123b/apv123b_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "apv3a":
                    try {    
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3a/apv3a_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 98);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3a/apv3a_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "apv3b":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3b/apv3b_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3b/apv3b_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "arbetsmiljoansvar":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/arbetsmiljoansvar/arbetsmiljoansvar_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/arbetsmiljoansvar/arbetsmiljoansvar_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "atex":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/atex/atex_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "bamansvar":
                    try {    
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/bamansvar/bamansvar_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/bamansvar/bamansvar_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "baspu":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/baspu/baspu_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 117);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "baspugosta":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/baspugosta/baspugosta_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 118);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/baspugosta/baspugosta_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "digitalfardskrivare":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/digitalfardskrivare/digitalfardskrivare_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 171);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 177);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/digitalfardskrivare/digitalfardskrivare_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "elansvar":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elansvar/elansvar_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 98);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elansvar/elansvar_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "elinstskotsel":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elinstskotsel/elinstskotsel_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 109);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "elsakerhetbegrelinstall":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetbegrelinstall/elsakerhetbegrelinstall_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 108);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetbegrelinstall/elsakerhetbegrelinstall_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "fliv7":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/fliv7/fliv7_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                        
                        $pdf->SetFont('Arial','B',22);
                        $pdf->SetTextColor(0, 0, 0);
                        
                        $pdf->SetXY(0, 112);
                        $pdf->Cell(190,0,utf8_decode($full_name),0,1,"C");
                        
                        $pdf->SetXY(0, 135);
                        $pdf->Cell(190,0,utf8_decode("Skötsel av elanläggningar"),0,1,"C");

                        $pdf->SetFont('Arial','',11);
                        $pdf->SetTextColor(0, 0, 0);
                        
                        $pdf->SetXY(22, 161);
                        $pdf->Cell(210,0,utf8_decode("7,5 timmar"),0,1,"L");
                        
                        $pdf->SetXY(22, 180);
                        $pdf->Cell(210,0,utf8_decode("Det elektrisk regelverket SSEN-50110-1 utgåva 3 ".chr(127)." ELSÄK-FS 2006:1"),0,1,"L");
                        
                        $pdf->SetXY(22, 185);
                        $pdf->Cell(210,0,utf8_decode("Arbetsgivarens ansvar för elarbetet ".chr(127)." Innehavarens ansvar för elanläggningen"),0,1,"L");
                        
                        $pdf->SetXY(22, 190);
                        $pdf->Cell(210,0,utf8_decode("Innehavarens ansvar för elanläggningen ".chr(127)." Riskbedömning ".chr(127)." Varselmärkning"),0,1,"L");
                        
                        $pdf->SetXY(22, 195);
                        $pdf->Cell(210,0,utf8_decode("Skötselåtgärder och arbetsmetoder ".chr(127)." Elfaran & Elolycksfall"),0,1,"L");
                        
                        $pdf->SetXY(0, 205);
                        $pdf->Cell(190,0,utf8_decode($city." - ".$date),0,1,"C");
                        
                        $pdf->SetFont('Arial','B',11);
                        $pdf->SetTextColor(0, 0, 0);
                        
                        $pdf->SetXY(0, 240);
                        $pdf->Cell(190,0,utf8_decode("Robin Persson"),0,1,"C");
                        
                        $pdf->SetFont('Arial','',11);
                        $pdf->SetTextColor(0, 0, 0);
                        
                        $pdf->SetXY(0, 245);
                        $pdf->Cell(190,0,utf8_decode("Robin Alsterberg - Svensk Uppdragsutbildning"),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "nyaelinstregler":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/nyaelinstregler/nyaelinstregler_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 113);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "elinstregler":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elinstregler/elinstregler_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 113);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "elinstreglerbravida":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elinstreglerbravida/elinstreglerbravida_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 113);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "elsakerhetdriftp":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetdriftp/elsakerhetdriftp_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetdriftp/elsakerhetdriftp_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "entrjuridik2":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/entrjuridik2/entrjuridik2_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/entrjuridik2/entrjuridik2_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "esa14":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esa14/esa14_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                        
                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "esa14tilltrade":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esa14tilltrade/esa14tilltrade_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                        
                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "esavattenvagar":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esavattenvagar/esavattenvagar_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                        
                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "esaindustri":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esaindustri/esaindustri_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                        
                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "esaroj":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esaroj/esaroj_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                        
                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "forstaforband":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/forstaforband/forstaforband_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 117);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 188);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 194);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "fallskydd":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/fallskydd/fallskydd_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 176);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 182);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/fallskydd/fallskydd_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hlrforsta":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrforsta/hlrforsta_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 174);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hlrhjartstartare":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrhjartstartare/hlrhjartstartare_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 125);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 174);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hlrhjart":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrhjart/hlrhjart_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 174);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hlrelskada":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrelskada/hlrelskada_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 174);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hlr":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlr/hlr_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 114);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 168);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 174);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hemocue":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocue/hemocue_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 106);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocue/hemocue_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hemocuebasic":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocuebasic/hemocuebasic_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 106);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocuebasic/hemocuebasic_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "hardplast":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hardplast/hardplast_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 106);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hardplast/hardplast_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "instrueradpersskoselel":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/instrueradpersskoselel/instrueradpersskoselel_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "kfid":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/kfid/kfid_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "kfidbravida":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/kfidbravida/kfidbravida_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "kj4115":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/kj4115/kj4115_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 115);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "lastsakring":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/lastsakring/lastsakring_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 115);
                        $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 190);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/lastsakring/lastsakring_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "meranlaggning":
                    try {    
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/meranlaggning/meranlaggning_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 170);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 180);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/meranlaggning/meranlaggning_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "nbrf":
                    try 
                    {                                            
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/nbrf/nbrf_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 128);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 190);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/nbrf/nbrf_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "olycksfall":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/olycksfall/olycksfall_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 107);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 171);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 177);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "eltekendag":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekendag/eltekendag_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 98);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekendag/eltekendag_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "eltekfastighetsskotare":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 115);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "eltekfastighetsskotare":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 115);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "eltekva":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekva/eltekva_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 106);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekva/eltekva_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "sip":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/sip/sip_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 117);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");                

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "skotselelanl":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/skotselelanl/skotselelanl_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 113);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");                

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "skotselelanlbravida":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/skotselelanlbravida/skotselelanlbravida_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 113);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");                

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
            break;
                case "stallningsarskild":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/stallningsarskild/stallningsarskild_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 113);
                        $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 172);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 178);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 184);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");                

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "projektledning":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/projektledning/projektledning_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 171);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 177);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/projektledning/projektledning_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                case "pblbbr":
                    try {
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/pblbbr/pblbbr_front.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                        $pdf->SetFont('CenturyGothic','',22);
                        $pdf->SetTextColor(51, 153, 255);
                        $pdf->SetXY(0, 116);
                        $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                        $pdf->SetFont('CenturyGothic','',12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(0, 171);
                        $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                        $pdf->SetXY(0, 177);
                        $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                        $pdf->SetXY(0, 183);
                        $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                        $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/pblbbr/pblbbr_back.pdf');
                        $tplIdx = $pdf->importPage(1);
                        $pdf->addPage();
                        $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                        $pdf->Output("D", utf8_decode($fname));
                    }
                    catch(Exception $e) {
                        $error = true;
                    }
                    finally {
                        if ($error != true) {
                            if ($diploma_generated == '0') {
                                $this->pdf_model->update_diploma_status($event_id, $participant_id);
                            }
                        }
                        else {
                            print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                        }
                    }
                break;
                default:
                    print_r("Intyg är inaktiverat för detta utbildningstillfälle.");
                    break;
            }
        }
    }
    
    public function generate_multiple_cert($event_id = null)
    {
        // Prepare virtual post values
        $formd = array(
            'event_id' => $event_id
        );

        $this->form_validation->set_data($formd);
        
        // Set validation rules
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric');
        
        // Perform the validation
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            // Clean the event_id
            $event_id = $this->security->xss_clean($event_id);
            
            // Get participants
            $participants = $this->pdf_model->get_by_event($event_id);
            
            // Count the array and init a counter
            $num_participants = count($participants);
            $counter = 1;
            
            // Do not run logic if there isn't any participants
            if ($num_participants != 0)
            {
                // Get event specific data
                $event_data = $this->pdf_model->get_course_event($event_id);
                $course_data = $this->pdf_model->get_course($event_data->course_id);                        

                // Date from course date
                $date = substr($event_data->course_date, 0, 10);
                $dateyr3 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 3 year"));
                $dateyr5 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 5 year"));

                // Get teachers
                $teacher = $this->pdf_model->get_teachers($event_id);

                // Get templates
                $cert_template = $course_data->cert_template;

                // Include required files for the pdf generation
                require_once(APPPATH.'third_party/fpdi/fpdf.php');
                require_once(APPPATH.'third_party/fpdi/textbox.php');
                require_once(APPPATH.'third_party/fpdi/fpdi.php');

                // Set the error bool
                $error = false;
                $inactive = false;
                
                // Create filename
                $filename = "certifikat_".date('Ymd').".pdf";

                // Create a new PDF
                $pdf = new FPDI();

                // Loop through the participants
                foreach ($participants as $participant)
                {
                    // Get event details for this participant
                    $participant_event_details = $this->pdf_model->get_participant_event_details($event_id, $participant->id);

                    // Create name
                    $full_name = trim($participant->first_name)." ".trim($participant->last_name);
                    $personalnumber = $participant->personalnumber;
                    $fp_combined = $full_name." ".$personalnumber;

                    // Check if cert is already generated
                    $cert_generated = $participant_event_details->cert_generated;

                    switch($cert_template)
                    {
                        case "adr13":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/adr13/adr_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 16, utf8_decode("Namn:"));
                                $pdf->text(48, 16, utf8_decode($full_name));

                                $pdf->text(10, 26, utf8_decode("Personnummer:"));
                                $pdf->text(48, 26, utf8_decode($personalnumber));

                                $pdf->text(10, 36, utf8_decode("Utfärdat:"));
                                $pdf->text(48, 36, utf8_decode($date));                                        

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(10, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/adr13/adr_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);                            
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "ams":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/ams/ams_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 16, utf8_decode("Certifierad:"));
                                $pdf->text(53, 16, utf8_decode($full_name));

                                $pdf->text(10, 24, utf8_decode("Personnummer:"));
                                $pdf->text(53, 24, utf8_decode($personalnumber));

                                $pdf->text(10, 31, utf8_decode("Utbildningsdatum:"));
                                $pdf->text(53, 31, utf8_decode($date));

                                $pdf->text(10, 34, utf8_decode("Uppdateras rek. senast:"));
                                $pdf->text(53, 34, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(10, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/ams/ams_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv12":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv12/apv12_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 13);
                                $pdf->Write(0, utf8_decode($full_name));
                                $pdf->SetXY(48, 13);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 20, utf8_decode("Utbildning"));
                                $pdf->text(50, 20, utf8_decode("Utfärdat"));
                                $pdf->text(14, 24, utf8_decode("APV 1"));
                                $pdf->text(14, 29, utf8_decode("APV 2"));
                                $pdf->text(14, 34, utf8_decode("APV 3A"));
                                $pdf->text(14, 39, utf8_decode("APV 3B"));
                                $pdf->text(14, 44, utf8_decode("SPV"));

                                $pdf->text(50, 24, utf8_decode($date));
                                $pdf->text(50, 29, utf8_decode($date));
                                $pdf->text(50, 44, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv12/apv12_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv123a":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123a/apv123a_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 13);
                                $pdf->Write(0, utf8_decode($full_name));
                                $pdf->SetXY(48, 13);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 20, utf8_decode("Utbildning"));
                                $pdf->text(50, 20, utf8_decode("Utfärdat"));
                                $pdf->text(14, 24, utf8_decode("APV 1"));
                                $pdf->text(14, 29, utf8_decode("APV 2"));
                                $pdf->text(14, 34, utf8_decode("APV 3A"));
                                $pdf->text(14, 39, utf8_decode("APV 3B"));
                                $pdf->text(14, 44, utf8_decode("SPV"));

                                $pdf->text(50, 24, utf8_decode($date));
                                $pdf->text(50, 29, utf8_decode($date));
                                $pdf->text(50, 34, utf8_decode($date));
                                $pdf->text(50, 44, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123a/apv123a_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv123b":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123b/apv123b_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 13);
                                $pdf->Write(0, utf8_decode($full_name));
                                $pdf->SetXY(48, 13);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 20, utf8_decode("Utbildning"));
                                $pdf->text(50, 20, utf8_decode("Utfärdat"));
                                $pdf->text(14, 23.5, utf8_decode("APV 1"));
                                $pdf->text(14, 27.5, utf8_decode("APV 2"));
                                $pdf->text(14, 31.5, utf8_decode("APV 3A"));
                                $pdf->text(14, 36, utf8_decode("APV 3B"));
                                $pdf->text(14, 40, utf8_decode("SPV"));

                                $pdf->text(50, 23.5, utf8_decode($date));
                                $pdf->text(50, 27.5, utf8_decode($date));
                                $pdf->text(50, 36, utf8_decode($date));
                                $pdf->text(50, 40, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv123b/apv123b_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv3ab":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3ab/apv3ab_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 13);
                                $pdf->Write(0, utf8_decode($full_name));
                                $pdf->SetXY(48, 13);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 20, utf8_decode("Utbildning"));
                                $pdf->text(50, 20, utf8_decode("Utfärdat"));
                                $pdf->text(14, 24, utf8_decode("APV 1"));
                                $pdf->text(14, 29, utf8_decode("APV 2"));
                                $pdf->text(14, 34, utf8_decode("APV 3A"));
                                $pdf->text(14, 39, utf8_decode("APV 3B"));
                                $pdf->text(14, 44, utf8_decode("SPV"));

                                $pdf->text(50, 24, utf8_decode($date));
                                $pdf->text(50, 29, utf8_decode($date));
                                $pdf->text(50, 34, utf8_decode($date));
                                $pdf->text(50, 39, utf8_decode($date));
                                $pdf->text(50, 44, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3ab/apv3ab_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv3a":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3a/apv3a_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 13);
                                $pdf->Write(0, utf8_decode($full_name));
                                $pdf->SetXY(48, 13);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 20, utf8_decode("Utbildning"));
                                $pdf->text(53, 20, utf8_decode("Utfärdat"));
                                $pdf->text(14, 24, utf8_decode("APV 1"));
                                $pdf->text(14, 29, utf8_decode("APV 2"));
                                $pdf->text(14, 34, utf8_decode("APV 3A"));
                                $pdf->text(14, 39, utf8_decode("APV 3B"));
                                $pdf->text(14, 44, utf8_decode("SPV"));

                                $pdf->text(53, 34, utf8_decode($date));
                                $pdf->text(53, 44, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3a/apv3a_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv3b":
                            try {
                                $pdf = new FPDI();
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3b/apv3b_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 13);
                                $pdf->Write(0, utf8_decode($full_name));
                                $pdf->SetXY(48, 13);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(10, 20, utf8_decode("Utbildning"));
                                $pdf->text(50, 20, utf8_decode("Utfärdat"));
                                $pdf->text(14, 24, utf8_decode("APV 1"));
                                $pdf->text(14, 29, utf8_decode("APV 2"));
                                $pdf->text(14, 34, utf8_decode("APV 3A"));
                                $pdf->text(14, 39, utf8_decode("APV 3B"));
                                $pdf->text(14, 44, utf8_decode("SPV"));
                                $pdf->text(50, 39, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',7);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(14, 50, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/apv3b/apv3b_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esa14industri":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14industri/esa14industri_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(40, 17);
                                $pdf->Write(0, utf8_decode($full_name));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(40, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                                $pdf->text(41, 33, utf8_decode($date));
                                $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                                $pdf->text(41, 39, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 48, utf8_decode("Utbildare:"));
                                $pdf->text(6, 52, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14industri/esa14industri_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esa14":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14/esa14_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(41.1, 17.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(30, 17.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(40, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                                $pdf->text(41, 33, utf8_decode($date));
                                $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                                $pdf->text(41, 39, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 48, utf8_decode("Utbildare:"));
                                $pdf->text(6, 52, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14/esa14_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esa14tilltrade":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14tilltrade/esa14tilltrade_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(41.1, 17.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(30, 17.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(40, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                                $pdf->text(41, 33, utf8_decode($date));
                                $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                                $pdf->text(41, 39, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 48, utf8_decode("Utbildare:"));
                                $pdf->text(6, 52, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esa14tilltrade/esa14tilltrade_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esaroj":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esaroj/esaroj_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(39.1, 18.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(30, 18.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(38, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 33, utf8_decode("Utfärdat:"));
                                $pdf->text(39, 33, utf8_decode($date));
                                $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                                $pdf->text(39, 39, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 48, utf8_decode("Utbildare:"));
                                $pdf->text(6, 52, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esaroj/esaroj_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esavattenvagar":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/esavattenvagar/esavattenvagar_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(39.1, 18.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(30, 18.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(38, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 33, utf8_decode("Utfärdat:"));
                                $pdf->text(39, 33, utf8_decode($date));
                                $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                                $pdf->text(39, 39, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 48, utf8_decode("Utbildare:"));
                                $pdf->text(6, 52, utf8_decode($teacher->teachers));
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elsakerhetbegrelinstall":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/elsakerhetbegrelinstall/elsakerhetbegrelinstall_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 16);
                                $pdf->Write(0, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 16, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 24);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 24);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 32);
                                $pdf->Write(0, utf8_decode("Utfärdat:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 32);
                                $pdf->Write(0, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 44, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/elsakerhetbegrelinstall/elsakerhetbegrelinstall_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "fallskydd":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/fallskydd/fallskydd_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 16, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(30, 16, utf8_decode($full_name));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 24, utf8_decode("Personnummer:"));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(40, 24, utf8_decode($personalnumber));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 32, utf8_decode("Utbildningsdatum"));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(40, 32, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(5, 42, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/fallskydd/fallskydd_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hjullastare":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/hjullastare/hjullastare_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 16);
                                $pdf->Write(0, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 16);
                                $pdf->Write(0, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 24);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 24);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 32);
                                $pdf->Write(0, utf8_decode("Utfärdat:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 32);
                                $pdf->Write(0, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 44, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/hjullastare/hjullastare_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "kj4115":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/kj4115/kj4115_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(41.1, 17.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(255, 153, 0);
                                    $pdf->text(30, 17.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(40, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 33, utf8_decode("Utbildningsdatum:"));
                                $pdf->text(41, 33, utf8_decode($date));
                                $pdf->text(6, 39, utf8_decode("Giltigt till:"));
                                $pdf->text(41, 39, utf8_decode($dateyr3));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 48, utf8_decode("Utbildare:"));
                                $pdf->text(6, 52, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/kj4115/kj4115_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "lift":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift/lift_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Behörighet:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 17);
                                $pdf->Write(0, utf8_decode("A1 ** A3, B1 ** B3"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->text(6, 27.2, utf8_decode($full_name));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 30);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                                $pdf->text(30, 40, utf8_decode($date));
                                $pdf->text(6, 44, utf8_decode("Giltigt till:"));
                                $pdf->text(30, 44, utf8_decode($dateyr5));

                                $pdf->SetFont('CenturyGothic','',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift/lift_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "lift3a":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift3a/lift3a_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Behörighet:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 17);
                                $pdf->Write(0, utf8_decode("A3"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->text(6, 27.2, utf8_decode($full_name));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 30);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothicBold','B',10);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                                $pdf->text(30, 40, utf8_decode($date));
                                $pdf->text(6, 44, utf8_decode("Giltigt till:"));
                                $pdf->text(30, 44, utf8_decode($dateyr5));

                                $pdf->SetFont('CenturyGothic','',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/lift3a/lift3a_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "liftlur":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlur/liftlur_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->text(39, 18.2, utf8_decode($full_name));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(38, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',10);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 32, utf8_decode("Behörighet:"));
                                $pdf->text(30, 32, utf8_decode("A1 ** A3, B1 ** B3"));
                                $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                                $pdf->text(30, 40, utf8_decode($date));
                                $pdf->text(6, 46, utf8_decode("Giltigt till:"));
                                $pdf->text(30, 46, utf8_decode($dateyr5));

                                $pdf->SetFont('CenturyGothic','',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlur/liftlur_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "liftlurtorbjornalla":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlurtorbjornalla/liftlurtorbjornalla_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(38, 17);
                                $pdf->Write(0, utf8_decode($full_name));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(38, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',10);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 32, utf8_decode("Behörighet:"));
                                $pdf->text(30, 32, utf8_decode("A1 A2 A3, B1 B2 B3"));
                                $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                                $pdf->text(30, 40, utf8_decode($date));
                                $pdf->text(6, 46, utf8_decode("Giltigt till:"));
                                $pdf->text(30, 46, utf8_decode($dateyr5));

                                $pdf->SetFont('CenturyGothic','',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftlurtorbjornalla/liftlurtorbjornalla_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "liftfallskyddlur":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftfallskyddlur/liftfallskyddlur_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 17);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->text(39, 18.2, utf8_decode($full_name));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(5, 23);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(255, 153, 0);
                                $pdf->SetXY(38, 23);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',10);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 32, utf8_decode("Behörighet:"));
                                $pdf->text(30, 32, utf8_decode("A1 ** A3, B1 ** B3"));
                                $pdf->text(6, 40, utf8_decode("Utfärdat:"));
                                $pdf->text(30, 40, utf8_decode($date));
                                $pdf->text(6, 46, utf8_decode("Giltigt till:"));
                                $pdf->text(30, 46, utf8_decode($dateyr5));

                                $pdf->SetFont('CenturyGothic','',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 52, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/liftfallskyddlur/liftfallskyddlur_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "stallningallman":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningallman/stallningallman_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 20);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(51, 153, 255);
                                    $pdf->text(41.1, 21.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(51, 153, 255);
                                    $pdf->text(30, 21.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 28);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(40, 28);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 38, utf8_decode("Utbildningsdatum:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(41, 38, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 45, utf8_decode("Utbildare:"));
                                $pdf->text(6, 50, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningallman/stallningallman_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "stallningsarskild":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarskild/stallningsarskild_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 20);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                // Check name length
                                if (strlen($full_name) <= 21)
                                {
                                    $pdf->SetTextColor(51, 153, 255);
                                    $pdf->text(41.1, 21.2, utf8_decode($full_name));
                                }
                                else
                                {
                                    $pdf->SetTextColor(51, 153, 255);
                                    $pdf->text(30, 21.2, utf8_decode($full_name));
                                }

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 28);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(40, 28);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 38, utf8_decode("Utbildningsdatum:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(41, 38, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 45, utf8_decode("Utbildare:"));
                                $pdf->text(6, 50, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarskild/stallningsarskild_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "stallningsarvader":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarvader/stallningsarvader_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 20);
                                $pdf->Write(0, utf8_decode("Certifierad:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 20);
                                $pdf->Write(0, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 28);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 28);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 38, utf8_decode("Utfärdat:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 38, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',8);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 45, utf8_decode("Utbildare:"));
                                $pdf->text(6, 50, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/stallningsarvader/stallningsarvader_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "sakralyft":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/sakralyft/sakralyft_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 17, utf8_decode("Namn:"));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 17, utf8_decode($full_name));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 24, utf8_decode("Personnummer:"));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 24, utf8_decode($personalnumber));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 31, utf8_decode("Utfärdat:"));
                                
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 31, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(5, 42, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/sakralyft/sakralyft_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "travers":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/travers/travers_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 16);
                                $pdf->Write(0, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 16, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 24);
                                $pdf->Write(0, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 24);
                                $pdf->Write(0, utf8_decode($personalnumber));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(5, 32);
                                $pdf->Write(0, utf8_decode("Utfärdat:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(38, 32);
                                $pdf->Write(0, utf8_decode($date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(6, 44, utf8_decode("Utbildare: ".$teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/travers/travers_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "truck":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truck/truck_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 15, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 15, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 21, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 21, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(5, 50, utf8_decode("Utbildare: "));
                                $pdf->text(5, 53, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truck/truck_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "trucka2a4":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4/trucka2a4_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 15, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 15, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 21, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 21, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(5, 50, utf8_decode("Utbildare: "));
                                $pdf->text(5, 53, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4/trucka2a4_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "trucka2a4b1":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4b1/trucka2a4b1_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 15, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 15, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 21, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 21, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(5, 50, utf8_decode("Utbildare: "));
                                $pdf->text(5, 53, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/trucka2a4b1/trucka2a4b1_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        case "truckb1":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truckb1/truckb1_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','B','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothicBold','B',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 15, utf8_decode("Namn:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 15, utf8_decode($full_name));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(6, 21, utf8_decode("Personnummer:"));

                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(39, 21, utf8_decode($personalnumber));

                                $pdf->SetFont('CenturyGothic','',11);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->text(5, 45, utf8_decode("Utfärdat: ".$date));

                                $pdf->SetFont('CenturyGothicBold','B',9);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->text(5, 50, utf8_decode("Utbildare: "));
                                $pdf->text(5, 53, utf8_decode($teacher->teachers));

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/cert/truckb1/truckb1_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($cert_generated == '0') {
                                        $this->pdf_model->update_cert_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte certifikatet genereras. Försök igen.");
                                }
                            }
                        break;
                        default:
                            $error = true;
                            $inactive = true;
                        break;
                    }

                    if ($counter == $num_participants)
                    {
                        if ($error != true)
                        {
                            // Update certdip_sent before output since headers will be thrown
                            $this->course_event_model->update(array('certdip_sent' => 1), $event_id);
                            
                            $pdf->Output("D", utf8_decode($filename));
                        }
                    }

                    $counter++;
                }
                if ($inactive != false)
                {
                    print_r("Certifikat är inaktiverat för detta utbildningstillfälle.");
                }
            }
            else
            {
                print_r("Inga deltagare är kopplade till detta utbildningstillfälle.");
            }
        }
    }
    
    public function generate_multiple_diploma($event_id = null)
    {
        // Prepare virtual post values
        $formd = array(
            'event_id' => $event_id
        );

        $this->form_validation->set_data($formd);
        
        // Set validation rules
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric');
        
        // Perform the validation
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else
        {
            // Clean the event_id
            $event_id = $this->security->xss_clean($event_id);
            
            // Get participants
            $participants = $this->pdf_model->get_by_event($event_id);
            
            // Count the array and init a counter
            $num_participants = count($participants);
            $counter = 1;
            
            // Do not run logic if there isn't any participants
            if ($num_participants != 0)
            {
                // Get event specific data
                $event_data = $this->pdf_model->get_course_event($event_id);
                $course_data = $this->pdf_model->get_course($event_data->course_id);

                // Date from course date
                $date = substr($event_data->course_date, 0, 10);
                $dateyr3 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 3 year"));
                $dateyr5 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 5 year"));
                
                // Get city
                $city = $event_data->city;

                // Get teachers
                $teacher = $this->pdf_model->get_teachers($event_id);

                // Get templates
                $diploma_template = $course_data->diploma_template;

                // Include required files for the pdf generation
                require_once(APPPATH.'third_party/fpdi/fpdf.php');
                require_once(APPPATH.'third_party/fpdi/textbox.php');
                require_once(APPPATH.'third_party/fpdi/fpdi.php');

                // Set the error bool
                $error = false;
                $inactive = false;
                
                // Create filename
                $filename = "intyg_".date('Ymd').".pdf";

                // Create a new PDF
                $pdf = new FPDI();
                
                // Loop through the participants
                foreach ($participants as $participant)
                {
                    // Get event details for this participant
                    $participant_event_details = $this->pdf_model->get_participant_event_details($event_id, $participant->id);

                    // Create name
                    $full_name = trim($participant->first_name)." ".trim($participant->last_name);
                    $personalnumber = $participant->personalnumber;
                    $fp_combined = $full_name." ".$personalnumber;

                    // Check if cert is already generated
                    $diploma_generated = $participant_event_details->diploma_generated;
                    
                    switch($diploma_template)
                    {
                        case "ab04abt06":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ab04abt06/ab04abt06_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 171);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 177);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ab04abt06/ab04abt06_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "abs09":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/abs09/abs09_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 171);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 177);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/abs09/abs09_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "allmanstallning":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/allmanstallning/allmanstallning_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "amaanlaggning":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/amaanlaggning/amaanlaggning_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/amaanlaggning/amaanlaggning_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "ams":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ams/ams_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/ams/ams_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv12":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv12/apv12_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 190);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv12/apv12_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv123a":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123a/apv123a_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 190);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123a/apv123a_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv123b":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123b/apv123b_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 190);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv123b/apv123b_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv3a":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3a/apv3a_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 98);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3a/apv3a_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "apv3b":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3b/apv3b_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/apv3b/apv3b_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "arbetsmiljoansvar":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/arbetsmiljoansvar/arbetsmiljoansvar_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/arbetsmiljoansvar/arbetsmiljoansvar_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "atex":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/atex/atex_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "bamansvar":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/bamansvar/bamansvar_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/bamansvar/bamansvar_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "baspu":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/baspu/baspu_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 117);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 188);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 194);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "baspugosta":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/baspugosta/baspugosta_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 118);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/baspugosta/baspugosta_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "digitalfardskrivare":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/digitalfardskrivare/digitalfardskrivare_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 171);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 177);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/digitalfardskrivare/digitalfardskrivare_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);                                
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "olycksfall":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/olycksfall/olycksfall_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 171);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 177);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elansvar":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elansvar/elansvar_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 98);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elansvar/elansvar_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elsakerhetbegrelinstall":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetbegrelinstall/elsakerhetbegrelinstall_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 108);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetbegrelinstall/elsakerhetbegrelinstall_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elinstskotsel":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elinstskotsel/elinstskotsel_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 109);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "nyaelinstregler":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/nyaelinstregler/nyaelinstregler_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 113);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elinstregler":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elinstregler/elinstregler_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 113);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elinstreglerbravida":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elinstreglerbravida/elinstreglerbravida_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 113);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "elsakerhetdriftp":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetdriftp/elsakerhetdriftp_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/elsakerhetdriftp/elsakerhetdriftp_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "entrjuridik2":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/entrjuridik2/entrjuridik2_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/entrjuridik2/entrjuridik2_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esa14":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esa14/esa14_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                                
                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esa14tilltrade":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esa14tilltrade/esa14tilltrade_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                                
                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esavattenvagar":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esavattenvagar/esavattenvagar_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                                
                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esaindustri":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esaindustri/esaindustri_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                                
                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "esaroj":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/esaroj/esaroj_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");
                                
                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "fallskydd":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/fallskydd/fallskydd_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 176);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 182);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/fallskydd/fallskydd_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "fliv7":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/fliv7/fliv7_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->SetFont('Arial','B',22);
                                $pdf->SetTextColor(0, 0, 0);

                                $pdf->SetXY(0, 112);
                                $pdf->Cell(190,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetXY(0, 135);
                                $pdf->Cell(190,0,utf8_decode("Skötsel av elanläggningar"),0,1,"C");

                                $pdf->SetFont('Arial','',11);
                                $pdf->SetTextColor(0, 0, 0);

                                $pdf->SetXY(22, 161);
                                $pdf->Cell(210,0,utf8_decode("7,5 timmar"),0,1,"L");

                                $pdf->SetXY(22, 180);
                                $pdf->Cell(210,0,utf8_decode("Det elektrisk regelverket SSEN-50110-1 utgåva 3 ".chr(127)." ELSÄK-FS 2006:1"),0,1,"L");

                                $pdf->SetXY(22, 185);
                                $pdf->Cell(210,0,utf8_decode("Arbetsgivarens ansvar för elarbetet ".chr(127)." Innehavarens ansvar för elanläggningen"),0,1,"L");

                                $pdf->SetXY(22, 190);
                                $pdf->Cell(210,0,utf8_decode("Innehavarens ansvar för elanläggningen ".chr(127)." Riskbedömning ".chr(127)." Varselmärkning"),0,1,"L");

                                $pdf->SetXY(22, 195);
                                $pdf->Cell(210,0,utf8_decode("Skötselåtgärder och arbetsmetoder ".chr(127)." Elfaran & Elolycksfall"),0,1,"L");

                                $pdf->SetXY(0, 205);
                                $pdf->Cell(190,0,utf8_decode($city." - ".$date),0,1,"C");

                                $pdf->SetFont('Arial','B',11);
                                $pdf->SetTextColor(0, 0, 0);

                                $pdf->SetXY(0, 240);
                                $pdf->Cell(190,0,utf8_decode("Robin Persson"),0,1,"C");

                                $pdf->SetFont('Arial','',11);
                                $pdf->SetTextColor(0, 0, 0);

                                $pdf->SetXY(0, 245);
                                $pdf->Cell(190,0,utf8_decode("Robin Alsterberg - Svensk Uppdragsutbildning"),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hlrhjartstartare":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrhjartstartare/hlrhjartstartare_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 125);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 174);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hlrhjart":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrhjart/hlrhjart_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 174);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hlrforsta":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrforsta/hlrforsta_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 174);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hlrelskada":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlrelskada/hlrelskada_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 174);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hlr":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hlr/hlr_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 114);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 168);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 174);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hemocue":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocue/hemocue_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 106);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocue/hemocue_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);                                
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hemocuebasic":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocuebasic/hemocuebasic_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 106);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hemocuebasic/hemocuebasic_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "hardplast":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hardplast/hardplast_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 106);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/hardplast/hardplast_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "instrueradpersskoselel":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/instrueradpersskoselel/instrueradpersskoselel_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "kfid":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/kfid/kfid_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "kj4115":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/kj4115/kj4115_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 115);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "kfidbravida":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/kfidbravida/kfidbravida_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "lastsakring":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/lastsakring/lastsakring_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 115);
                                $pdf->Cell(210,0,utf8_decode($full_name." ".$personalnumber),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 190);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/lastsakring/lastsakring_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "meranlaggning":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/meranlaggning/meranlaggning_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 107);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 170);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 180);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/meranlaggning/meranlaggning_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "nbrf":
                            try 
                            {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/nbrf/nbrf_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 128);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 190);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/nbrf/nbrf_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "eltekendag":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekendag/eltekendag_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 98);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekendag/eltekendag_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "eltekfastighetsskotare":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 115);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "eltekfastighetsskotare":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 115);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekfastighetsskotare/eltekfastighetsskotare_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "eltekva":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekva/eltekva_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 106);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/eltekva/eltekva_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "sip":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/sip/sip_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 117);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");                                
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "skotselelanl":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/skotselelanl/skotselelanl_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 113);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "skotselelanlbravida":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/skotselelanlbravida/skotselelanlbravida_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 113);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "stallningsarskild":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/stallningsarskild/stallningsarskild_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->AddFont('CenturyGothicBold','','CenturyGothicBold.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 113);
                                $pdf->Cell(210,0,utf8_decode($fp_combined),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 172);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 178);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 184);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "projektledning":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/projektledning/projektledning_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 171);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 177);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/projektledning/projektledning_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        case "pblbbr":
                            try {
                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/pblbbr/pblbbr_front.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);

                                $pdf->AddFont('CenturyGothic','','CenturyGothic.php');
                                $pdf->SetFont('CenturyGothic','',22);
                                $pdf->SetTextColor(51, 153, 255);
                                $pdf->SetXY(0, 116);
                                $pdf->Cell(210,0,utf8_decode($full_name),0,1,"C");

                                $pdf->SetFont('CenturyGothic','',12);
                                $pdf->SetTextColor(0, 0, 0);
                                $pdf->SetXY(0, 171);
                                $pdf->Cell(210,0,utf8_decode($date),0,1,"C");

                                $pdf->SetXY(0, 177);
                                $pdf->Cell(210,0,utf8_decode("Utbildare"),0,1,"C");

                                $pdf->SetXY(0, 183);
                                $pdf->Cell(210,0,utf8_decode($teacher->teachers),0,1,"C");

                                $pageCount = $pdf->setSourceFile(FCPATH.'assets/pdf/diploma/pblbbr/pblbbr_back.pdf');
                                $tplIdx = $pdf->importPage(1);
                                $pdf->addPage();
                                $pdf->useTemplate($tplIdx, null, null, 0, 0, true);
                            }
                            catch(Exception $e) {
                                $error = true;
                            }
                            finally {
                                if ($error != true) {
                                    if ($diploma_generated == '0') {
                                        $this->pdf_model->update_diploma_status($event_id, $participant->id);
                                    }
                                }
                                else {
                                    print_r("Tyvärr kunde inte intyget genereras. Försök igen.");
                                }
                            }
                        break;
                        default:
                            $error = true;
                            $inactive = true;
                        break;
                    }
                    
                    if ($counter == $num_participants)
                    {
                        if ($error != true)
                        {
                            // Update certdip_sent before output since headers will be thrown
                            $this->course_event_model->update(array('certdip_sent' => 1), $event_id);
                            
                            $pdf->Output("D", utf8_decode($filename));
                        }
                    }

                    $counter++;
                }
                if ($inactive != false)
                {
                    print_r("Intyg är inaktiverat för detta utbildningstillfälle.");
                }
            }
            else
            {
                print_r("Inga deltagare är kopplade till detta utbildningstillfälle.");
            }
        }
    }
    
    public function generate_standard_report()
    {
        $todays_date = date('Y-m-d');
        $todays_time = date('H:i:s');
        $today_dbdate = date('Y-m-d').' 06:00';
        
        $error = false;
        
        $page_open = '<page backtop="5mm" backbottom="30mm" backleft="5mm" backright="5mm" style="font-size:12px;">';
        $page_header = '<page_header><img src="'.base_url('assets/apps/img/logo.png').'" style="width:200px;height:auto;float:right;"></page_header>';
        $page_footer = '<page_footer>
                            <table style="width:100%;color:#808085;">
                                <tr>
                                    <td style="width:33%;">FULL = Fullbokat</td>
                                    <td style="width:33%;"></td>
                                    <td style="width:33%;"></td>
                                </tr>
                                <tr>
                                    <td style="width:33%;">F = Flyttad kurs</td>
                                    <td style="width:33%;"></td>
                                    <td style="width:33%;text-align:right;">'.$todays_time.'</td>
                                </tr>
                                <tr>
                                    <td style="width:33%;"></td>
                                    <td style="width:33%;text-align:center;">'.$todays_date.'</td>
                                    <td style="width:33%;"></td>
                                </tr>
                            </table>
                        </page_footer>';
        $page_close = '</page>';
        
        $page_content = '';
        
        // Courses
        $this->db->select('id, course_name');
        $this->db->from('tbl_course');        
        $this->db->order_by('course_name', 'ASC');
        $query = $this->db->get();
        $courses = $query->result();
        
        foreach ($courses as $course)
        {
            $this->db->select('id, customized, canceled, course_date, city, maximum_participants');
            $this->db->from('tbl_course_event');        
            $this->db->order_by('course_date', 'ASC');
            $this->db->where('course_id',$course->id);
            $this->db->where('course_date >=', $today_dbdate);
            
            $query = $this->db->get();
            $num_rows = $query->num_rows();
            $events = $query->result();
            
            if ($num_rows > 0)
            {
                $page_content .= '<h2>'.$course->course_name.'</h2>';
                $page_content .= '<ul>';
                
                foreach ($events as $event)
                {
                    $event_date = date("d/m-Y", strtotime($event->course_date));                    
                    $full = '';
                    $new_date = '';
                    $customized = '';
                    $total_participants = $this->pdf_model->count_participants($event->id);
                    $amount_participants = '';
                    $teachers = $this->pdf_model->get_teachers($event->id);
                                                            
                    if ($total_participants >= $event->maximum_participants)
                    {
                        $full = '<strong>(FULL)</strong>';
                    }
                    
                    if ($this->pdf_model->event_is_moved($event->id) )
                    {
                        $new_date = '<strong>(F)</strong>';
                    }
                    
                    if ($event->customized == 1)
                    {
                        $customized = '<strong>FTG</strong>';
                    }
                    
                    $amount_participants = '<strong>'.$total_participants.'/'.$event->maximum_participants.'</strong>';
                    
                    if ($event->canceled == 1)
                    {
                        $page_content .= '<li style="text-decoration:line-through;">';
                    }
                    else
                    {
                        $page_content .= '<li>';
                    }
                    
                    $page_content .= ''.$event_date.' - '.$event->city.' | '.$teachers->teachers.' '.$amount_participants.' '.$customized.' '.$full.' '.$new_date.'';
                    $page_content .= '</li>';
                }
                
                $page_content .= '</ul>';
            }
        }
        
        // Build page
        $html = $page_open.$page_header.$page_footer.$page_content.$page_close;
        
        // Filename
        $filename = 'rapport_'.date('Ymd').'.pdf';
        
        try {
            require_once(APPPATH.'third_party/html2pdf/html2pdf.class.php');
            
            $html2pdf = new HTML2PDF('P','A4','en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->WriteHTML($html);
            $html2pdf->Output($filename);            
        }
        catch(Exception $e) {
            $error = true;
        }
        finally {
            if ($error != false) {
                print_r("Tyvärr kunde inte rapporten genereras. Försök igen.");
            }
        }
    }
    
    public function generate_extended_report()
    {
        $todays_date = date('Y-m-d');
        $todays_time = date('H:i:s');
        $today_dbdate = date('Y-m-d').' 06:00';
        
        $error = false;
        
        $page_open = '<page backtop="5mm" backbottom="30mm" backleft="5mm" backright="5mm" style="font-size:12px;">';
        $page_header = '<page_header><img src="'.base_url('assets/apps/img/logo.png').'" style="width:200px;height:auto;float:right;"></page_header>';
        $page_footer = '<page_footer>
                            <table style="width:100%;color:#808085;">
                                <tr>
                                    <td style="width:33%;">FULL = Fullbokat</td>
                                    <td style="width:33%;"></td>
                                    <td style="width:33%;"></td>
                                </tr>
                                <tr>
                                    <td style="width:33%;">F = Flyttad kurs</td>
                                    <td style="width:33%;"></td>
                                    <td style="width:33%;text-align:right;">'.$todays_time.'</td>
                                </tr>
                                <tr>
                                    <td style="width:33%;"></td>
                                    <td style="width:33%;text-align:center;">'.$todays_date.'</td>
                                    <td style="width:33%;"></td>
                                </tr>
                            </table>
                        </page_footer>';
        $page_close = '</page>';
        
        $page_content = '';
        
        // Courses
        $this->db->select('id, course_name');
        $this->db->from('tbl_course');        
        $this->db->order_by('course_name', 'ASC');
        $query = $this->db->get();
        $courses = $query->result();
        
        foreach ($courses as $course)
        {
            $this->db->select('id, customized, canceled, course_date, city, maximum_participants');
            $this->db->from('tbl_course_event');        
            $this->db->order_by('course_date', 'ASC');
            $this->db->where('course_id',$course->id);
            $this->db->where('course_date >=', $today_dbdate);
            
            $query = $this->db->get();
            $num_rows = $query->num_rows();
            $events = $query->result();
            
            if ($num_rows > 0)
            {
                $page_content .= '<h2>'.$course->course_name.'</h2>';
                $page_content .= '<ul>';
                
                foreach ($events as $event)
                {
                    $event_date = date("d/m-Y", strtotime($event->course_date));                    
                    $full = '';
                    $new_date = '';
                    $customized = '';
                    $total_participants = $this->pdf_model->count_participants($event->id);
                    $amount_participants = '';
                    $teachers = $this->pdf_model->get_teachers($event->id);
                    $participant_price = $this->pdf_model->calculate_participant($event->id);
                    $ghost_price = $this->pdf_model->calculate_ghost($event->id);
                    $total_price = $participant_price + $ghost_price;
                    $total_price_format = '<strong>('.$total_price.':-)</strong>';
                                                            
                    if ($total_participants >= $event->maximum_participants)
                    {
                        $full = '<strong>(FULL)</strong>';
                    }
                    
                    if ($this->pdf_model->event_is_moved($event->id) )
                    {
                        $new_date = '<strong>(F)</strong>';
                    }
                    
                    if ($event->customized == 1)
                    {
                        $customized = '<strong>FTG</strong>';
                    }
                    
                    $amount_participants = '<strong>'.$total_participants.'/'.$event->maximum_participants.'</strong>';
                    
                    if ($event->canceled == 1)
                    {
                        $page_content .= '<li style="text-decoration:line-through;">';
                    }
                    else
                    {
                        $page_content .= '<li>';
                    }
                    
                    $page_content .= ''.$event_date.' - '.$event->city.' | '.$teachers->teachers.' '.$amount_participants.' '.$customized.' '.$full.' '.$new_date.' '.$total_price_format.'';
                    $page_content .= '</li>';
                }
                
                $page_content .= '</ul>';
            }
        }
        
        // Build page
        $html = $page_open.$page_header.$page_footer.$page_content.$page_close;
        
        // Filename
        $filename = 'rapport_'.date('Ymd').'.pdf';
        
        try {
            require_once(APPPATH.'third_party/html2pdf/html2pdf.class.php');
            
            $html2pdf = new HTML2PDF('P','A4','en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->WriteHTML($html);
            $html2pdf->Output($filename);            
        }
        catch(Exception $e) {
            $error = true;
        }
        finally {
            if ($error != false) {
                print_r("Tyvärr kunde inte rapporten genereras. Försök igen.");
            }
        }
    }

    /**
     * Generate a report based on users choises
     */
    public function generate_report($from = null, $to = null, $type = null, $city = null, $county = null, $economy = null)
    {
        // prepare get to post array
        $formd = array(
            'from' => $from,
            'to' => $to, 
            'type' => $type,
            'city' => $city,
            'county' => $county,
            'economy' => $economy
        );

        // convert get to post
        $this->form_validation->set_data($formd);
        
        // Set validation rules
        $this->form_validation->set_rules('from', 'Datum (från)', 'required|exact_length[10]|trim');
        $this->form_validation->set_rules('to', 'Datum (till)', 'required|exact_length[10]|trim');
        $this->form_validation->set_rules('type', 'Typ av rapport', 'required|max_length[20]|trim');
        $this->form_validation->set_rules('city', 'Ort', 'max_length[100]|trim');
        $this->form_validation->set_rules('county', 'Län', 'max_length[50]|trim');
        $this->form_validation->set_rules('economy', 'Ekonomi', 'numeric|exact_length[1]|trim');
        
        // Perform the validation
		if ($this->form_validation->run() == FALSE)
		{
			print_r(validation_errors(' ', ' '));
		}
        else 
        {
            // xss clean our dynmaic posts
            $from = $this->security->xss_clean($from);
            $to = $this->security->xss_clean($to);
            $type = $this->security->xss_clean($type);
            $city = $this->security->xss_clean($city);
            $county = $this->security->xss_clean($county);
            $economy = $this->security->xss_clean($economy);

            // get todays date and time for page stamps
            $todays_date = date('Y-m-d');
            $todays_time = date('H:i:s');

            // convert our dates to db dates
            $from_dbdate = $from.' 06:00';
            $to_dbdate = $to. ' 06:00';
            
            // error check
            $error = false;
            
            // start building our PDF
            $page_open = '<page backtop="5mm" backbottom="30mm" backleft="5mm" backright="5mm" style="font-size:12px;">';
            $page_header = '<page_header><img src="'.base_url('assets/apps/img/logo.png').'" style="width:200px;height:auto;float:right;"></page_header>';
            $page_footer = '<page_footer>
                                <table style="width:100%;color:#808085;">
                                    <tr>
                                        <td style="width:33%;">FULL = Fullbokat</td>
                                        <td style="width:33%;"></td>
                                        <td style="width:33%;"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:33%;">F = Flyttad kurs</td>
                                        <td style="width:33%;"></td>
                                        <td style="width:33%;text-align:right;">'.$todays_time.'</td>
                                    </tr>
                                    <tr>
                                        <td style="width:33%;"></td>
                                        <td style="width:33%;text-align:center;">'.$todays_date.'</td>
                                        <td style="width:33%;"></td>
                                    </tr>
                                </table>
                            </page_footer>';
            $page_close = '</page>';
            
            // empty content variable
            $page_content = '';

            // if type is course
            if ($type == 'course')
            {
                // Get courses from DB
                $courses = $this->report_model->getCourses();
                
                // loop through our courses
                foreach ($courses as $course)
                {
                    $this->db->select('id, customized, canceled, course_date, city, maximum_participants');
                    $this->db->where('course_id', $course->id);
                    $this->db->where('course_date >=', $from_dbdate);
                    $this->db->where('course_date <=', $to_dbdate);

                    // check for city
                    if (!empty($city) && $city != '-1')
                    {
                        $this->db->where('city', $city);
                    }
                    
                    // check for county
                    if (!empty($county) && $county != '-1')
                    {
                        $this->db->where('county', $county);
                    }

                    $this->db->order_by('course_date', 'ASC');
                    $this->db->from('tbl_course_event');
                    
                    $query = $this->db->get();
                    $num_rows = $query->num_rows();
                    $events = $query->result();
                    
                    if ($num_rows > 0)
                    {
                        $page_content .= '<h2>'.$course->course_name.'</h2>';
                        $page_content .= '<ul>';
                        
                        foreach ($events as $event)
                        {
                            $event_date = date("d/m-Y", strtotime($event->course_date));                    
                            $full = '';
                            $new_date = '';
                            $customized = '';
                            $total_participants = $this->pdf_model->count_participants($event->id);
                            $amount_participants = '';
                            $teachers = $this->pdf_model->get_teachers($event->id);
                            
                            // do we want to include prices
                            if ($economy == 1)
                            {
                                $participant_price = $this->pdf_model->calculate_participant($event->id);
                                $ghost_price = $this->pdf_model->calculate_ghost($event->id);
                                $total_price = $participant_price + $ghost_price;
                                $total_price_format = '<strong>('.$total_price.':-)</strong>';
                            }
                                                                    
                            if ($total_participants >= $event->maximum_participants)
                            {
                                $full = '<strong>(FULL)</strong>';
                            }
                            
                            if ($this->pdf_model->event_is_moved($event->id) )
                            {
                                $new_date = '<strong>(F)</strong>';
                            }
                            
                            if ($event->customized == 1)
                            {
                                $customized = '<strong>FTG</strong>';
                            }
                            
                            $amount_participants = '<strong>'.$total_participants.'/'.$event->maximum_participants.'</strong>';
                            
                            if ($event->canceled == 1)
                            {
                                $page_content .= '<li style="text-decoration:line-through;">';
                            }
                            else
                            {
                                $page_content .= '<li>';
                            }
                            
                            $page_content .= ''.$event_date.' - '.$event->city.' | '.$teachers->teachers.' '.$amount_participants.' '.$customized.' '.$full.' '.$new_date.'';

                            // do we want to include prices
                            if ($economy == 1)
                            {
                                $page_content .= $total_price_format;
                            }

                            $page_content .= '</li>';
                        }
                        
                        $page_content .= '</ul>';
                    }
                }
            }
            else if ($type == 'city')
            {
                $cities = $this->report_model->getCities();

                foreach ($cities as $cit)
                {
                    if(empty($cit->city))
                    {
                        continue;
                    }

                    $this->db->select('tbl_course_event.id, tbl_course_event.customized, tbl_course_event.canceled, tbl_course_event.course_date, tbl_course_event.maximum_participants');
                    $this->db->select('tbl_course.course_name');
                    $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
                    $this->db->where('course_date >=', $from_dbdate);
                    $this->db->where('course_date <=', $to_dbdate);                    
                    $this->db->where('city', $cit->city);
                    $this->db->order_by('course_date', 'ASC');
                    $this->db->from('tbl_course_event');
                    
                    $query = $this->db->get();
                    $num_rows = $query->num_rows();
                    $events = $query->result();

                    if ($num_rows > 0)
                    {
                        $page_content .= '<h2>'.$cit->city.'</h2>';
                        $page_content .= '<ul>';
                        
                        foreach ($events as $event)
                        {
                            $event_date = date("d/m-Y", strtotime($event->course_date));                    
                            $full = '';
                            $new_date = '';
                            $customized = '';
                            $total_participants = $this->pdf_model->count_participants($event->id);
                            $amount_participants = '';
                            $teachers = $this->pdf_model->get_teachers($event->id);
                            
                            // do we want to include prices
                            if ($economy == 1)
                            {
                                $participant_price = $this->pdf_model->calculate_participant($event->id);
                                $ghost_price = $this->pdf_model->calculate_ghost($event->id);
                                $total_price = $participant_price + $ghost_price;
                                $total_price_format = '<strong>('.$total_price.':-)</strong>';
                            }
                                                                    
                            if ($total_participants >= $event->maximum_participants)
                            {
                                $full = '<strong>(FULL)</strong>';
                            }
                            
                            if ($this->pdf_model->event_is_moved($event->id) )
                            {
                                $new_date = '<strong>(F)</strong>';
                            }
                            
                            if ($event->customized == 1)
                            {
                                $customized = '<strong>FTG</strong>';
                            }
                            
                            $amount_participants = '<strong>'.$total_participants.'/'.$event->maximum_participants.'</strong>';
                            
                            if ($event->canceled == 1)
                            {
                                $page_content .= '<li style="text-decoration:line-through;">';
                            }
                            else
                            {
                                $page_content .= '<li>';
                            }
                            
                            $page_content .= ''.$event_date.' - '.$event->course_name.' - '.$teachers->teachers.' '.$amount_participants.' '.$customized.' '.$full.' '.$new_date.'';

                            // do we want to include prices
                            if ($economy == 1)
                            {
                                $page_content .= $total_price_format;
                            }

                            $page_content .= '</li>';
                        }
                        
                        $page_content .= '</ul>';
                    }

                }
            }
            else if ($type == 'county')
            {
                $counties = $this->report_model->getCounties();

                foreach ($counties as $cit)
                {
                    if (empty($cit->county))
                    {
                        continue;
                    }

                    $this->db->select('tbl_course_event.id, tbl_course_event.customized, tbl_course_event.canceled, tbl_course_event.course_date, tbl_course_event.maximum_participants');
                    $this->db->select('tbl_course.course_name');
                    $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
                    $this->db->where('course_date >=', $from_dbdate);
                    $this->db->where('course_date <=', $to_dbdate);                    
                    $this->db->where('county', $cit->county);
                    $this->db->order_by('course_date', 'ASC');
                    $this->db->from('tbl_course_event');
                    
                    $query = $this->db->get();
                    $num_rows = $query->num_rows();
                    $events = $query->result();

                    if ($num_rows > 0)
                    {
                        $page_content .= '<h2>'.$cit->county.'</h2>';
                        $page_content .= '<ul>';
                        
                        foreach ($events as $event)
                        {
                            $event_date = date("d/m-Y", strtotime($event->course_date));                    
                            $full = '';
                            $new_date = '';
                            $customized = '';
                            $total_participants = $this->pdf_model->count_participants($event->id);
                            $amount_participants = '';
                            $teachers = $this->pdf_model->get_teachers($event->id);
                            
                            // do we want to include prices
                            if ($economy == 1)
                            {
                                $participant_price = $this->pdf_model->calculate_participant($event->id);
                                $ghost_price = $this->pdf_model->calculate_ghost($event->id);
                                $total_price = $participant_price + $ghost_price;
                                $total_price_format = '<strong>('.$total_price.':-)</strong>';
                            }
                                                                    
                            if ($total_participants >= $event->maximum_participants)
                            {
                                $full = '<strong>(FULL)</strong>';
                            }
                            
                            if ($this->pdf_model->event_is_moved($event->id) )
                            {
                                $new_date = '<strong>(F)</strong>';
                            }
                            
                            if ($event->customized == 1)
                            {
                                $customized = '<strong>FTG</strong>';
                            }
                            
                            $amount_participants = '<strong>'.$total_participants.'/'.$event->maximum_participants.'</strong>';
                            
                            if ($event->canceled == 1)
                            {
                                $page_content .= '<li style="text-decoration:line-through;">';
                            }
                            else
                            {
                                $page_content .= '<li>';
                            }
                            
                            $page_content .= ''.$event_date.' - '.$event->course_name.' - '.$teachers->teachers.' '.$amount_participants.' '.$customized.' '.$full.' '.$new_date.'';

                            // do we want to include prices
                            if ($economy == 1)
                            {
                                $page_content .= $total_price_format;
                            }

                            $page_content .= '</li>';
                        }
                        
                        $page_content .= '</ul>';
                    }

                }
            }
            else 
            {
                // error no type
            }
                                    
            // Build page
            $html = $page_open.$page_header.$page_footer.$page_content.$page_close;
            
            // Filename
            $filename = 'rapport_'.date('Ymd').'.pdf';
            
            try {
                require_once(APPPATH.'third_party/html2pdf/html2pdf.class.php');
                
                $html2pdf = new HTML2PDF('P','A4','en');
                $html2pdf->pdf->SetDisplayMode('fullpage');
                $html2pdf->WriteHTML($html);
                $html2pdf->Output($filename);            
            }
            catch(Exception $e) {
                $error = true;
            }
            finally {
                if ($error != false) {
                    print_r("Tyvärr kunde inte rapporten genereras. Försök igen.");
                }
            }
        }
    }
}