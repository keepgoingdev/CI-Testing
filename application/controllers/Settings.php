<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    
    public $error_message = null;
    public $success_message = null;
    public $super_admin_group = null;    
    public $admin_group = null;
    public $user_group = null;
    public $teacher_group = null;
    public $extended_teacher_group = null;
    public $site_title = null;
    public $site_url = null;
    public $site_email = null;
    public $log_threshold = null;
    public $pass_policy_min = null;
    public $pass_policy_max = null;
    public $login_attempts = null;
    public $login_timeout = null;
    public $auth = '';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));
		$this->load->helper(array('gravatar'));
		$this->load->model(array('settings_model','teacher_model','messages_model'));

		if (!$this->ion_auth->logged_in())
		{ 
			redirect('login');
            exit;
		}
        
        $this->auth = auth($this->auth_model->is_super_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_regular_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_extended_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_user($this->ion_auth->user()->row()->id));
        
        if ($this->auth != 'super_admin')
        {
            redirect('dashboard');
            exit;
        }
	}
    
    /**
     * System settings
    */
	public function index()
	{
        $this->site_title = $this->config->item('site_title');
        $this->site_url = $this->config->item('base_url');
        $this->site_email = $this->config->item('site_email');
        $this->log_threshold = $this->config->item('log_threshold');
        $this->pass_policy_min = $this->config->item('min_password_length', 'ion_auth');
        $this->pass_policy_max = $this->config->item('max_password_length', 'ion_auth');
        $this->login_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');
        $this->login_timeout = $this->config->item('lockout_time', 'ion_auth');
        $this->super_admin_group = $this->config->item('admin_group', 'ion_auth');
        $this->admin_group = $this->config->item('regular_admin_group', 'ion_auth');
        $this->user_group = $this->config->item('default_group', 'ion_auth');
        $this->teacher_group = $this->config->item('teacher_group', 'ion_auth');
        $this->extended_teacher_group = $this->config->item('extended_teacher_group', 'ion_auth');
        
        if($this->input->post())
		{
            $this->form_validation->set_rules("site_title", "Webbplatstitel", "required");
            $this->form_validation->set_rules("site_url", "Webbplatsadress (URL)", "required|valid_url");
            $this->form_validation->set_rules("site_email", "E-postadress", "required|valid_email");
            $this->form_validation->set_rules("log_threshold", "Loggning", "required|numeric");
            $this->form_validation->set_rules("min_password_length", "Lösenordspolicy (min. antal tecken)", "required|numeric");
            $this->form_validation->set_rules("max_password_length", "Lösenordspolicy (max. antal tecken)", "required|numeric");
            $this->form_validation->set_rules("maximum_login_attempts", "Antal inloggningsförsök innan kontot blir låst", "required|numeric");
            $this->form_validation->set_rules("lockout_time", "Låst konto är låst så här länge", "required|numeric");
			$this->form_validation->set_rules("super_admin_group", "Grupp för super-administratörer", "required");
            $this->form_validation->set_rules("admin_group", "Grupp för administratörer", "required");
			$this->form_validation->set_rules("user_group", "Grupp för användare", "required");
			$this->form_validation->set_rules("teacher_group", "Grupp för utbildare", "required");
            $this->form_validation->set_rules("extended_teacher_group", "Grupp för utbildare med utökad behörighet", "required");

			if ($this->form_validation->run() == FALSE)
			{  
				$this->error_message = validation_errors(' ', ' ');
			}
			else
			{ 
                $site_title = $this->input->post('site_title');
                $site_url = $this->input->post('site_url');
                $site_email = $this->input->post('site_email');
                $log_threshold = $this->input->post('log_threshold');
                $min_password_length = $this->input->post('min_password_length');
                $max_password_length = $this->input->post('max_password_length');
                $maximum_login_attempts = $this->input->post('maximum_login_attempts');
                $lockout_time = $this->input->post('lockout_time');
				$super_admin_group = $this->input->post('super_admin_group');
                $admin_group = $this->input->post('admin_group');
				$user_group = $this->input->post('user_group');
                $teacher_group = $this->input->post('teacher_group');
                $extended_teacher_group = $this->input->post('extended_teacher_group');
                
                $update_ion_auth = array(
                    'min_password_length' => $min_password_length,
                    'max_password_length' => $max_password_length,
                    'maximum_login_attempts' => $maximum_login_attempts,
                    'lockout_time' => $lockout_time,
                    'super_admin_group' => $super_admin_group,
                    'admin_group' => $admin_group,
                    'user_group' => $user_group,
                    'teacher_group' => $teacher_group,
                    'extended_teacher_group' => $extended_teacher_group
                );

				if($this->settings_model->update_ion_auth($update_ion_auth))
				{
					$this->success_message = 'Inställningarna har sparats.';
                    $this->super_admin_group = $super_admin_group;
                    $this->admin_group = $admin_group;
                    $this->user_group = $user_group;
                    $this->teacher_group = $teacher_group;
                    $this->extended_teacher_group = $extended_teacher_group;
                    $this->min_password_length = $min_password_length;
                    $this->max_password_length = $max_password_length;
                    $this->maximum_login_attempts = $maximum_login_attempts;
                    $this->lockout_time = $lockout_time;
				}
                else
                {
                    $this->error_message = 'Kunde ej skriva till ion_auth.php, se över läs- och skrivrättigheter på servern.';
                }
                
                $update_ci_config = array(
                    'site_title' => $site_title,
                    'site_url' => $site_url,
                    'site_email' => $site_email,
                    'log_threshold' => $log_threshold
                );
                
                if($this->settings_model->update_ci_config($update_ci_config))
				{
					$this->success_message = 'Inställningarna har sparats.';
                    $this->site_title = $site_title;
                    $this->site_url = $site_url;
                    $this->site_email = $site_email;
                    $this->log_threshold = $log_threshold;
				}
                else
                {
                    $this->error_message = 'Kunde ej skriva till config.php, se över läs- och skrivrättigheter på servern.';
                }
			}
		}
        
        $groups = $this->ion_auth->groups()->result();
        
        $page_styles = array(
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
            'bootstrap_touchspin' => 'assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.min.css'
        );
        
		$page_scripts = array(
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'bootstrap_touchspin' => 'assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.min.js',
			'settings_script' => 'assets/pages/scripts/settings.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);

		$view_data = array(
			'title_part1' => 'Inställningar',
			'title_part2' => 'Svensk Uppdragsutbildning',
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'groups' => $groups,
            'site_title' => $this->site_title,
            'site_url' => $this->site_url,
            'site_email' => $this->site_email,
            'log_threshold' => $this->log_threshold,
            'pass_policy_min' => $this->pass_policy_min,
            'pass_policy_max' => $this->pass_policy_max,
            'login_attempts' => $this->login_attempts,
            'login_timeout' => $this->login_timeout,
            'super_admin_group' => $this->super_admin_group,
            'admin_group' => $this->admin_group,
            'user_group' => $this->user_group,
            'teacher_group' => $this->teacher_group,
            'extended_teacher_group' => $this->extended_teacher_group,
            'success_message' => $this->success_message,
            'error_message' => $this->error_message,
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
			'page_scripts' => $page_scripts            
		);

		$this->load->view('includes/headers/header', $view_data);
		$this->load->view('includes/nav_bar');
		$this->load->view('content/settings/dashboard');
		$this->load->view('includes/footers/footer');
	}
    
    /**
     * List all users
    */
    public function users()
    {
        $groups = $this->ion_auth->groups()->result();
        
        $page_styles = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.css',
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
            'editable' => 'assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css'
        );
        
        $page_scripts = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'editable' => 'assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js',
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'bootstrap_passowrd_strength' => 'assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js',
			'users_script' => 'assets/pages/scripts/get_users_ajax.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);

		$view_data = array(
			'title_part1' => 'Användare',
			'title_part2' => 'Svensk Uppdragsutbildning',
            'groups' => $groups,
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
		$this->load->view('content/settings/users');
		$this->load->view('includes/footers/footer');
    }
    
    /**
     * Add a user and return a JSON response
    */
    public function add_user()
    {
        $this->form_validation->set_rules('first_name', 'Förnamn', 'required|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Efternamn', 'required|max_length[50]');
        $this->form_validation->set_rules('email', 'Epost', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('phone', 'Telefon', 'required|max_length[20]');
        $this->form_validation->set_rules('password', 'Lösenord', 'required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('group', 'Grupp', 'required|numeric');
			
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
		}
		else 
		{
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');
            $password = $this->input->post('password');
            $group = $this->input->post('group');
			
			if ($this->ion_auth->register($email, $password, $email, array('first_name' => $first_name, 'last_name' => $last_name, 'phone' => $phone), array($group)))
			{
				$response = array(
					'status' => 'success',
                    'message' => 'Användaren har skapats och kan nu användas.'
				);
			}
			else 
			{
				$response = array(
					'status' => 'error',
					'message' => 'Ett fel uppstod vid skapandet av användaren.'
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
     * Get all users and return them in a JSON
     * response.
    */
    public function get_users_ajax()
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
            0 => 'users.id',
            1 => 'full_name',
            2 => 'users.email',
            3 => 'users.phone',
            5 => 'groupname'            
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
        $this->db->select("users.id, CONCAT((users.first_name),(' '),(users.last_name)) as full_name, users.email, users.phone, groups.name AS groupname, groups.id AS group_id");
        $this->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
        $this->db->join('groups', 'groups.id = users_groups.group_id', 'left');
        $this->db->from('users');
        
        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(CONCAT((users.first_name),(' '),(users.last_name)) LIKE '%$search%' OR users.email LIKE '%$search%' OR users.phone LIKE '%$search%' OR groups.name LIKE '%$search%')", null, true);
        }
        
        /* Use custom order only if order_column isset and not empty */
        if (!empty($order_column))
        {
            $this->db->order_by($table_fields[$order_column], $order_dir);
        }
        else
        {
            $this->db->order_by('users.id', $order_dir);
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
        $recordsTotal = $this->db->count_all_results('users');
        
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
    
    /**
	 * Get all groups for the X-editable select with ajax as source
	 * @return string JSON
	*/
	public function get_inline_groups()
	{
		$this->db->select('id AS value, name AS text');		
		$query = $this->db->get('groups');
		$data = $query->result_array();
		
		/* Use CI3 output class to display the results */
        $_output = json_encode($data);
        $this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);
	}
    
    /**
     * Update the users first- and lastname and return
     * response in JSON
    */
    public function update_user_name()
    {
        $this->form_validation->set_rules('pk', 'ID', 'required|numeric');
        $this->form_validation->set_rules('value', 'Namn', 'required|min_length[3]|max_length[100]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
            );
            
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
		else
		{
			$user_id = $this->input->post('pk');
			$name = $this->input->post('value');
                        
            $names = explode(" ", $name);
            
            $first_name = $names[0];
            
            if (isset($names[1]))
            {
                unset($names[0]);
                $last_name = implode(" ", $names);
            }
            else 
            {
                $last_name = "";
            }
            
            $update_data = array(
                'first_name' => $first_name,
                'last_name' => $last_name
            );
            
            if ($this->teacher_model->is_teacher($user_id))
            {
                if ($this->teacher_model->update($update_data, $user_id))
                {
                    if ($this->ion_auth->update($user_id, $update_data))
                    {
                        $response = array(
                            'status' => 'success'
                        );
                    }
                    else 
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod vid uppdateringen av användarens namn.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdateringen av användarens namn.'
                    );
                }
            }
            else
            {
                if ($this->ion_auth->update($user_id, $update_data))
                {
                    $response = array(
                        'status' => 'success'
                    );
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdateringen av användarens namn.'
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
    }
    
    /**
     * Update user email and return
     * response in JSON
    */
    public function update_user_email()
    {
        $this->form_validation->set_rules('pk', 'ID', 'required|numeric');
		$this->form_validation->set_rules('value', 'E-post', 'required|valid_email|is_unique[users.email]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
            
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
		else
		{
			$user_id = $this->input->post('pk');
			$email = $this->input->post('value');
            
            $update_data = array(
                'email' => $email
            );
            
            if ($this->teacher_model->is_teacher($user_id))
            {
                if ($this->teacher_model->update($update_data, $user_id))
                {
                    if ($this->ion_auth->update($user_id, $update_data))
                    {
                        $response = array(
                            'status' => 'success'
                        );
                    }
                    else 
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod vid uppdateringen av användarens e-post.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdateringen av användarens e-post.'
                    );
                }
            }
            else
            {
                if ($this->ion_auth->update($user_id, $update_data))
                {
                    $response = array(
                        'status' => 'success'
                    );
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdateringen av användarens e-post.'
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
    }
    
    /**
     * Update user phone and return
     * response in JSON
    */
    public function update_user_phone()
    {
        $this->form_validation->set_rules('pk', 'ID', 'required|numeric');
		$this->form_validation->set_rules('value', 'Telefon', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
            
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
		else
		{
			$user_id = $this->input->post('pk');
			$phone = $this->input->post('value');
            
            $update_data = array(
                'phone' => $phone
            );
            
            if ($this->teacher_model->is_teacher($user_id))
            {
                if ($this->teacher_model->update($update_data, $user_id))
                {
                    if ($this->ion_auth->update($user_id, $update_data))
                    {
                        $response = array(
                            'status' => 'success'
                        );
                    }
                    else 
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod vid uppdateringen av användarens telefon.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdateringen av användarens telefon.'
                    );
                }
            }
            else
            {
                if ($this->ion_auth->update($user_id, $update_data))
                {
                    $response = array(
                        'status' => 'success'
                    );
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdateringen av användarens telefon.'
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
    }
    
    /**
     * Update user password and return
     * response in JSON
    */
    public function update_user_password()
    {
        $this->form_validation->set_rules('pk', 'ID', 'required|numeric');
		$this->form_validation->set_rules('value', 'Lösenord', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
            
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
		else
		{
			$user_id = $this->input->post('pk');
			$password = $this->input->post('value');
            
            $update_data = array(
                'password' => $password
            );
            
            if ($this->ion_auth->update($user_id, $update_data))
            {
                $response = array(
                    'status' => 'success'
                );
            }
            else 
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Ett fel uppstod vid uppdateringen av användarens lösenord.'
                );
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
    }
    
    /**
     * Update user group and return
     * response in JSON
    */
    public function update_user_group()
    {
        $this->form_validation->set_rules('pk', 'ID', 'required|numeric');
		$this->form_validation->set_rules('value', 'Grupp', 'required|numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
            
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
		else
		{
			$user_id = $this->input->post('pk');
			$group_id = $this->input->post('value');
            
            if ( $this->ion_auth->remove_from_group(NULL, $user_id))
            {
                if ($this->ion_auth->add_to_group($group_id, $user_id))
                {
                    $response = array(
                        'status' => 'success'
                    );
                    
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när användaren skulle flyttas till en ny grupp.'
                    );
                }
            }
            else
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Ett fel uppstod när användaren skulle lämna sin gamla grupp.'
                );
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
    }
    
    /**
     * Deletes a user and optional teacher if they are connected
    */
    public function delete_user()
    {
        $this->form_validation->set_rules('id', 'User ID', 'required|numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors()
            );
            
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
		else
		{
			$user_id = $this->input->post('id');
            $teacher_id = $this->auth_model->is_teacher($user_id);
            
            if ($teacher_id != false)
            {
                if ($this->teacher_model->delete($user_id))
                {
                    if ($this->teacher_model->delete_from_events($teacher_id))
                    {
                        if ($this->messages_model->remove_all_messages($user_id))
                        {
                            if ($this->ion_auth->delete_user($user_id))
                            {
                                $response = array(
                                    'status' => 'success',
                                    'message' => 'Användaren har tagits bort.'
                                );
                            }
                            else 
                            {
                                $response = array(
                                    'status' => 'error',
                                    'message' => 'Ett fel uppstod när användaren skulle tas bort.'
                                );
                            }
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Ett fel uppstod när användaren skulle tas bort.'
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod när användaren skulle tas bort.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när användaren skulle tas bort.'
                    );
                }
            }
            else
            {
                if ($this->ion_auth->delete_user($user_id))
                {
                    if ($this->messages_model->remove_all_messages($user_id))
                    {
                        $response = array(
                            'status' => 'success',
                            'message' => 'Användaren har tagits bort.'
                        );
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod när användaren skulle tas bort.'
                        );
                    }
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när användaren skulle tas bort.'
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
    }
    
    /**
     * List all groups
    */
    public function groups()
    {
        $page_styles = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.css',
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
            'editable' => 'assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css'    
        );
        
        $page_scripts = array(
            'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'editable' => 'assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js',
			'groups_scripts' => 'assets/pages/scripts/get_groups_ajax.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
		);

		$view_data = array(
			'title_part1' => 'Grupper',
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
		$this->load->view('content/settings/groups');
		$this->load->view('includes/footers/footer');
    }
    
    /**
     * Get all groups and return them in a JSON
     * response.
    */
    public function get_groups_ajax()
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
            1 => 'name',
            2 => 'description'            
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
        $this->db->select("id, name, description");
        $this->db->from('groups');
        
        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(name LIKE '%$search%' OR description LIKE '%$search%')", null, true);
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
        $recordsTotal = $this->db->count_all_results('groups');
        
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
    
    /**
     * Add group and return result with JSON
    */
    public function add_group()
    {
        $this->form_validation->set_rules('name', 'Namn', 'required|is_unique[groups.name]|max_length[20]|trim');
		$this->form_validation->set_rules('desc', 'Beskrivning', 'max_length[255]|trim');
			
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
            
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
		else 
		{
			$name = $this->input->post('name');
			$description = $this->input->post('desc');
			
			if ($this->ion_auth->create_group($name, $description))
			{
				$response = array(
					'status' => 'success',
                    'message' => 'Gruppen har skapats och kan nu användas.'
				);
			}
			else 
			{
				$response = array(
					'status' => 'error',
					'message' => 'Databasfel'
				);
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
    }
    
    /**
     * Update the name of given group and return
     * response in JSON
    */
    public function update_group_name()
    {
        $this->form_validation->set_rules('pk', 'Grupp ID', 'required|numeric|trim');
		$this->form_validation->set_rules('value', 'Gruppnamn', 'required|is_unique[groups.name]|max_length[20]|trim');
        $this->form_validation->set_rules('old_group_name', 'Gammalt gruppnamn', 'required|max_length[20]|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
            );
            
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
		else
		{
            $default_group_detected = false;
            
			$group_id = $this->input->post('pk');
			$group_name = $this->input->post('value');
            $old_group_name = $this->input->post('old_group_name');
            
            $super_admin_group = $this->config->item('admin_group', 'ion_auth');
            $admin_group = $this->config->item('regular_admin_group', 'ion_auth');
            $default_group = $this->config->item('default_group', 'ion_auth');
            $teacher_group = $this->config->item('teacher_group', 'ion_auth');
            $extended_teacher_group = $this->config->item('extended_teacher_group', 'ion_auth');
            
            if ($old_group_name == $super_admin_group)
            {
                $default_group_detected = true;
            }
            
            if ($old_group_name == $admin_group)
            {
                $default_group_detected = true;
            }
            
            if ($old_group_name == $default_group)
            {
                $default_group_detected = true;
            }
            
            if ($old_group_name == $teacher_group)
            {
                $default_group_detected = true;
            }
            
            if ($old_group_name == $extended_teacher_group)
            {
                $default_group_detected = true;
            }
            
            if ($default_group_detected != true)
            {
                if ($this->ion_auth->update_group($group_id, $group_name))
                {
                    $response = array(
                        'status' => 'success'
                    );
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod vid uppdatering av gruppnamnet.'
                    );
                }
            }
            else 
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Det går ej döpa om en standardgrupp.'
                );
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
    }
    
    /**
     * Update the description of given group and return
     * response in JSON
    */
    public function update_group_desc()
    {
        $this->form_validation->set_rules('pk', 'Grupp ID', 'required|numeric|trim');
		$this->form_validation->set_rules('group_name', 'Gruppnamn', 'required|max_length[20]|trim');
        $this->form_validation->set_rules('value', 'Beskrivning', 'max_length[255]|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', ' ')
			);
            
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
		else
		{
			$group_id = $this->input->post('pk');
			$group_name = $this->input->post('group_name');
            $group_desc = $this->input->post('value');
			
			if ($this->ion_auth->update_group($group_id, $group_name, $group_desc))
			{
				$response = array(
					'status' => 'success'
				);
			}
			else 
			{
				$response = array(
					'status' => 'error',
					'message' => 'Ett fel uppstod vid uppdatering av gruppens beskrivning.'
				);
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
    }
    
    /**
     * Delete a given group and return response
     * in JSON
    */
    public function delete_group()
    {
        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('name', 'Gruppnamn', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors(' ', '  ')
            );
            
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
		else
		{
            $default_group_detected = false;
            
            $group_id = $this->input->post('id');
            $group_name = $this->input->post('name');
            
            $super_admin_group = $this->config->item('admin_group', 'ion_auth');
            $admin_group = $this->config->item('regular_admin_group', 'ion_auth');
            $default_group = $this->config->item('default_group', 'ion_auth');
            $teacher_group = $this->config->item('teacher_group', 'ion_auth');
            $extended_teacher_group = $this->config->item('extended_teacher_group', 'ion_auth');
            
            if ($group_name == $super_admin_group)
            {
                $default_group_detected = true;
            }
            
            if ($group_name == $admin_group)
            {
                $default_group_detected = true;
            }
            
            if ($group_name == $default_group)
            {
                $default_group_detected = true;
            }
            
            if ($group_name == $teacher_group)
            {
                $default_group_detected = true;
            }
            
            if ($group_name == $extended_teacher_group)
            {
                $default_group_detected = true;
            }
            
            if ($default_group_detected != true)
            {
                if ($this->ion_auth->delete_group($group_id))
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
                        'message' => 'Ett fel uppstod vid borttagningen av gruppen.'
                    );
                }
            }
            else 
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Det går ej radera en standardgrupp.'
                );
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
    }

    /**
     * Change the default email template    
     */
    public function emailtemplate()
    {
        if($this->input->post())
		{
            $this->form_validation->set_rules("default_mail_template", "E-postmall för kallelser", "trim");

			if ($this->form_validation->run() == FALSE)
			{  
				$this->error_message = validation_errors(' ', ' ');
			}
			else
			{ 
                $default_mail_template = $this->input->post('default_mail_template', false);

                if ($this->settings_model->update_setting('default_mail_template', $default_mail_template))
                {
                    $this->success_message = 'Inställningarna har sparats.';
                }
                else 
                {
                    $this->error_message = 'Ett fel uppstod när e-postmallen skulle sparas.';
                }
            }
        }

        $page_styles = array();
        
		$page_scripts = array(
            'settings_script' => 'assets/pages/scripts/settings_emailtemplate.min.js',
            'tinymce' => 'assets/global/plugins/tinymce/tinymce.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );
        
        $email_template = $this->settings_model->get_setting('default_mail_template');

		$view_data = array(
			'title_part1' => 'E-postmall',
            'title_part2' => 'Svensk Uppdragsutbildning',
            'email_template' => $email_template,
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
		$this->load->view('content/settings/emailtemplate');
		$this->load->view('includes/footers/footer');
    }
    
    /**
     * Database backup
    */
    public function take_db_backup() 
    {
        $this->load->dbutil();
        $backup = $this->dbutil->backup();
        $filename = date('YmdHis').'.gz';
        $path = './backup/'.$filename;
        if ( ! write_file($path, $backup))
        {
            print_r('Kunde inte skriva databasen till en fil.');
        }
        else
        {
            $this->load->helper('download');
            force_download($filename, $backup);
        }
    }
}