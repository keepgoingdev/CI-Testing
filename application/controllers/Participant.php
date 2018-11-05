<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Participant extends CI_Controller {

	public $error_message = null;
    public $success_message = null;
    public $auth = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));
        $this->load->helper(array('gravatar'));
		$this->load->model(array('participant_model','customer_model','teacher_model','messages_model'));

		if (!$this->ion_auth->logged_in())
		{ 
			redirect('login');
            exit;
		}
        
        $this->auth = auth($this->auth_model->is_super_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_regular_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_extended_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_user($this->ion_auth->user()->row()->id));
        
        if ($this->auth == 'teacher')
        {
            redirect('dashboard');
            exit;
        }
	}
    
    /**
     * Default view for participants
    */
	public function index()
	{
        $page_styles = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.css',
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );
        
		$page_scripts = array(
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
			'customer_ajax' => 'assets/pages/scripts/participant_ajax.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);

		$view_data = array(
			'title_part1' => 'Deltagare',
			'title_part2' => 'Svensk Uppdragsutbildning',
            'success_message' => $this->success_message,
            'error_message' => $this->error_message,
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
			'page_scripts' => $page_scripts
		);

		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/participant');
		$this->load->view('includes/footers/footer');
	}
    
    /**
     * Create a new participant
    */
    public function new_participant()
    {
        if($this->input->post())
		{
            $this->form_validation->set_rules("personalnumber", "Personnummer", "required|is_unique[tbl_participant.personalnumber]|max_length[50]|trim",
                array(
                    'is_unique' => 'En deltagare med detta personnummer finns redan registrerad i systemet.'                    
                )
            );
            
			$this->form_validation->set_rules("first_name", "Förnamn", "required|max_length[50]|trim");
			$this->form_validation->set_rules("last_name", "Efternamn", "required|max_length[50]|trim");
            $this->form_validation->set_rules("company", "Företag", "required|numeric|max_length[11]|trim");
            $this->form_validation->set_rules("phone", "Telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("email", "E-post", "valid_email|max_length[50]|trim");

			if ($this->form_validation->run() == FALSE)
			{  
				$this->error_message = validation_errors(' ', ' ');
			}
			else
			{
                $personalnumber = $this->input->post('personalnumber');
                
                if ($this->input->post('foreign_ssn', true)) 
                {
                    $foreign_ssn = '1';
                }
                else 
                {
                    $foreign_ssn = '0';
                }
                
                $company_id = $this->input->post('company');
                $first_name = ucname($this->input->post('first_name'));
                $last_name = ucname($this->input->post('last_name'));
                $phone = $this->input->post('phone');
                $email = $this->input->post('email');
                $freetext = $this->input->post('freetext');
                
                $prepdata = array(
                    'personalnumber' => $personalnumber,
                    'foreign_ssn' => $foreign_ssn,
                    'company_id' => $company_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'email' => $email,
                    'freetext' => $freetext,
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'create_time' => date('Y-m-d H:i:s')
                );
                
                if ($this->participant_model->insert($prepdata))
                {
                    redirect('participant');
                    exit;
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när deltagaren skulle sparas.';
                }
			}
		}
        
        $page_styles = array(
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );
        
        $page_scripts = array(
            'bootstrap_passowrd_strength' => 'assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js',
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
			'page_specific' => 'assets/pages/scripts/participant_new_edit.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);
		
		$view_data = array(
			'title_part1' => 'Lägg till deltagare',
			'title_part2' => 'Svensk Uppdragsutbildning',
            'success_message' => $this->success_message,
            'error_message' => $this->error_message,
            'customers' => $this->customer_model->get_all(),
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts
		);
        
		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/new_participant');
		$this->load->view('includes/footers/footer');
    }
    
    /**
     * Edit a existing participant
    */
    public function edit_participant($id = null, $readonly = false)
    {
        if ($id == null)
        {
            redirect('participant');
            exit;
        }
        else
        {
            if (!is_numeric($id))
            {
                redirect('participant');
                exit;
            }
            else
            {
                $id = $this->security->xss_clean($id);
            }
        }
        
        if($this->input->post())
		{
            if($this->input->post('personalnumber') != $this->input->post('pn_org')) 
            {
               $is_unique =  '|is_unique[tbl_participant.personalnumber]';
            } 
            else 
            {
               $is_unique =  '';
            }
            
            $this->form_validation->set_rules("personalnumber", "Personnummer", "required|max_length[50]|trim$is_unique",
                array(
                    'is_unique' => 'En deltagare med detta personnummer finns redan registrerad i systemet.'                    
                )
            );
                        
            $this->form_validation->set_rules("first_name", "Förnamn", "required|max_length[50]|trim");
			$this->form_validation->set_rules("last_name", "Efternamn", "required|max_length[50]|trim");
            $this->form_validation->set_rules("company", "Företag", "required|numeric|max_length[11]|trim");
            $this->form_validation->set_rules("phone", "Telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("email", "E-post", "valid_email|max_length[50]|trim");

			if ($this->form_validation->run() == FALSE)
			{  
				$this->error_message = validation_errors(' ', ' ');
			}
			else
			{
                $personalnumber = $this->input->post('personalnumber');
                
                if ($this->input->post('foreign_ssn', true)) {
                    $foreign_ssn = '1';
                }
                else
                {
                    $foreign_ssn = '0';
                }
                
                $company_id = $this->input->post('company');
                $first_name = ucname($this->input->post('first_name'));
                $last_name = ucname($this->input->post('last_name'));
                $phone = $this->input->post('phone');
                $email = $this->input->post('email');
                $freetext = $this->input->post('freetext');
                
                $prepdata = array(
                    'foreign_ssn' => $foreign_ssn,
                    'personalnumber' => $personalnumber,
                    'company_id' => $company_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'email' => $email,
                    'freetext' => $freetext,
                    'edited_by' => $this->ion_auth->user()->row()->id,
                    'edit_time' => date('Y-m-d H:i:s')
                );
                
                if ($this->participant_model->update($id, $prepdata))
                {
                    $this->success_message = 'Deltagaren har sparats.';
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när deltagaren skulle sparas.';
                }
			}
		}
        
        $page_styles = array(
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );
        
        $page_scripts = array(
            'bootstrap_passowrd_strength' => 'assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js',
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
			'page_specific' => 'assets/pages/scripts/participant_new_edit.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);
        
        if ($readonly)
        {
            $title_part1 = 'Visa deltagare';
        }
        else
        {
            $title_part1 = 'Redigera deltagare';
        }
		
        $participant = $this->participant_model->get($id);
        
		$view_data = array(
			'title_part1' => $title_part1,
			'title_part2' => 'Svensk Uppdragsutbildning',
            'success_message' => $this->success_message,
            'error_message' => $this->error_message,
            'readonly' => $readonly,
            'participant' => $participant,
            'customer' => $this->customer_model->get($participant->company_id),
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts
		);
        
		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/edit_participant');
		$this->load->view('includes/footers/footer');
    }
    
    /**
     * Delete a participant
    */
    public function delete_participant()
	{
		$this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors()
			);
		}
		else
		{
			$id = $this->input->post('id', true);
			
			if ($this->participant_model->delete($id))
			{
				if ($this->participant_model->delete_from_events($id))
                {
                    $response = array(
                        'status' => 'success',
                        'message' => ''
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när deltagaren skulle tas bort.'
                    );
                }
			}
			else 
			{
				$response = array(
					'status' => 'error',
					'message' => 'Ett fel uppstod när deltagaren skulle tas bort.'
				);
			}
		}
		
        /* Use CI3 output class to display the results */
        $_output = json_encode($response);
        $this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);        
	}
    
    public function get_course_events($participant_id = null)
    {
        $formd = array(
            'id' => $participant_id            
        );

        $this->form_validation->set_data($formd);
        
        $this->form_validation->set_rules('id', 'ID', 'required|numeric');        
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ',' ')
			);
		}
		else
		{
            $participant_id = $this->security->xss_clean($participant_id);
			$data = $this->participant_model->get_by_participant($participant_id);
            
			if (!$data)
			{
				$response = array(
					'status' => 'error',
					'message' => 'Ett fel uppstod när deltagarens utbildningar skulle hämtas.'
				);
			}
            else
            {
                $response = array(
                    'status' => 'success',
                    'message' => '',
                    'data' => $data
                );
            }
		}
		
		/* Use CI3 output class to display the results */
        $_output = json_encode($response);
        $this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);   
    }
    
    /**
     * Return companies as JSON to be
     * used with Select2
    */
    public function search_companies()
    {
        $term = $this->input->get('term', true);
        
        $response = $this->participant_model->search_companies($term);

        /* Use CI3 output class to display the results */
        $_output = json_encode($response);
        $this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);
    }
        
    /**
     * Get all participants and return them with a
     * JSON response.
    */
	public function get_participants_ajax()
	{
        /* General fields for limiting resutls */         
        $start = $this->input->post('start', true);
        $limit = $this->input->post('length', true);
        $draw = $this->input->post('draw', true);
        
        /**
         * Declare your table fields, if just using 1 table the field name is enough
         * add your table name if joining (table.field)
         * if using concat, use the final name (if first_name and last_name = full_name then 
         * use full_name)
         * This should be the order displayed in datatables
        */
        $table_fields = array(
            0 => 'tbl_participant.id',
            1 => 'full_name',
            2 => 'tbl_participant.personalnumber',
            3 => 'tbl_customer.company_name',
            4 => 'tbl_participant.email',
            5 => 'tbl_participant.phone'
        );
        
        /* Declare order variables */
        $order_column = '';
        $order_dir = 'DESC';
        
        /* If ordering, store the order values */
        if (isset($_POST['order'][0]) && !empty($_POST['order'][0]))
        {
            $o1 = $this->input->post('order', true);
        }
        
        if (isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']))
        {
            $order_column = $o1[0]['column'];
        }
        
        if (isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']))
        {
            $order_dir = $o1[0]['dir'];
        }
        
        /* Declare search variable */
        $search = '';
        
        /* If search, store the search value */
        if (isset($_POST['search']['value']) && !empty($_POST['search']['value']))
        {
            // xss clean
            $s1 = $this->input->post('search', true);

            // remove single quotes
            $search = str_replace("'", "", $s1['value']);
        }
        
        /* Do your query with CI3 */
        $this->db->select("tbl_participant.id, tbl_participant.personalnumber, CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) as full_name, tbl_participant.phone, tbl_participant.email");
        $this->db->select('tbl_customer.id as customer_id, tbl_customer.company_name');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
        $this->db->from('tbl_participant');
        
        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(tbl_participant.personalnumber LIKE '%$search%' OR CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) LIKE '%$search%' OR tbl_participant.phone LIKE '%$search%' OR tbl_participant.email LIKE '%$search%' OR tbl_customer.company_name LIKE '%$search%')", null, true);
        }
        
        /* Use custom order only if order_column isset and not empty */
        if (!empty($order_column))
        {
            $this->db->order_by($table_fields[$order_column], $order_dir);
        }
        else
        {
            $this->db->order_by('tbl_participant.id', $order_dir);
        }
        
        /* Count filtered result if searching */
        if (!empty($search))
        {
            $tempdb = clone $this->db;
            $tempquery = $tempdb->get();
            $recordsFiltered = $tempquery->num_rows();
        }
        
        /* Limit the results and perform the query */
        $this->db->limit($limit, $start);
		$query = $this->db->get();
		$data = $query->result_array();
        
        /* Count the results */
        $recordsTotal = $this->db->count_all('tbl_participant');
        
        if (!isset($recordsFiltered))
        {
            $recordsFiltered = $recordsTotal;
        }
        
        /* Prepare the JSON data */
        $json_data = array(
            "start"           => intval( $start ),
            "limit"           => intval( $limit ),
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval( $recordsTotal ),  
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $data
        );
		
        /* Use CI3 output class to display the results */
        $_output = json_encode($json_data);
        $this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);
	}
}