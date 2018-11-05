<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {	    
    
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));		
		$this->load->model(array('messages_model','teacher_model'));
        $this->load->helper(array('gravatar'));
		
		if (!$this->ion_auth->logged_in())
		{ 
			redirect('auth/login');
            exit;
		}
        
        $this->auth = auth($this->auth_model->is_super_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_regular_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_extended_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_user($this->ion_auth->user()->row()->id));
        
        if ($this->auth == 'extended_teacher' || $this->auth == 'teacher')
        {
            redirect('dashboard');
            exit;
        }
	}

	public function index()
	{
        $page_styles = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.css',
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );
        
        $page_scripts = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'page_specific' => 'assets/pages/scripts/messages.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );
		
        $this->messages_model->message_read($this->ion_auth->user()->row()->id);
        
        $view_data = array(
			'title_part1' => 'Meddelanden',
			'title_part2' => 'Svensk Uppdragsutbildning',
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts
		);
		
		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/messages');
		$this->load->view('includes/footers/footer');
	}
    
    public function delete($message_id = null)
    {
        $formd = array(
            'id' => $message_id            
        );

        $this->form_validation->set_data($formd);
        
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
            $message_id = $this->security->xss_clean($message_id);
            
            if($this->messages_model->remove_message($message_id))
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
                    'message' => 'Ett fel uppstod när meddelandet skulle tas bort.'
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
    
    public function get_messages_ajax()
    {
        /* User id */
        $user_id = $this->ion_auth->user()->row()->id;
        
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
            0 => 'id',
            1 => 'title',
            2 => 'message',
            3 => 'date'
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
        $this->db->select('id, title, message, date');
        $this->db->from('tbl_messages');
        
        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(title LIKE '%$search%' OR message LIKE '%$search%' OR date LIKE '%$search%')", null, true);
        }
        
        /* Use custom order only if order_column isset and not empty */
        if (!empty($order_column))
        {
            $this->db->order_by($table_fields[$order_column], $order_dir);
        }
        else
        {
            $this->db->order_by('id', $order_dir);
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
        $recordsTotal = $this->db->count_all_results('tbl_messages');
        
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