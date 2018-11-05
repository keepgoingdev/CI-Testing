<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	public $error_message = null;
    public $success_message = null;
    public $auth = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));
		$this->load->helper(array('gravatar'));
		$this->load->model(array('teacher_model','course_model','messages_model'));

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
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
			'customer_ajax' => 'assets/pages/scripts/teacher_ajax.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);

		$view_data = array(
			'title_part1' => 'Utbildare',
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
		$this->load->view('content/teacher');
		$this->load->view('includes/footers/footer');
	}
    
    /**
     * Create a new teacher
    */
	public function new_teacher()
    {
		if($this->input->post())
		{
			$this->form_validation->set_rules("first_name", "Förnamn", "required|max_length[50]|trim");
			$this->form_validation->set_rules("last_name", "Efternamn", "required|max_length[50]|trim");
			$this->form_validation->set_rules("address", "Adress", "required|max_length[100]|trim");
			$this->form_validation->set_rules("password", "Lösenord", "required|max_length[50]|trim");
            $this->form_validation->set_rules("email", "E-post", "required|valid_email|is_unique[users.email]|max_length[100]|trim");
            $this->form_validation->set_rules("phone", "Telefon", "required|max_length[50]|trim");
            $this->form_validation->set_rules("secondary_phone", "Alternativ telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company", "Företag", "max_length[100]|trim");
            $this->form_validation->set_rules("courses[]", "Utbildningar", "required|max_length[255]|trim");

			if ($this->form_validation->run() == FALSE)
			{  
				$this->error_message = validation_errors(' ', ' ');
			}
			else
			{
                $first_name = ucname($this->input->post('first_name'));
                $last_name = ucname($this->input->post('last_name'));
                $address = $this->input->post('address');                
				$password = $this->input->post('password');
				$email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $secondary_phone = $this->input->post('secondary_phone');
                $company = $this->input->post('company');
                $courses = $this->input->post('courses[]');
                $courses = implode(",", $courses);
                
                if (isset($_POST['extended_auth']))
                {
                    $extended_auth = 1;
                }
                else 
                {
                    $extended_auth = 0;
                }
                
                $freetext = $this->input->post('freetext');
                
                $teacher_group = $this->config->item('teacher_group', 'ion_auth');
                $teacher_group_id = $this->teacher_model->get_default_teacher_group($teacher_group);
				$user_id = $this->ion_auth->register($email, $password, $email, array('first_name' => $first_name, 'last_name' => $last_name, 'phone' => $phone, 'company' => $company), array($teacher_group_id));
                
                if ($user_id != false)
                {
                    $prep_data = array(
                        'user_id' => $user_id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'address' => $address,
                        'phone' => $phone,
                        'secondary_phone' => $secondary_phone,
                        'email' => $email,
                        'company' => $company,
                        'courses' => $courses,
                        'extended_auth' => $extended_auth,
                        'freetext' => $freetext,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'create_time' => date('Y-m-d H:i:s')
                    );
                    
                    if($this->teacher_model->insert($prep_data))
                    {
                        redirect('teacher');
                        exit;
                    }
                    else
                    {
                        $this->error_message = 'Ett fel uppstod när användaren skulle kopplas till en utbildare.';
                    }
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när användaren skulle sparas.';
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
			'customer_ajax' => 'assets/pages/scripts/teacher_new.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);
		
		$view_data = array(
			'title_part1' => 'Lägg till utbildare',
			'title_part2' => 'Svensk Uppdragsutbildning',
            'success_message' => $this->success_message,
            'error_message' => $this->error_message,
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'courses' => $this->course_model->get_all(),
            'gmaps' => true,
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts
		);
        
		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/new_teacher');
		$this->load->view('includes/footers/footer');
        
	}
    
    /**
     * Edit a teacher
    */
	public function edit_teacher($id = null, $readonly = false)
    {
        if ($id == null)
        {
            redirect('teacher');
            exit;
        }
        else
        {
            if (!is_numeric($id))
            {
                redirect('teacher');
                exit;
            }
            else
            {
                $id = $this->security->xss_clean($id);
            }
        }
        
		if($this->input->post())
		{            
            $this->form_validation->set_rules("first_name", "Förnamn", "required|max_length[50]|trim");
			$this->form_validation->set_rules("last_name", "Efternamn", "required|max_length[50]|trim");
			$this->form_validation->set_rules("address", "Adress", "required|max_length[100]|trim");
			$this->form_validation->set_rules("password", "Lösenord", "max_length[50]|trim");
            $this->form_validation->set_rules("email", "E-post", "required|valid_email|max_length[100]|trim");
            $this->form_validation->set_rules("phone", "Telefon", "required|max_length[50]|trim");
            $this->form_validation->set_rules("secondary_phone", "Alternativ telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company", "Företag", "max_length[100]|trim");
            $this->form_validation->set_rules("courses[]", "Utbildningar", "required|max_length[255]|trim");
            $this->form_validation->set_rules("user_id", "ID", "required|numeric|trim");

			if ($this->form_validation->run() == FALSE)
			{  
				$this->error_message = validation_errors(' ', ' ');
			}
			else
			{
                $first_name = ucname($this->input->post('first_name'));
                $last_name = ucname($this->input->post('last_name'));
                $address = $this->input->post('address');
                $password = $this->input->post('password');
                $email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $secondary_phone = $this->input->post('secondary_phone');
                $company = $this->input->post('company');
                $courses = $this->input->post('courses[]');
                $courses = implode(",", $courses);
                $user_id = $this->input->post('user_id');
                
                if (isset($_POST['extended_auth']))
                {
                    $extended_auth = 1;
                }
                else 
                {
                    $extended_auth = 0;
                }
                
                $freetext = $this->input->post('freetext');
                
                if (empty($password))
                {
                    $update_ion_data = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'address' => $address,
                        'email' => $email,
                        'phone' => $phone,
                        'company' => $company
                    );
                }
                else
                {
                    $update_ion_data = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'address' => $address,
                        'password' => $password,
                        'email' => $email,
                        'phone' => $phone,
                        'company' => $company
                    );
                }
                
                $update_data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'address' => $address,
                    'email' => $email,
                    'phone' => $phone,
                    'secondary_phone' => $secondary_phone,
                    'company' => $company,
                    'courses' => $courses,
                    'extended_auth' => $extended_auth,
                    'freetext' => $freetext,
                    'edited_by' => $this->ion_auth->user()->row()->id,
                    'edit_time' => date('Y-m-d H:i:s')
                );
                
                if($this->ion_auth->update($user_id, $update_ion_data))
                {
                    if($this->teacher_model->update_teacher($update_data, $id))
                    {
                        $this->success_message = 'Ändringarna har sparats.';
                    }
                    else
                    {
                        $this->error_message = 'Ett fel uppstod när ändringarna skulle sparas.';
                    }
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när ändringarna skulle sparas.';
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
			'customer_ajax' => 'assets/pages/scripts/teacher_edit.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);
        
        if ($readonly)
        {
            $title_part1 = 'Visa utbildare';
        }
        else
        {
            $title_part1 = 'Redigera utbildare';
        }
		
        $view_data = array(
			'title_part1' => $title_part1,
			'title_part2' => 'Svensk Uppdragsutbildning',
            'success_message' => $this->success_message,
            'error_message' => $this->error_message,
            'readonly' => $readonly,
            'id' => $id,
            'teacher' => $this->teacher_model->get($id),
            'courses' => $this->course_model->get_all(),
            'gmaps' => true,
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts            
		);
        
		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/edit_teacher');
		$this->load->view('includes/footers/footer');
        
	}
    
    /**
     * Delete a teacher and return a JSON response
    */
	public function delete_teacher()
	{
		$this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
        $this->form_validation->set_rules('user_id', 'ID', 'required|numeric|trim');
		
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
			$user_id = $this->input->post('user_id', true);

			if ($this->teacher_model->delete_teacher($id))
			{
                if ($this->ion_auth->delete_user($user_id))
                {
                    if ($this->teacher_model->delete_from_events($id))
                    {
                        if ($this->messages_model->remove_all_messages($user_id))
                        {
                            $response = array(
                                'status' => 'success'
                            );
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Utbildaren har plockats bort men vi kunde inte radera alla kopplingar i databasen.'
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Utbildaren har plockats bort men vi kunde inte radera alla kopplingar i databasen.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när utbildaren skulle tas bort.'
                    );
                }
			}
			else 
			{
				$response = array(
					'status' => 'error',
					'message' => 'Ett fel uppstod när utbildaren skulle tas bort.'
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
     * Get all teachers and return them with a
     * JSON response.
    */
	public function get_teachers_ajax()
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
            0 => 'id',
            1 => 'full_name',
            2 => 'email',
            3 => 'phone',
            4 => 'company'
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
		$this->db->select("id, user_id, CONCAT((first_name),(' '),(last_name)) as full_name, email, phone, company");
        $this->db->from('tbl_teacher');
        
        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(CONCAT((first_name),(' '),(last_name)) LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR company LIKE '%$search%')", null, true);
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
        $recordsTotal = $this->db->count_all_results('tbl_teacher');
        
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