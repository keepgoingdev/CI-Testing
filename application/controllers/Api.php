<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public $error_message = null;
    public $success_message = null;

	public function __construct()
	{
		parent::__construct();
        
        $this->load->model(array('api_model'));
	}
    
    /**
     * Don't allow empty calls, just kill it
    */
	public function index()
	{
        die("API reported the following errors: Action is missing.");
	}
    
    /**
     * Get Courses
    */
    public function get_courses($render = 'html', $filter = 'all', $permission = '', $cid = '')
    {
        // Clean before use
        $render = $this->security->xss_clean($render);
        $filter = $this->security->xss_clean($filter);
        $permission = $this->security->xss_clean($permission);
        $cid = $this->security->xss_clean($cid);
        $callback = '';
        $getRequest = $this->input->get();

        if (empty($permission))
        {
            die('Sidan kunde inte hittas');
        }
        else 
        {
            // Get data
            switch ($render)
            {
                case "html":
                    $page_styles = array(
                        'page_specific' => 'assets/pages/css/api.min.css',
                        'datatables' => 'assets/global/plugins/datatables/datatables.min.css'                        
                    );

                    $page_scripts = array(
                        'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
                        'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
                        'page_specific' => 'assets/pages/scripts/api_course_events.min.js'
                    );

                    $view_data = array(
                        'title_part1' => 'Utbildningstillfällen',
                        'title_part2' => 'Svensk Uppdragsutbildning',
                        'page_styles' => $page_styles,
                        'page_scripts' => $page_scripts,
                        'filter' => $filter,
                        'permission' => $permission,
                        'cid' => $cid                    
                    );

                    $this->load->view('api/html_wrapper_view', $view_data);
                break;
                case "json":
                    $this->api_get_courses($filter, $permission, $cid, $callback, $getRequest);
                break;
                case "jsonp":
                    $callback = $this->input->get('callback', true);
                    $this->api_get_courses($filter, $permission, $cid, $callback, $getRequest);                
                break;
            }
        }        
    }
    
    /**
     * Get a specific course
    */
    public function get_course($apiKey = null, $id = null)
    {
        if ($apiKey == null || $id == null)
        {
            $response = array(
                'status' => 'error',
                'message' => 'Felaktigt ID.',
                'data' => ''
            );
        }
        else
        {
            if (!is_numeric($apiKey) || !is_numeric($id))
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Felaktigt ID.',
                    'data' => ''
                );
            }
            else
            {
                $apiKey = $this->security->xss_clean($apiKey);
                $id = $this->security->xss_clean($id);
                
                // Amount of ghosts
                $this->db->select('SUM(tbl_course_event_ghosts.amount) AS num_ghosts');
                $this->db->select('tbl_course_event_ghosts.course_event_id AS ghost_event_id');
                $this->db->from('tbl_course_event_ghosts');
                $this->db->group_by('ghost_event_id');
                $subquery = $this->db->get_compiled_select();
                $this->db->reset_query();

                // Amount of participnats
                $this->db->select('COUNT(DISTINCT tbl_course_event_participants.id) AS num_participants');
                $this->db->select('SUM(tbl_course_event_participants.price) AS sum_participants');
                $this->db->select('tbl_course_event_participants.course_event_id AS participant_event_id');
                $this->db->from('tbl_course_event_participants');
                $this->db->group_by('participant_event_id');
                $subquery2 = $this->db->get_compiled_select();
                $this->db->reset_query();

                // Course event details
                $this->db->select('tbl_course_event.id, tbl_course.id AS course_id, tbl_course.course_name, tbl_course_event.course_date, tbl_course_event.course_date_end, tbl_course_event.city, tbl_course_event.zip, tbl_course_event.maximum_participants');

                // SUU
                if ($apiKey == 1)
                {
                    $this->db->select('tbl_course.course_external_price');
                }

                // Assemblin
                if ($apiKey == 2)
                {
                    // use default price if 0 or null
                    $this->db->select('(CASE 
                    WHEN tbl_course_prices.price_assemblin = 0 THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_assemblin IS NULL THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_assemblin != 0 THEN tbl_course_prices.price_assemblin
                    END) AS course_external_price');
                }
                
                // Stena
                if ($apiKey == 3)
                {
                    // use default price if 0 or null
                    $this->db->select('(CASE 
                    WHEN tbl_course_prices.price_stena = 0 THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_stena IS NULL THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_stena != 0 THEN tbl_course_prices.price_stena
                    END) AS course_external_price');
                }

                $this->db->select('ghosts.num_ghosts');
                $this->db->select('participants.num_participants');
                $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
                $this->db->join('tbl_course_prices', 'tbl_course_prices.course_id = tbl_course.id', 'left');
                $this->db->join("($subquery) ghosts", 'ghosts.ghost_event_id = tbl_course_event.id', 'left');
                $this->db->join("($subquery2) participants", 'participants.participant_event_id = tbl_course_event.id', 'left');
                $this->db->from('tbl_course_event');
                $this->db->where('tbl_course_event.id', $id);
                
                $query = $this->db->get();
                
                if ($query->num_rows() > 0) {
                    $response = array(
                        'status' => 'success',
                        'message' => '',
                        'data' => $query->row()
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Inga rader retunerades.',
                        'data' => ''
                    );
                }
            }
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    
    public function register()
    {
        // check if post
        if($this->input->post())
        {
            // get participants
            $participants = $this->input->post('participants');

            // get number of participants
            $num_participants = count($participants);

		    // load form validation library
            $this->load->library('form_validation');            

            // perform validation on static fields
            $this->form_validation->set_rules("company_name", "Företag", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_address", "Utdelningsadress", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_city", "Ort", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_phone", "Telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company_registration", "Organisationsnummer", "required|max_length[20]|trim");
            $this->form_validation->set_rules("company_postal_zip", "Postnummer", "required|max_length[6]|trim");
            $this->form_validation->set_rules("contact_person", "Kontaktperson", "required|max_length[50]|trim");
            $this->form_validation->set_rules("company_email[]", "Epost", "valid_email|max_length[500]|trim");
            $this->form_validation->set_rules("courseID", "Kurs Id", "required|max_length[11]|numeric|trim");
            $this->form_validation->set_rules("apiKey", "API Nyckel", "required|max_length[11]|numeric|trim");

            // init counter
            $i = 1;
            
            // validate dynamic fields
		    do {
			    $this->form_validation->set_rules("participants[".$i."][first_name]", "Deltagare ".$i." Förnamn", "required|max_length[50]|trim");
                $this->form_validation->set_rules("participants[".$i."][last_name]", "Deltagare ".$i." Efternamn", "required|max_length[50]|trim");
                $this->form_validation->set_rules("participants[".$i."][social_security_number]", "Deltagare ".$i." Personnummer", "required|max_length[50]|trim");
                $this->form_validation->set_rules("participants[".$i."][email]", "Deltagare ".$i." E-mail", "required|valid_email|max_length[50]|trim");
                $this->form_validation->set_rules("participants[".$i."][phone]", "Deltagare ".$i." Telefon", "required|max_length[20]|trim");
			
			    $i++;
		    } while ($i < $num_participants);
            
            // perform validation
            if ( $this->form_validation->run() == FALSE )
            {
			    $response = array(
				    'status' => 'error',
				    'message' => validation_errors(' ',' '),
				    'data' => ''
			    );
		    }
		    else
		    {
                // get course id
                $courseID = $this->input->post('courseID', true);

                // get api key as permission
                $permission = $this->input->post('apiKey', true);

                // get amount of ghost seats
                $this->db->select('SUM(tbl_course_event_ghosts.amount) AS num_ghosts');
                $this->db->select('tbl_course_event_ghosts.course_event_id AS ghost_event_id');
                $this->db->from('tbl_course_event_ghosts');
                $this->db->group_by('ghost_event_id');
                $subquery = $this->db->get_compiled_select();
                $this->db->reset_query();

                // get amount of participants
                $this->db->select('COUNT(DISTINCT tbl_course_event_participants.id) AS num_participants');
                $this->db->select('SUM(tbl_course_event_participants.price) AS sum_participants');
                $this->db->select('tbl_course_event_participants.course_event_id AS participant_event_id');
                $this->db->from('tbl_course_event_participants');
                $this->db->group_by('participant_event_id');
                $subquery2 = $this->db->get_compiled_select();
                $this->db->reset_query();

                // get course event details
                $this->db->select('tbl_course_event.id, tbl_course.id AS course_id, tbl_course.course_name, tbl_course.course_external_price, tbl_course_event.course_date, tbl_course_event.course_date_end, tbl_course_event.city, tbl_course_event.zip, tbl_course_event.maximum_participants');

                // SUU
                if (is_numeric($permission) && $permission == 1)
                {
                    $this->db->select('tbl_course.course_external_price');
                }

                // Assemblin
                if (is_numeric($permission) && $permission == 2)
                {
                    // use default price if 0 or null
                    $this->db->select('(CASE 
                    WHEN tbl_course_prices.price_assemblin = 0 THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_assemblin IS NULL THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_assemblin != 0 THEN tbl_course_prices.price_assemblin
                    END) AS course_external_price');
                }
                
                // Stena
                if (is_numeric($permission) && $permission == 3)
                {
                    // use default price if 0 or null
                    $this->db->select('(CASE 
                    WHEN tbl_course_prices.price_stena = 0 THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_stena IS NULL THEN tbl_course.course_external_price
                    WHEN tbl_course_prices.price_stena != 0 THEN tbl_course_prices.price_stena
                    END) AS course_external_price');
                }

                $this->db->select('ghosts.num_ghosts');
                $this->db->select('participants.num_participants');
                $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
                $this->db->join('tbl_course_prices', 'tbl_course_prices.course_id = tbl_course.id', 'left');
                $this->db->join("($subquery) ghosts", 'ghosts.ghost_event_id = tbl_course_event.id', 'left');
                $this->db->join("($subquery2) participants", 'participants.participant_event_id = tbl_course_event.id', 'left');
                $this->db->from('tbl_course_event');
                $this->db->where('tbl_course_event.id', $courseID);
			    $query = $this->db->get();

                // did we get any results?
                if ($query->num_rows() > 0) 
                {
                    // course information
                    $courseInfo = $query->row();

                    // number of seats left
                    $num_spots_left = $courseInfo->maximum_participants-$courseInfo->num_ghosts-$courseInfo->num_participants;
				
			        // Check if number of participants can book
                    if ($num_participants <= $num_spots_left) 
                    {
                        // declare company id
                        $companyId = null;

                        // get company info
                        $company_name = $this->input->post('company_name', true);
                        $company_postal_address = $this->input->post('company_postal_address', true);
                        $company_postal_city = $this->input->post('company_postal_city', true);
                        $company_phone = $this->input->post('company_phone', true);
                        $company_registration = $this->input->post('company_registration', true);
                        $company_postal_zip = $this->input->post('company_postal_zip', true);
                        $contact_person = $this->input->post('contact_person', true);
                        $company_email = $this->input->post('company_email', true);

                        $this->db->select('tbl_customer.id');
                        $this->db->from('tbl_customer');
                        $this->db->where('tbl_customer.company_registration', $company_registration);
                        $query = $this->db->get();

                        // check for results
                        if ($query->num_rows() > 0) {
                            $companyId = $query->row()->id;
                        } 
                        else 
                        {
                            if (!$this->input->post('company_email'))
                            {
                                $company_email = '';
                            }
                            if (!empty($company_email))
                            {
                                $company_email = implode(",", $company_email);
                            }

                            $prepdata = array(
                                'company_name' => $company_name,
                                'company_postal_address' => $company_postal_address,
                                'company_postal_zip' => $company_postal_zip,
                                'company_postal_city' => $company_postal_city,
                                'contact_person' => $contact_person,
                                'company_email' => $company_email,
                                'company_phone' => $company_phone,
                                'company_registration' => $company_registration,
                                'create_time' => date('Y-m-d H:i:s')
                            );

                            $this->db->insert('tbl_customer', $prepdata);
                            $companyId = $this->db->insert_id();
                        }

					    // loop through all participants
					    foreach ($participants as $participant) {
	
                            // declare participant id
						    $participantId = null;
						    
						    // Check if participant exist
						    // Format social security number to match table format
						    $socialSecurityNumber = $this->security->xss_clean($participant['social_security_number']);
						    $socialSecurityNumber = str_replace('-','',$socialSecurityNumber);
						    $socialSecurityNumber = str_replace(' ','',$socialSecurityNumber);
                            if(strlen($socialSecurityNumber) == 12)
                            {
							    $socialSecurityNumber = substr($socialSecurityNumber, 2);
						    }
                            if(strlen($socialSecurityNumber) == 10)
                            {
    							$socialSecurityNumber = substr_replace($socialSecurityNumber, '-', strlen($socialSecurityNumber)-4, 0);
                            }

		    				// Check participant, if not exist, create, else get participant id (use social_security_number for matching)
			    			$this->db->select('tbl_participant.id, tbl_participant.personalnumber');
				    		$this->db->from('tbl_participant');
					    	$this->db->where('tbl_participant.personalnumber', $socialSecurityNumber);
                            $query = $this->db->get();

                            // check for results
                            if ($query->num_rows() > 0) 
                            {
                                $participantId = $query->row()->id;
                            }
                            else
                            {
                                $prepdata = array(
                                    'company_id' => $companyId,
                                    'first_name' => $participant['first_name'],
                                    'last_name' => $participant['last_name'],
                                    'phone' => $participant['phone'],
                                    'email' => $participant['email'],
                                    'personalnumber' => $socialSecurityNumber,
                                    'create_time' => date('Y-m-d H:i:s')
                                );

                                $this->db->insert('tbl_participant', $prepdata);
                                $participantId = $this->db->insert_id();
                            }
						
                            // Add participant to event
                            $prepdata = array(
                                'course_event_id' => $courseInfo->id,
                                'participant_id' => $participantId,
                                'verified' => 0,
                                'sales_person' => 'Hemsida',
                                'price' => $courseInfo->course_external_price,
                                'mail_sent' => -1,
                                'diploma_generated' => 0,
                                'cert_generated' => 0,
                                'invoice_sent' => 0
                            );

						    $this->db->insert('tbl_course_event_participants', $prepdata);

                        }

                        $response = array(
                            'status'	=>	'success',
                            'message'	=>	'',
                            'data'		=>	$courseInfo
                        );


				    }
				    else
				    {
					    $response = array(
							'status' => 'error',
							'message' => 'Det finns inte tillrakligt manga platser kvar att boka pa denna utbildningen.',
							'data' => ''
					    );
				    }

			    }
			    else
			    {
				    $response = array(
					    'status' => 'error',
					    'message' => 'Kunde inte hitta kursen.',
					    'data' => ''
				    );
			    }
		    }
        }
        else
        {
            $response = array(
                'status' => 'error',
                'message' => 'No post data found',
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
    
    /**
     * Get the HTML formatted description text
    */
    public function api_get_external_description($id = null, $json = true)
    {
        if ($id == null)
        {
            $response = array(
                'status' => 'error',
                'message' => '',
                'data' => ''
            );
        }
        else
        {
            if (!is_numeric($id))
            {
                $response = array(
                    'status' => 'error',
                    'message' => '',
                    'data' => ''
                );
            }
            else
            {
                $id = $this->security->xss_clean($id);
                
                $this->db->select("course_external_description");
                $this->db->from('tbl_course');
                $this->db->where('id', $id);
                $query = $this->db->get();
                $result = $query->row();
                
                $response = array(
                    'status' => 'success',
                    'message' => '',
                    'data' => $result->course_external_description
                );
            }
        }
        
        if ($json)
        {
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
        else
        {
            return $response;
        }
    }
    
    public function api_get_courses($filter = 'all', $permission = '', $cid = '', $callback = '', $getRequest = '')
    {
        /* Check if we need some sorting */
        if (isset($getRequest) && !empty($getRequest))
        {
            $_POST = $getRequest;
        }
        
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
            0 => 'tbl_course.course_name',
            1 => 'tbl_course_event.city',
            2 => 'tbl_course_event.course_date'
        );
        
        /* Declare order variables */
        $order_column = 2;
        $order_dir = 'DESC';
        
        /* If ordering, store the order values */
        if (isset($_POST['order'][0]) && !empty($_POST['order'][0]))
        {
            $o1 = $this->input->post('order', true);
        }
        
        if (isset($_POST['order'][0]['column']))
        {
            $order_column = $o1[0]['column'];
        }
        
        if (isset($_POST['order'][0]['dir']))
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

        // permission array
        $permission_data = explode(',', $permission);
        
        // Amount of ghosts
        $this->db->select('SUM(tbl_course_event_ghosts.amount) AS num_ghosts');
        $this->db->select('tbl_course_event_ghosts.course_event_id AS ghost_event_id');
        $this->db->from('tbl_course_event_ghosts');
        $this->db->group_by('ghost_event_id');
        $subquery = $this->db->get_compiled_select();
        $this->db->reset_query();
        
        // Amount of participnats
        $this->db->select('COUNT(DISTINCT tbl_course_event_participants.id) AS num_participants');
        $this->db->select('SUM(tbl_course_event_participants.price) AS sum_participants');
        $this->db->select('tbl_course_event_participants.course_event_id AS participant_event_id');
        $this->db->from('tbl_course_event_participants');
        $this->db->group_by('participant_event_id');
        $subquery2 = $this->db->get_compiled_select();
        $this->db->reset_query();
        
        // Course event details
        $this->db->select('tbl_course_event.id, tbl_course.id AS course_id, tbl_course.course_name, tbl_course_event.course_date, tbl_course_event.city, tbl_course_event.maximum_participants');
        
        // SUU
        if (!empty($permission_data) && in_array(1, $permission_data))
        {
            $this->db->select('tbl_course.course_external_price');
        }

        // Assemblin
        if (!empty($permission_data) && in_array(2, $permission_data))
        {
            // use default price if 0 or null
            $this->db->select('(CASE 
            WHEN tbl_course_prices.price_assemblin = 0 THEN tbl_course.course_external_price
            WHEN tbl_course_prices.price_assemblin IS NULL THEN tbl_course.course_external_price
            WHEN tbl_course_prices.price_assemblin != 0 THEN tbl_course_prices.price_assemblin
            END) AS course_external_price');
        }
        
        // Stena
        if (!empty($permission_data) && in_array(3, $permission_data))
        {
            // use default price if 0 or null
            $this->db->select('(CASE 
            WHEN tbl_course_prices.price_stena = 0 THEN tbl_course.course_external_price
            WHEN tbl_course_prices.price_stena IS NULL THEN tbl_course.course_external_price
            WHEN tbl_course_prices.price_stena != 0 THEN tbl_course_prices.price_stena
            END) AS course_external_price');
        }

        $this->db->select('ghosts.num_ghosts');
        $this->db->select('participants.num_participants');
        $this->db->where('tbl_course_event.customized', 0);
        $this->db->where('tbl_course_event.canceled', 0);
        $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
        $this->db->join('tbl_course_prices', 'tbl_course_prices.course_id = tbl_course.id', 'left');
        $this->db->join("($subquery) ghosts", 'ghosts.ghost_event_id = tbl_course_event.id', 'left');
        $this->db->join("($subquery2) participants", 'participants.participant_event_id = tbl_course_event.id', 'left');
        $this->db->from('tbl_course_event');

        // Filter permissions
        if (!empty($permission))
        {
            if (is_numeric($permission))
            {
                $this->db->where("FIND_IN_SET($permission, tbl_course.apipermissions)");
            }
        }

        // Filter only courses from todays date
        if ($filter == 'newer')
        {
            $this->db->where("str_to_date(tbl_course_event.course_date, '%Y-%m-%d %H:%i') >=", date('Y-m-d H:i'));
        }
        
        // Filter on specific course don't forget to urldecode
        if (!empty($cid))
        {
            if (is_numeric($cid))
            {
                $this->db->where('tbl_course.id', $cid);
            }
        }
        
		//$this->db->group_by(array("tbl_course_event.id"));
        
        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(tbl_course.course_name LIKE '%$search%' OR tbl_course_event.city LIKE '%$search%' OR tbl_course_event.course_date LIKE '%$search%')", null, true);
        }
        
        /* Use custom order only if order_column isset and not empty */
        if (isset($order_column) && isset($order_dir))
        {
            $this->db->order_by($table_fields[$order_column], $order_dir);
        }
        else
        {
            $this->db->order_by('tbl_course_event.id', $order_dir);
        }
        
        /* Count filtered result if searching */
        if (!empty($search) || $filter == 'newer' || !empty($cid))
        {
            $tempdb = clone $this->db;
            $tempquery = $tempdb->get();
            $recordsFiltered = $tempquery->num_rows();
        }
        
        /* Limit the results */
        $this->db->limit($limit, $start);
		
        /* Perform the query */
        $query = $this->db->get();
		$data = $query->result_array();
        
        /* Count the results */
        $recordsTotal = $this->db->count_all('tbl_course_event');
        
        /* Set recordsFiltered to recordsTotal if the user isn't searching */
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
        
        if (isset($callback) && !empty($callback))
        {
            $_output = $callback."({$_output});";
        }
        
		$this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);
    }
    
    /**
     * Get all availible courses and output as JSON
    */
    public function getCourseList()
    {
        $this->db->select('id, course_name, course_description, course_time, maximum_participants, validity');
        $this->db->from('tbl_course');
        $this->db->order_by('course_name', 'ASC');
        
        /* Perform the query */
        $query = $this->db->get();
		$data = $query->result_array();
        $_output = json_encode($data);
        
        /* Use CI3 output class to display the results */ 
        $this->output->set_content_type('application/json');
        $this->output->set_status_header('200');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->output->set_header('Content-Length: '. strlen($_output));
        $this->output->set_output($_output);
    }
}
