<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public $auth = '';
    
	public function __construct() {
		parent::__construct();
		$this->load->library(array('ion_auth'));
        
        if ($this->ion_auth->logged_in())
		{
			$this->auth = auth($this->auth_model->is_super_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_regular_admin($this->ion_auth->user()->row()->id), $this->auth_model->is_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_extended_teacher($this->ion_auth->user()->row()->id), $this->auth_model->is_user($this->ion_auth->user()->row()->id));
		}
	}

    /**
     * Default Login View
    */
	public function index()
	{
        if ($this->ion_auth->logged_in())
		{ 
			redirect(site_url('dashboard'));
            exit;
		}
        
		/* Prepare data to be sent to the view of this controller */
		$view_data = array(
			'title_part1' => 'Logga in',
			'title_part2' => 'Labbmiljö'
		);
		
		$this->load->view('includes/headers/login_header', $view_data);
		$this->load->view('content/login');
		$this->load->view('includes/footers/login_footer');

		if($this->input->post())
		{		  
			$username = $this->input->post("username");
	        $password = $this->input->post("password");
            
            if ($this->input->post('remember', true)) 
            {
                $remember = true;
            }
            else
            {
                $remember = false;   
            }
            
			if($this->ion_auth->login($username, $password, $remember))
			{
				redirect('dashboard');
			}
			else
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-danger"><button class="close" data-close="alert"></button><span>Felaktigt användarnamn eller lösenord.</span></div>');
				redirect('login');
			}
	    }
	}
    
    /**
     * Dashboard view
    */
	public function dashboard()
	{
		if (!$this->ion_auth->logged_in())
		{ 
			redirect(site_url('login'));
            exit;
		}
        
        $this->load->helper(array('gravatar'));
        $this->load->model(array('teacher_model','messages_model'));
        
        $page_styles = array(
            'fullcalendar' => 'assets/global/plugins/fullcalendar/fullcalendar.min.css',
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );
        
        $page_scripts = array(
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'fullcalendar' => 'assets/global/plugins/fullcalendar/fullcalendar.min.js',
            'fullcalendar_sv' => 'assets/global/plugins/fullcalendar/locale/sv.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'page_specific' => 'assets/pages/scripts/dashboard_ajax.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );        
		
		$view_data = array(
			'title_part1' => 'Översikt',
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
		$this->load->view('content/dashboard');
		$this->load->view('includes/footers/footer');
	}
    
    /**
     * Calendar view
    */
    public function calendar()
    {
        if (!$this->ion_auth->logged_in())
		{ 
			redirect(site_url('login'));
            exit;
		}
        
        $date = $this->input->get('date', true);
            
        $page_styles = array(
            'fullcalendar' => 'assets/global/plugins/fullcalendar/fullcalendar.min.css',
            'fullcalendar_print' => 'assets/global/plugins/fullcalendar/fullcalendar.print.css'
        );

        $page_scripts = array(
            'moment' => 'assets/global/plugins/moment.min.js',
            'fullcalendar' => 'assets/global/plugins/fullcalendar/fullcalendar.min.js',
            'fullcalendar_sv' => 'assets/global/plugins/fullcalendar/locale/sv.js',
            'page_specific' => 'assets/pages/scripts/calendar_ajax.min.js'
        );

        $view_data = array(
            'title_part1' => 'Kalender',
            'title_part2' => 'Svensk Uppdragsutbildning',
            'date' => $date,
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts
        );

        $this->load->view('includes/headers/calendar_header', $view_data);
        $this->load->view('content/calendar');
        $this->load->view('includes/footers/calendar_footer');
    }
    
    /**
     * Get all course events to be displayed in the
     * calendar.
     * @AJAX ONLY
    */
    public function get_course_events($mode = null, $Id = null)
    {
        // check if user is logged in
        if (!$this->ion_auth->logged_in())
		{ 
			$this->output->set_status_header('400');
            $this->output->set_content_type('text/plain');
            $this->output->set_output('Bad request');
		}
        else
        {
            // check if this is an Ajax request
            if (!$this->input->is_ajax_request())
            {
                $this->output->set_status_header('400');
                $this->output->set_content_type('text/plain');
                $this->output->set_output('Bad request');
            }
            else 
            {
                $start = $this->input->get('start', true);
                $end = $this->input->get('end', true);

                $this->db->select('tbl_course_event.id, tbl_course_event.course_date AS start, tbl_course_event.course_date_end AS end, tbl_course_event.canceled, tbl_course_event.food_booked, tbl_course_event.material_sent, tbl_course_event.mails_sent, tbl_course_event.certdip_sent, tbl_course_event.extern_teacher');
                $this->db->select("CONCAT((tbl_course.course_name),(' | '),(tbl_course_event.location)) as title");
                $this->db->join("tbl_course", "tbl_course_event.course_id = tbl_course.id", "left");
                $this->db->join('tbl_course_event_teachers', 'tbl_course_event_teachers.course_event_id = tbl_course_event.id', 'left');
                $this->db->join('tbl_teacher', 'tbl_teacher.id = tbl_course_event_teachers.teacher_id', 'left');
                $this->db->where("tbl_course_event.course_date BETWEEN '$start' AND '$end'");

                // sort on teacher or course
                if ($mode != null)
                {
                    if ($Id != null)
                    {
                        if ($mode == 'teacher')
                        {
                            $this->db->where('tbl_teacher.id', $Id);
                        }
                        if ($mode == 'course')
                        {
                            $this->db->where('tbl_course.id', $Id);
                        }
                    }
                }            

                $this->db->group_by('tbl_course_event.id');
                
                $query = $this->db->get('tbl_course_event');
                $results = $query->result_array();

                /* Use CI3 output class to display the results */
                $_output = json_encode($results);
                $this->output->set_content_type('application/json');
                $this->output->set_status_header('200');
                $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
                $this->output->set_header('Pragma: no-cache');
                $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
                $this->output->set_header('Content-Length: '. strlen($_output));
                $this->output->set_output($_output);
            }
        }
    }
    
    /**
     * Get a specific course event
     * @AJAX ONLY
    */
    public function get_specific_event($event_id = null)
    {
        // check if user is logged in
        if (!$this->ion_auth->logged_in())
		{ 
			$this->output->set_status_header('400');
            $this->output->set_content_type('text/plain');
            $this->output->set_output('Bad request');
		}
        else
        {
            // check if this is an Ajax request
            if (!$this->input->is_ajax_request())
            {
                $this->output->set_status_header('400');
                $this->output->set_content_type('text/plain');
                $this->output->set_output('Bad request');
            }
            else 
            {
                // Get participants and ghosts
                $authenticated = false;
                
                // Course event details
                $this->db->select('tbl_course_event.course_code, tbl_course_event.canceled, tbl_course_event.course_date, tbl_course_event.course_date_end, tbl_course_event.location, tbl_course_event.zip, tbl_course_event.city, tbl_course_event.maximum_participants, tbl_course_event.food, tbl_course_event.living, tbl_course_event.course_material, tbl_course_event.create_time');
                $this->db->select('users.first_name, users.last_name');
                $this->db->join('users', 'tbl_course_event.user_id = users.id', 'left');
                $this->db->where('tbl_course_event.id', $event_id);
                $query1 = $this->db->get('tbl_course_event');
                $result1 = $query1->row();

                // Teachers
                $this->db->select('tbl_teacher.user_id, tbl_teacher.first_name, tbl_teacher.last_name, tbl_teacher.phone, tbl_teacher.email');
                $this->db->join('tbl_course_event_teachers', 'tbl_teacher.id = tbl_course_event_teachers.teacher_id', 'left');
                $this->db->where('tbl_course_event_teachers.course_event_id', $event_id);
                $query2 = $this->db->get('tbl_teacher');
                $result2 = $query2->result_array();
                
                // Check if the user is allowd to view this information
                foreach ($result2 as $teacher)
                {
                    if ($teacher['user_id'] == $this->ion_auth->user()->row()->id)
                    {
                        $authenticated = true;
                    }
                }
                
                // Participants
                $this->db->select("tbl_participant.id, tbl_participant.personalnumber, CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) as full_name, tbl_participant.email");
                $this->db->select('tbl_customer.company_name, tbl_customer.id as company_id');
                $this->db->select('tbl_course_event_participants.verified');
                $this->db->from('tbl_participant');
                $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
                $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.participant_id = tbl_participant.id', 'left');
                $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
                $this->db->order_by('tbl_customer.company_name', 'ASC');
                $query3 = $this->db->get();
                $result3 = $query3->result();

                // Ghosts
                $this->db->select('tbl_course_event_ghosts.id, tbl_course_event_ghosts.customer_id, tbl_course_event_ghosts.amount');
                $this->db->select('tbl_customer.company_name, tbl_customer.company_phone');
                $this->db->from('tbl_course_event_ghosts');
                $this->db->join('tbl_customer', 'tbl_customer.id = tbl_course_event_ghosts.customer_id', 'left');
                $this->db->where('tbl_course_event_ghosts.course_event_id', $event_id);
                $this->db->order_by('tbl_customer.company_name', 'ASC');
                $query4 = $this->db->get();
                $result4 = $query4->result();

                if ($this->auth == 'teacher') 
                {
                    if ($authenticated != true)
                    {
                        $result3 = '';
                        $result4 = '';
                    }
                }

                $response = array(
                    'status' => 'success',
                    'message' => '',
                    'data' => $result1,
                    'teachers' => $result2,
                    'participants' => $result3,
                    'ghosts' => $result4
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
        }
    }

    /**
     * Get all availible teachers
     * @AJAX ONLY
    */
    public function get_teachers()
    {
        // check if user is logged in
        if (!$this->ion_auth->logged_in())
		{ 
			$this->output->set_status_header('400');
            $this->output->set_content_type('text/plain');
            $this->output->set_output('Bad request');
        }
        else 
        {
            // check if this is an Ajax request
            if (!$this->input->is_ajax_request())
            {
                $this->output->set_status_header('400');
                $this->output->set_content_type('text/plain');
                $this->output->set_output('Bad request');
            }
            else 
            {
                $this->db->select('id, user_id, first_name, last_name');
                $this->db->order_by('first_name');
                $this->db->from('tbl_teacher');
                $query = $this->db->get();
                $teachers = $query->result_object();

                if ($teachers != false)
                {
                    $response = array(
                        'status' => 'success',
                        'message' => '',
                        'data' => $teachers
                    );
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när utbildare skulle hämtas. Försök igen senare.',
                        'data' => ''
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
    }

    /**
     * Get all availible courses
     * @AJAX ONLY
    */
    public function get_courses()
    {
        // check if user is logged in
        if (!$this->ion_auth->logged_in())
		{ 
			$this->output->set_status_header('400');
            $this->output->set_content_type('text/plain');
            $this->output->set_output('Bad request');
        }
        else 
        {
            // check if this is an Ajax request
            if (!$this->input->is_ajax_request())
            {
                $this->output->set_status_header('400');
                $this->output->set_content_type('text/plain');
                $this->output->set_output('Bad request');
            }
            else 
            {
                $this->db->select('id, course_name');
                $this->db->order_by('course_name');
                $this->db->from('tbl_course');
                $query = $this->db->get();
                $courses = $query->result_object();

                if ($courses != false)
                {
                    $response = array(
                        'status' => 'success',
                        'message' => '',
                        'data' => $courses
                    );
                }
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när utbildningar skulle hämtas. Försök igen senare.',
                        'data' => ''
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
    }

    /**
     * Logout function
    */
	public function logout()
	{
		$this->ion_auth->logout();
		$this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-close="alert"></button><span>Du är nu utloggad!</span></div>');
		redirect('login');
	}
}