<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

    public $error_message = null;
    public $success_message = null;
    public $auth = '';

    /**
     * This controllers constructor
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->model(array('customer_model','participant_model','teacher_model','messages_model'));
        $this->load->helper(array('gravatar'));

        if (!$this->ion_auth->logged_in())
        { 
            redirect('auth/login');
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
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/customers
	 *	- or -
	 * 		http://example.com/customers/index
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
            'customer_ajax' => 'assets/pages/scripts/customers_ajax.min.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );

        $view_data = array(
            'title_part1' => 'Företag',
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
        $this->load->view('content/customers');
        $this->load->view('includes/footers/footer');
    }

    /**
	 * New customer Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/customers/new_customer
	 *	- or -
	 * 		http://example.com/customers/new_customer/index
	 */
    public function new_customer()
    {

        if($this->input->post())
        {
            $this->form_validation->set_rules("company_name", "Företag", "required|max_length[100]|trim|is_unique[tbl_customer.company_name]");
            $this->form_validation->set_rules("company_location_address", "Adress", "max_length[100]|trim");
            $this->form_validation->set_rules("company_location_zip", "Postnummer", "max_length[6]|trim");
            $this->form_validation->set_rules("company_location_city", "Ort", "max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_address", "Utdelningsadress", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_zip", "Postnummer", "required|max_length[6]|trim");
            $this->form_validation->set_rules("company_postal_city", "Ort", "required|max_length[100]|trim");
            $this->form_validation->set_rules("contact_person", "Kontaktperson", "required|max_length[50]|trim");
            $this->form_validation->set_rules("company_email[]", "Epost", "valid_email|max_length[500]|trim");
            $this->form_validation->set_rules("company_phone", "Telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company_secondary_phone", "Alternativ telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company_registration", "Organisationsnummer", "required|max_length[20]|trim");
            $this->form_validation->set_rules("company_vat", "VAT-nummer", "max_length[20]|trim");
            $this->form_validation->set_rules("company_website", "Hemsida", "valid_url|max_length[100]|trim");
            $this->form_validation->set_rules("number_of_employees", "Antal anställda", "numeric|max_length[10]|trim");

            if ($this->form_validation->run() == FALSE)
            {  
                $this->error_message = validation_errors(' ', ' ');
            }
            else
            {
                $contact_people = $this->input->post('contact_people');
                
                for($i = 0; $i < count($contact_people); $i++)
                {
                    $contact_people[$i] = json_decode($contact_people[$i]);
                }

                $company_name = $this->input->post('company_name');
                $company_location_address = $this->input->post('company_location_address');
                $company_location_zip = $this->input->post('company_location_zip');
                $company_location_city = $this->input->post('company_location_city');
                $company_postal_address = $this->input->post('company_postal_address');
                $company_postal_zip = $this->input->post('company_postal_zip');
                $company_postal_city = $this->input->post('company_postal_city');
                $contact_person = $this->input->post('contact_person');
                $company_email = $this->input->post('company_email');

                if (!$this->input->post('company_email'))
                {
                    $company_email = '';
                }
                if (!empty($company_email))
                {
                    $company_email = implode(",", $company_email);
                }

                $company_phone = $this->input->post('company_phone');
                $company_secondary_phone = $this->input->post('company_secondary_phone');
                $company_registration = $this->input->post('company_registration');
                $company_vat = $this->input->post('company_vat');
                $company_website = $this->input->post('company_website');
                $number_of_employees = $this->input->post('number_of_employees');
                $freetext = $this->input->post('freetext');

                $prepdata = array(
                    'company_name' => $company_name,
                    'company_location_address' => $company_location_address,
                    'company_location_zip' => $company_location_zip,
                    'company_location_city' => $company_location_city,
                    'company_postal_address' => $company_postal_address,
                    'company_postal_zip' => $company_postal_zip,
                    'company_postal_city' => $company_postal_city,
                    'contact_person' => $contact_person,
                    'company_email' => $company_email,
                    'company_phone' => $company_phone,
                    'company_secondary_phone' => $company_secondary_phone,
                    'company_registration' => $company_registration,
                    'company_vat' => $company_vat,
                    'company_website' => $company_website,
                    'number_of_employees' => $number_of_employees,
                    'freetext' => $freetext,
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'create_time' => date('Y-m-d H:i:s')
                );

                if ($this->customer_model->insert($prepdata, $contact_people))
                {
                    redirect('customers');
                    exit;
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när kunden skulle skapas.';
                }
            }
        }

        $page_styles = array(
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );

        $page_scripts = array(
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'new_customer' => 'assets/pages/scripts/src/customers_new_edit.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );

        $view_data = array(
            'title_part1' => 'Nytt företag',
            'title_part2' => 'Svensk Uppdragsutbildning',
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
        $this->load->view('content/new_customer');
        $this->load->view('includes/footers/footer');
    }

    /**
	 * Edit customer Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/customers/edit_customer
	 *	- or -
	 * 		http://example.com/customers/edit_customer/index
	 */
    public function edit_customer($id = null, $readonly = false)
    {
        if ($id == null)
        {
            redirect('customers');
            exit;
        }
        else
        {
            if (!is_numeric($id))
            {
                redirect('customers');
                exit;
            }
            else
            {
                $id = $this->security->xss_clean($id);
            }
        }

        if($this->input->post())
        {
            if($this->input->post('company_name') != $this->input->post('cn_org')) 
            {
                $is_unique =  '|is_unique[tbl_customer.company_name]';
            } 
            else 
            {
                $is_unique =  '';
            }

            $this->form_validation->set_rules("company_name", "Företag", "required|max_length[100]|trim$is_unique");
            $this->form_validation->set_rules("company_location_address", "Adress", "max_length[100]|trim");
            $this->form_validation->set_rules("company_location_zip", "Postnummer", "max_length[6]|trim");
            $this->form_validation->set_rules("company_location_city", "Ort", "max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_address", "Utdelningsadress", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_zip", "Postnummer", "required|max_length[6]|trim");
            $this->form_validation->set_rules("company_postal_city", "Ort", "required|max_length[100]|trim");
            $this->form_validation->set_rules("contact_person", "Kontaktperson", "required|max_length[50]|trim");
            $this->form_validation->set_rules("company_email[]", "Epost", "valid_email|max_length[500]|trim");
            $this->form_validation->set_rules("company_phone", "Telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company_secondary_phone", "Alternativ telefon", "max_length[50]|trim");
            $this->form_validation->set_rules("company_registration", "Organisationsnummer", "required|max_length[20]|trim");
            $this->form_validation->set_rules("company_vat", "VAT-nummer", "max_length[20]|trim");
            $this->form_validation->set_rules("company_website", "Hemsida", "valid_url|max_length[100]|trim");
            $this->form_validation->set_rules("number_of_employees", "Antal anställda", "numeric|max_length[10]|trim");

            if ($this->form_validation->run() == FALSE)
            {  
                $this->error_message = validation_errors(' ', ' ');
            }
            else
            {
                
                $contact_people = $this->input->post('contact_people');
                for($i = 0; $i < count($contact_people); $i++)
                {
                    $contact_people[$i] = json_decode($contact_people[$i]);
                }

                $company_name = $this->input->post('company_name');
                $company_location_address = $this->input->post('company_location_address');
                $company_location_zip = $this->input->post('company_location_zip');
                $company_location_city = $this->input->post('company_location_city');
                $company_postal_address = $this->input->post('company_postal_address');
                $company_postal_zip = $this->input->post('company_postal_zip');
                $company_postal_city = $this->input->post('company_postal_city');
                $contact_person = $this->input->post('contact_person');
                $company_email = $this->input->post('company_email[]');

                if (!$this->input->post('company_email'))
                {
                    $company_email = '';
                }
                if (!empty($company_email))
                {
                    $company_email = implode(",", $company_email);
                }

                $company_phone = $this->input->post('company_phone');
                $company_secondary_phone = $this->input->post('company_secondary_phone');
                $company_registration = $this->input->post('company_registration');
                $company_vat = $this->input->post('company_vat');
                $company_website = $this->input->post('company_website');
                $number_of_employees = $this->input->post('number_of_employees');
                $freetext = $this->input->post('freetext');

                $prepdata = array(
                    'company_name' => $company_name,
                    'company_location_address' => $company_location_address,
                    'company_location_zip' => $company_location_zip,
                    'company_location_city' => $company_location_city,
                    'company_postal_address' => $company_postal_address,
                    'company_postal_zip' => $company_postal_zip,
                    'company_postal_city' => $company_postal_city,
                    'contact_person' => $contact_person,
                    'company_email' => $company_email,
                    'company_phone' => $company_phone,
                    'company_secondary_phone' => $company_secondary_phone,
                    'company_registration' => $company_registration,
                    'company_vat' => $company_vat,
                    'company_website' => $company_website,
                    'number_of_employees' => $number_of_employees,
                    'freetext' => $freetext,
                    'edited_by' => $this->ion_auth->user()->row()->id,
                    'edit_time' => date('Y-m-d H:i:s')
                );

                if ($this->customer_model->update($id, $prepdata, $contact_people))
                {
                    $this->success_message = 'Kunden har sparats.';
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när kunden skulle skapas.';
                }
            }
        }

        $page_styles = array(
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
        );

        $page_scripts = array(
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'edit_customer' => 'assets/pages/scripts/src/customers_new_edit.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );

        if ($readonly)
        {
            $title_part1 = 'Visa företag';
        }
        else
        {
            $title_part1 = 'Redigera företag';
        }

        $view_data = array(
            'title_part1' => $title_part1,
            'title_part2' => 'Svensk Uppdragsutbildning',
            'readonly' => $readonly,
            'customer' => $this->customer_model->get($id),
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
        $this->load->view('content/edit_customer');
        $this->load->view('includes/footers/footer');
    }
    
    /**
	 * Delete customer function for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/customers/delete_customer
	 *	- or -
	 * 		http://example.com/customers/delete_customer/index
	 */
    public function delete_customer()
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

            if ($this->customer_model->delete($id))
            {
                if ($this->customer_model->delete_from_events($id))
                {
                    $response = array(
                        'status' => 'success'
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Företaget har plockats bort men vi kunde inte radera alla kopplingar i databasen.'
                    );
                }
            }
            else 
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Ett fel uppstod när företaget skulle tas bort.'
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
	 * Get participants function for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/customers/get_participants
	 *	- or -
	 * 		http://example.com/customers/get_participants/index
	 */
    public function get_participants($id = null)
    {
        if ($id == null)
        {
            $response = array(
                'status' => 'error',
                'message' => 'ID saknas.'
            );
        }
        else
        {
            if (!is_numeric($id))
            {
                $response = array(
                    'status' => 'error',
                    'message' => 'Felaktigt ID.'
                );
            }
            else
            {
                $id = $this->security->xss_clean($id);

                $results = $this->participant_model->get_by_company($id);

                $response = array(
                    'status' => 'success',
                    'message' => '',
                    'data' => $results
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
	 * Get participants ajax function for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/customers/get_customers_ajax
	 *	- or -
	 * 		http://example.com/customers/get_customers_ajax/index
	 */
    public function get_customers_ajax()
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
            1 => 'company_name',
            2 => 'company_postal_address',
            3 => 'contact_person',
            4 => 'company_email',
            5 => 'company_phone'
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
        $this->db->select('id, company_name, company_postal_address, contact_person, company_phone, company_email');
        $this->db->from('tbl_customer');

        /* If searching, use CI3 like statements */
        if (!empty($search))
        {
            $this->db->where("(company_name LIKE '%$search%' OR company_postal_address LIKE '%$search%' OR contact_person LIKE '%$search%' OR company_email LIKE '%$search%' OR company_phone LIKE '%$search%')", null, true);
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
        $recordsTotal = $this->db->count_all('tbl_customer');

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

    public function get_contact_people_ajax($customer_id)
    {
        $results = $this->customer_model->get_contact_people($customer_id);

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