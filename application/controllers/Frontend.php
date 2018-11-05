<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->model(array('frontend_model'));
	}
	
	public function index()
	{
		redirect('login');
        exit;
	}
    
    /**
     * View course registration page
    */
    public function view($course_code = null)
    {
        // Check if course code is provided at all
        if ($course_code == null)
        {
            redirect('login');
            exit;
        }
        else
        {
            // xss_clean the course code
            $course_code = $this->security->xss_clean($course_code);
            
            // trim it
            $course_code = trim($course_code);
            
            // check if it's a valid course code
            if(!$this->frontend_model->is_course_code_valid($course_code))
            {
                // Store a message and redirect to login / dashboard
                $this->session->set_flashdata('msg', '<div class="alert alert-danger"><button class="close" data-close="alert"></button><span>Felaktig kod. Försök igen.</span></div>');
                redirect('login');
                exit;
            }
            
            // Page styles to be loaded
            $page_styles = array(
                'fontawesome' => 'assets/global/plugins/font-awesome/css/font-awesome.min.css'
            );
            
            // Page scripts to be loaded
            $page_scripts = array(
                'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
                'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
                'page_specific' => 'assets/pages/scripts/frontend.min.js'
            );
            
            // Get course event data based on course_code
            $course_event_data = $this->frontend_model->get_course_event_data($course_code);
            $course_data = $this->frontend_model->get_course_data($course_event_data->course_id);
            
            // Get companies relevant to this event
            $relevant_companies = $this->frontend_model->get_relevant_companies($course_event_data->id);
            
            // Prepare data to be sent to the view
            $view_data = array(
                'title_part1' => 'Registrering',
                'title_part2' => 'Svensk Uppdragsutbildning',
                'course_event_data' => $course_event_data,
                'course_data' => $course_data,
                'relevant_companies' => $relevant_companies,
                'page_scripts' => $page_scripts
            );

            // Load the view
            $this->load->view('includes/headers/frontend_header', $view_data);
            $this->load->view('content/frontend');
            $this->load->view('includes/footers/frontend_footer');
            
        }
    }
	
    /**
     * Check if course code is valid
    */
	public function check()
	{
		$this->form_validation->set_rules('course_code', 'Kurskod', 'required|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-danger"><button class="close" data-close="alert"></button><span>'.validation_errors(' ', ' ').'</span></div>');
            
            if ($this->input->post('origin', true)) 
            {
                if ($this->input->post('origin', true) == 'dashboard')
                {
                    redirect('dashboard');
                    exit;
                }
                else
                {
                    redirect('login');
                    exit;
                }
            }
            else
            {
                redirect('login');
                exit;
            }
		}
		else
		{
            $course_code = $this->input->post('course_code', true);
            if($this->frontend_model->is_course_code_valid($course_code))
            {
                redirect(site_url('frontend/view/'.$course_code));
            }
            else
            {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger"><button class="close" data-close="alert"></button><span>Felaktig kod. Försök igen.</span></div>');
                
                if ($this->input->post('origin', true)) 
                {
                    if ($this->input->post('origin', true) == 'dashboard')
                    {
                        redirect('dashboard');
                        exit;
                    }
                    else
                    {
                        redirect('login');
                        exit;
                    }
                }
                else
                {
                    redirect('login');
                    exit;
                }
                
            }
		}
	}
    
    /**
     * Check if particiapant is registered
    */
    public function check_participant($event_id = null, $personalnumber = null, $foreign_ssn = null)
    {
        if ($event_id == null || $personalnumber == null || $foreign_ssn == null)
        {
            $response = array(
                'status' => 'error',
                'message' => 'Felaktigt personnummer. Personnummer ska anges i formatet: ÅÅMMDD-XXXX.',
                'data' => ''
            );
        }
        else
        {
            $formd = array(
                'event_id' => $event_id,
                'personalnumber' => $personalnumber,
                'foreign_ssn' => $foreign_ssn
            );

            $this->form_validation->set_data($formd);
            
            $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|trim',
                array(
                    'required' => 'ID för detta utbildningstillfälle saknas. Testa ladda om sidan med F5 tangenten.',
                    'numeric' => 'ID för detta utbildningstillfälle saknas. Testa ladda om sidan med F5 tangenten.'
                )
            );
            $this->form_validation->set_rules('personalnumber', 'Personnummer', 'required|max_length[50]|trim',
                array(
                    'exact_length' => 'Felaktigt personnummer. Personnummer ska anges i formatet: ÅÅÅÅMMDD-XXXX.'                    
                )
            );
            $this->form_validation->set_rules('foreign_ssn', 'Format på Personnummer', 'required|numeric|trim',
                array(
                    'required' => 'Format på personnummer saknas. Testa ladda om sidan med F5 tangenten.',
                    'numeric' => 'Format på personnummer saknas. Testa ladda om sidan med F5 tangenten.'
                )
            );
            
            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' '),
                    'data' => ''
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $personalnumber = $this->security->xss_clean($personalnumber);
                $foreign_ssn = $this->security->xss_clean($foreign_ssn);
                $format_error = false;
                $trigger_words = array('Å', 'M', 'D', 'X');
                
                // Swedish personal number
                if ($foreign_ssn != 1) 
                {                
                    if (word_match($trigger_words, $personalnumber))
                    {
                        $format_error = true;
                    }
                }
                
                if ($format_error)
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Felaktigt personnummer. Personnummer ska anges i formatet: ÅÅMMDD-XXXX.',
                        'data' => ''
                    );
                }
                else
                {
                    /**
                     * Is the participant already registered in our system
                    */
                    if ($participant_data = $this->frontend_model->participant_exists($personalnumber))
                    {
                        /**
                         * Is the participant already connected to this event
                        */
                        if ($this->frontend_model->is_registered($event_id, $participant_data->id) != false)
                        {
                            /**
                             * Try to change status to verified
                            */
                            if ($this->frontend_model->change_status($event_id, $participant_data->id))
                            {
                                $response = array(
                                    'status' => 'verified',
                                    'message' => 'Du är nu registrerad!',
                                    'data' => ''
                                );
                            }
                            else
                            {
                                $response = array(
                                    'status' => 'error',
                                    'message' => 'Deltagaren är redan registrerad men kunde inte verifieras.',
                                    'data' => ''
                                );
                            }
                        }
                        else
                        {
                            $response = array(
                                'status' => 'success',
                                'message' => '',
                                'data' => $participant_data
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => 'success',
                            'message' => '',
                            'data' => ''
                        );
                    }
                }
            }
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    
    /**
     * Register participant for an event
    */
    public function register_participant()
    {
        // Set validation rules
        $this->form_validation->set_rules('company_id', 'Företag', 'required|max_length[11]|numeric|trim');
        $this->form_validation->set_rules('email', 'E-post', 'max_length[50]|valid_email|trim');
        $this->form_validation->set_rules('phone', 'Telefon', 'required|max_length[50]|trim');
        $this->form_validation->set_rules('event_id', 'ID för kurskod', 'required|max_length[11]|numeric|trim');
        $this->form_validation->set_rules('first_name', 'Förnamn', 'required|min_length[2]|max_length[50]|trim');
        $this->form_validation->set_rules('last_name', 'Efternamn', 'required|min_length[2]|max_length[50]|trim');
        $this->form_validation->set_rules('personalnumber', 'Personnummer', 'required|max_length[50]|trim');
        $this->form_validation->set_rules('foreign_ssn', 'Jag saknar svenskt personnummer', 'required|max_length[1]|numeric|trim');
        
        // Run the validation
        if ($this->form_validation->run() == FALSE)
        {
            $response = array(
                'status' => 'error',
                'message' => validation_errors(' ', ' '),
                'data' => ''
            );
        }
        else
        {
            // Store post data as variables and perform xss_clean
            $company_id = $this->input->post('company_id', true);
            $email = $this->input->post('email', true);
            $phone = $this->input->post('phone', true);
            $event_id = $this->input->post('event_id', true);
            $first_name = ucname($this->input->post('first_name', true));
            $last_name = ucname($this->input->post('last_name', true));
            $personalnumber = $this->input->post('personalnumber', true);
            $foreign_ssn = $this->input->post('foreign_ssn', true);
            
            // If participant_id is set then the user is already registered
            if ($this->input->post('participant_id', true))
            {
                $participant_id = $this->input->post('participant_id', true);
                
                // Prepare updated data for the participant
                $update_data = array(
                    'company_id' => $company_id,
                    'foreign_ssn' => $foreign_ssn,
                    'personalnumber' => $personalnumber,
                    'first_name' => $first_name, 
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone
                );
                
                // Try to update the participant data
                if ($this->frontend_model->update_participant($participant_id, $update_data))
                {
                    // Get information abount the company, if the company is registred and connected
                    $ghost_data = $this->frontend_model->is_company_registered($event_id, $company_id);
                
                    // Get information about the participants company
                    if ($ghost_data != false)
                    {
                        // Connect the participant to this event
                        if ($this->frontend_model->connect_participant($event_id, $participant_id, $ghost_data->sales_person, $ghost_data->price))
                        {
                            // remove the company if amount is 1 or less
                            if ($ghost_data->amount <= 1)
                            {
                                if ($this->frontend_model->remove_ghost_company($event_id, $company_id))
                                {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => 'Du är nu registrerad!',
                                        'data' => ''
                                    );
                                }
                                else
                                {
                                    $response = array(
                                        'status' => 'error',
                                        'message' => 'Deltagaren är tillagd men ett fel uppstod när företaget skulle tas bort.',
                                        'data' => ''
                                    );
                                }
                            }
                            else
                            {
                                // Get the existing amount and remove 1
                                $new_amount = $ghost_data->amount - 1;

                                if ($this->frontend_model->remove_one_from_company($event_id, $company_id, $new_amount))
                                {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => 'Du är nu registrerad!',
                                        'data' => ''
                                    );
                                }
                                else
                                {
                                    $response = array(
                                        'status' => 'error',
                                        'message' => 'Deltagaren är tillagd men ett fel uppstod när antalet bokade platser skulle uppdateras.',
                                        'data' => ''
                                    );
                                }
                            }
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Ett fel uppstod när deltagaren skulle kopplas till utbildningstillfället.',
                                'data' => ''
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod, företaget kunde inte hittas.',
                            'data' => ''
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när deltagaren skulle registreras.',
                        'data' => ''
                    );
                }
            }
            else
            {
                // Prepare the new participant
                $insert_data = array(
                    'company_id' => $company_id,
                    'foreign_ssn' => $foreign_ssn,
                    'personalnumber' => $personalnumber,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone
                );
                
                // Insert the new participant and return the participant_id on success
                $participant_id = $this->frontend_model->insert_participant($insert_data);
                
                if ($participant_id != false)
                {
                    // Get information about the participants company
                    $ghost_data = $this->frontend_model->is_company_registered($event_id, $company_id);
                    
                    if ($ghost_data != false)
                    {
                        // Connect the participant to this event
                        if ($this->frontend_model->connect_participant($event_id, $participant_id, $ghost_data->sales_person, $ghost_data->price))
                        {
                            // remove the company if amount is 1 or less
                            if ($ghost_data->amount <= 1)
                            {
                                if ($this->frontend_model->remove_ghost_company($event_id, $company_id))
                                {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => 'Du är nu registrerad!',
                                        'data' => ''
                                    );
                                }
                                else
                                {
                                    $response = array(
                                        'status' => 'error',
                                        'message' => 'Deltagaren är tillagd men ett fel uppstod när företaget skulle tas bort.',
                                        'data' => ''
                                    );
                                }
                            }
                            else
                            {
                                // Get the existing amount and remove 1
                                $new_amount = $ghost_data->amount - 1;
                                
                                if ($this->frontend_model->remove_one_from_company($event_id, $company_id, $new_amount))
                                {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => 'Du är nu registrerad!',
                                        'data' => ''
                                    );
                                }
                                else
                                {
                                    $response = array(
                                        'status' => 'error',
                                        'message' => 'Deltagaren är tillagd men ett fel uppstod när antalet bokade platser skulle uppdateras.',
                                        'data' => ''
                                    );
                                }
                            }
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Ett fel uppstod när deltagaren skulle kopplas till utbildningstillfället.',
                                'data' => ''
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod, företaget kunde inte hittas.',
                            'data' => ''
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när deltagaren skulle registreras.',
                        'data' => ''
                    );
                }
            }
        }
        
        // Encode and send response
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    
    /**
     * Add more seats or register a new company
    */
    public function register_company()
    {
        // Set validation rules
        $this->form_validation->set_rules('company_reg', 'Org.nr', 'required|max_length[11]|trim');
        $this->form_validation->set_rules('company_amount', 'Antal platser', 'required|min_length[1]|max_length[5]|numeric|trim');
        $this->form_validation->set_rules('company_event_id', 'ID för kurskod', 'required|max_length[11]|numeric|trim');
        
        // Run the validation
        if ($this->form_validation->run() == FALSE)
        {
            $response = array(
                'status' => 'error',
                'message' => validation_errors(' ', ' '),
                'data' => ''
            );
        }
        else
        {
            $company_reg = $this->input->post('company_reg', true);
            $company_amount = $this->input->post('company_amount', true);
            $event_id = $this->input->post('company_event_id', true);
            
            $trigger_words = array('Å', 'M', 'D', 'X');
                
            if (word_match($trigger_words, $company_reg))
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Felaktigt organisationsnummer. Organisationsnummer ska anges i formatet: XXXXXX-XXXX.',
                    'data' => ''
                );
            }
            else
            {
                $company_data = $this->frontend_model->company_exists($company_reg);
                if ($company_data != false)
                {
                    $ghost_data = $this->frontend_model->is_company_registered($event_id, $company_data->id);
                    if ($ghost_data != false)
                    {
                        $new_amount = $ghost_data->amount + $company_amount;
                        
                        if ($this->frontend_model->add_to_company($event_id, $company_data->id, $new_amount))
                        {
                            $response = array(
                                'status' => 'success',
                                'message' => '',
                                'data' => $company_data
                            );
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Det gick inte att uppdatera antalet platser.',
                                'data' => ''
                            );
                        }
                    }
                    else
                    {
                        if ($this->frontend_model->add_ghost_company($event_id, $company_data->id, $company_amount) != false)
                        {
                            $response = array(
                                'status' => 'success',
                                'message' => '',
                                'data' => $company_data
                            );
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Det gick inte att lägga till företaget.',
                                'data' => ''
                            );
                        }
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'load_more',
                        'message' => '',
                        'data' => ''
                    );                        
                }
            }
        }
        
        // Encode and send response
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    
    /**
     * Create a new company
    */
    public function create_company()
    {
        // Set validation rules
        $this->form_validation->set_rules("ccompany_name", "Företag", "required|max_length[100]|trim");
        $this->form_validation->set_rules("ccompany_reg", "Org.nr", "required|max_length[11]|trim");
        $this->form_validation->set_rules("ccompany_postal_address", "Utdelningsadress", "required|max_length[100]|trim");
        $this->form_validation->set_rules("ccompany_postal_zip", "Postnummer", "required|max_length[6]|trim");
        $this->form_validation->set_rules("ccompany_postal_city", "Ort", "required|max_length[100]|trim");
        $this->form_validation->set_rules("ccontact_person", "Kontaktperson", "required|max_length[50]|trim");
        $this->form_validation->set_rules("ccompany_email", "Epost", "valid_email|max_length[50]|trim");
        $this->form_validation->set_rules("ccompany_phone", "Telefon", "required|max_length[500]|trim");
        $this->form_validation->set_rules('ccompany_event_id', 'ID för kurskod', 'required|max_length[11]|numeric|trim');
        $this->form_validation->set_rules('ccompany_amount', 'Antal platser', 'required|min_length[1]|max_length[5]|numeric|trim');
        
        // Run validation
        if ($this->form_validation->run() == FALSE)
        {  
            $response = array(
                'status' => 'error',
                'message' => validation_errors(' ', ' '),
                'data' => ''
            );
        }
        else
        {
            $company_name = $this->input->post('ccompany_name', true);
            $company_reg = $this->input->post('ccompany_reg', true);
            $company_postal_address = $this->input->post('ccompany_postal_address', true);
            $company_postal_zip = $this->input->post('ccompany_postal_zip', true);
            $company_postal_city = $this->input->post('ccompany_postal_city', true);
            $contact_person = ucname($this->input->post('ccontact_person', true));
            $company_email = $this->input->post('ccompany_email', true);
            $company_phone = $this->input->post('ccompany_phone', true);
            $event_id = $this->input->post('ccompany_event_id', true);
            $company_amount = $this->input->post('ccompany_amount', true);
            
            $insert_data = array(
                'company_name' => $company_name,
                'company_registration' => $company_reg,
                'company_postal_address' => $company_postal_address,
                'company_postal_zip' => $company_postal_zip,
                'company_postal_city' => $company_postal_city,
                'contact_person' => $contact_person,
                'company_email' => $company_email,
                'company_phone' => $company_phone,
                'create_time' => date('Y-m-d H:i:s')
            );
            
            $company_id = $this->frontend_model->insert_customer($insert_data);
            
            if ($company_id != false)
            {
                if ($this->frontend_model->add_ghost_company($event_id, $company_id, $company_amount) != false)
                {
                    $response = array(
                        'status' => 'success',
                        'message' => '',
                        'data' => array('event_id' => $event_id, 'company_id' => $company_id, 'amount' => $company_amount)
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Företaget har registrerats men inga platser kunde reserveras.',
                        'data' => ''
                    );
                }
            }
            else
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Det gick inte att registrera företaget.',
                    'data' => ''
                );
            }
        }
        
        // Encode and send response
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
