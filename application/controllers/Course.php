<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Course extends CI_Controller {



    public $error_message = null;

    public $success_message = null;

    public $auth = '';



	public function __construct()

	{

		parent::__construct();

		$this->load->library(array('ion_auth'));

		$this->load->model(array('course_model','teacher_model','messages_model','settings_model'));

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



		//disable cache

		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

		$this->output->set_header('Pragma: no-cache');

		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

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

			'customer_ajax' => 'assets/pages/scripts/course_ajax.min.js',

            'reports' => 'assets/pages/scripts/reports.min.js'

		);



		$view_data = array(

			'title_part1' => 'Utbildningar',

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

		$this->load->view('content/course');

		$this->load->view('includes/footers/footer');

	}



    /**

     * Create a new course

    */

	public function new_course()

    {

        // if posting

        if($this->input->post())

		{

            // set validation rules

			$this->form_validation->set_rules("course_name", "Namn", "required|max_length[50]|trim");

			$this->form_validation->set_rules("course_description", "Beskrivning", "required|max_length[255]|trim");



			$this->form_validation->set_rules("course_time_from", "Utbildningsdatum (kursstart)", "required|max_length[30]|trim");

			$this->form_validation->set_rules("course_time_end", "Utbildningsdatum (kursslut)", "required|max_length[30]|trim");



            $this->form_validation->set_rules("course_external_description", "Extern beskrivning", "trim");

            $this->form_validation->set_rules("course_time", "Tid", "required|max_length[50]|trim");

            $this->form_validation->set_rules("maximum_participants", "Max antal deltagare", "required|max_length[10]|trim");

            $this->form_validation->set_rules("course_external_price", "Pris", "required|max_length[11]|numeric|trim");

            $this->form_validation->set_rules("course_external_price_assemblin", "Pris (Assemblin)", "max_length[11]|numeric|trim");

            $this->form_validation->set_rules("course_external_price_stena", "Pris (Stena)", "max_length[11]|numeric|trim");

            $this->form_validation->set_rules("cert_template", "Certifikat", "max_length[50]|trim");

            $this->form_validation->set_rules("diploma_template", "Intyg", "max_length[50]|trim");

            $this->form_validation->set_rules("apipermissions[]", "API Rättigheter", "required|max_length[50]|trim");

            $this->form_validation->set_rules("validity", "Giltighetstid", "max_length[4]|trim");

            $this->form_validation->set_rules("email_template", "E-postmall för kallelser", "trim");



            // run validation

			if ($this->form_validation->run() == FALSE)

			{

				$this->error_message = validation_errors(' ', ' ');

			}

			else

			{

                // get post data

                $course_name = $this->input->post('course_name');

                $course_description = $this->input->post('course_description');



				$course_time_from = $this->input->post('course_time_from', TRUE) . ":00";
                $course_time_end = $this->input->post('course_time_end', TRUE) . ":00";



                $course_external_description = $this->input->post('course_external_description');

                $course_time = $this->input->post('course_time');

                $maximum_participants = $this->input->post('maximum_participants');

                $course_external_price = $this->input->post('course_external_price');

                $course_external_price_assemblin = $this->input->post('course_external_price_assemblin');

                $course_external_price_stena = $this->input->post('course_external_price_stena');

                $cert_template = $this->input->post('cert_template');

                $diploma_template = $this->input->post('diploma_template');

                $apipermissions = implode(',', $this->input->post('apipermissions[]'));

                $validity = $this->input->post('validity');

                $email_template = $this->input->post('email_template', false);



                // prepare data

                $prep_data = array(

                    'course_name' => $course_name,

                    'course_description' => $course_description,



										'course_time_from' => $course_time_from,

										'course_time_end' => $course_time_end,



                    'course_external_description' => $course_external_description,

                    'course_time' => $course_time,

                    'maximum_participants' => $maximum_participants,

                    'course_external_price' => $course_external_price,

                    'cert_template' => $cert_template,

                    'diploma_template' => $diploma_template,

                    'validity' => $validity,

                    'apipermissions' => $apipermissions,

                    'email_template' => $email_template,

                    'created_by' => $this->ion_auth->user()->row()->id,

                    'create_time' => date('Y-m-d H:i:s')

                );



                // prepare price data

                $price_data = array();



                // check for assemblin price

                if (!empty($course_external_price_assemblin))

                {

                    $price_data['price_assemblin'] = $course_external_price_assemblin;

                }



                // check for stena price

                if (!empty($course_external_price_stena))

                {

                    $price_data['price_stena'] = $course_external_price_stena;

                }



                // insert data and return

                $course_id = $this->course_model->save($prep_data);



				if($course_id != false)

				{

                    if (!empty($price_data))

                    {

                        $price_data['course_id'] = $course_id;

                        $course_price_id = $this->course_model->savePrice($price_data);

                    }



                    redirect('course');

                    exit;

				}

                else

                {

                    $this->error_message = 'Ett fel uppstod när utbildningen skulle sparas.';

                }

			}

        }



        // get the default email template

        $email_template = $this->settings_model->get_setting('default_mail_template');



        $page_styles = array(

            'select2' => 'assets/global/plugins/select2/css/select2.min.css',

            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',

            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'

        );



		$page_scripts = array(

            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',

            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',

            'moment' => 'assets/global/plugins/moment.min.js',

            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',

            'tinymce' => 'assets/global/plugins/tinymce/tinymce.min.js',

			'course_new' => 'assets/pages/scripts/src/new_course.js',

            'reports' => 'assets/pages/scripts/reports.min.js'

		);



		$view_data = array(

			'title_part1' => 'Ny Utbildning',

            'title_part2' => 'Svensk Uppdragsutbildning',

            'email_template' => $email_template,

            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),

            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),

            'report_cities' => $this->report_model->getCities(),

            'report_counties' => $this->report_model->getCounties(),

            'page_styles' => $page_styles,

			'page_scripts' => $page_scripts

		);



		$this->load->view('includes/headers/header', $view_data);

		$this->load->view('includes/nav_bar');

		$this->load->view('content/new_course');

		$this->load->view('includes/footers/footer');

	}



    /**

     * Edit an existing course

    */

	public function edit_course($id=null, $readonly = false){



        // check id

        if ($id == null)

        {

            redirect('course');

            exit;

        }

        else

        {

            if (!is_numeric($id))

            {

                redirect('course');

                exit;

            }

            else

            {

                $id = $this->security->xss_clean($id);

            }

        }



        // if posting

		if($this->input->post())

		{

            // set validation rules

			$this->form_validation->set_rules("course_name", "Namn", "required|max_length[50]|trim");

			$this->form_validation->set_rules("course_description", "Beskrivning", "required|max_length[255]|trim");

            $this->form_validation->set_rules("course_external_description", "Extern beskrivining", "trim");



            $this->form_validation->set_rules("course_time_from", "Utbildningsdatum (kursstart)", "required|max_length[30]|trim");

      			$this->form_validation->set_rules("course_time_end", "Utbildningsdatum (kursslut)", "required|max_length[30]|trim");



            $this->form_validation->set_rules("course_time", "Tid", "required|max_length[50]|trim");

            $this->form_validation->set_rules("maximum_participants", "Max antal deltagare", "required|max_length[10]|trim");

            $this->form_validation->set_rules("course_external_price", "Pris", "required|max_length[11]|numeric|trim");

            $this->form_validation->set_rules("course_external_price_assemblin", "Pris (Assemblin)", "max_length[11]|numeric|trim");

            $this->form_validation->set_rules("course_external_price_stena", "Pris (Stena)", "max_length[11]|numeric|trim");

            $this->form_validation->set_rules("cert_template", "Certifikat", "max_length[50]|trim");

            $this->form_validation->set_rules("diploma_template", "Intyg", "max_length[50]|trim");

            $this->form_validation->set_rules("validity", "Giltighetstid", "max_length[4]|trim");

            $this->form_validation->set_rules("apipermissions[]", "API Rättigheter", "required|max_length[50]|trim");

            $this->form_validation->set_rules("email_template", "E-postmall för kallelser", "trim");



            // perform validation

			if ($this->form_validation->run() == FALSE)

			{

				$this->error_message = validation_errors(' ', ' ');

			}

			else

			{

                // get post data

                $course_name = $this->input->post('course_name');

                $course_description = $this->input->post('course_description');

                $course_external_description = $this->input->post('course_external_description');

                $course_time_from = $this->input->post('course_time_from') . ":00";

                $course_time_end = $this->input->post('course_time_end') . ":00";

                $course_time = $this->input->post('course_time');

                $maximum_participants = $this->input->post('maximum_participants');

                $course_external_price = $this->input->post('course_external_price');

                $course_external_price_assemblin = $this->input->post('course_external_price_assemblin');

                $course_external_price_stena = $this->input->post('course_external_price_stena');

                $cert_template = $this->input->post('cert_template');

                $diploma_template = $this->input->post('diploma_template');

                $validity = $this->input->post('validity');

                $apipermissions = implode(',', $this->input->post('apipermissions[]'));

                $email_template = $this->input->post('email_template', false);



                // prepare data

                $prep_data = array(

                    'course_name' => $course_name,

                    'course_description' => $course_description,

                    'course_time_from' => $course_time_from,

                    'course_time_end' => $course_time_end,

                    'course_external_description' => $course_external_description,

                    'course_time' => $course_time,

                    'maximum_participants' => $maximum_participants,

                    'course_external_price' => $course_external_price,

                    'cert_template' => $cert_template,

                    'diploma_template' => $diploma_template,

                    'validity' => $validity,

                    'apipermissions' => $apipermissions,

                    'email_template' => $email_template,

                    'edited_by' => $this->ion_auth->user()->row()->id,

                    'edit_time' => date('Y-m-d H:i:s')

                );



                // prepare price data

                $price_data = array();



                // check for assemblin price

                if (!empty($course_external_price_assemblin))

                {

                    $price_data['price_assemblin'] = $course_external_price_assemblin;

                }



                // check for stena price

                if (!empty($course_external_price_stena))

                {

                    $price_data['price_stena'] = $course_external_price_stena;

                }



				if($this->course_model->update($prep_data, $id))

				{

                    if (!empty($price_data))

                    {

                        $price_exists = $this->course_model->priceExists($id);



                        if ($price_exists)

                        {

                            $this->course_model->updatePrice($id, $price_data);

                        }

                        else

                        {

                            $price_data['course_id'] = $id;

                            $this->course_model->savePrice($price_data);

                        }

                    }



					$this->success_message = 'Utbildningen har sparats.';

				}

                else

                {

                    $this->error_message = 'Ett fel uppstod när utbildningen skulle sparas.';

                }

			}

        }



        $page_styles = array(

            'select2' => 'assets/global/plugins/select2/css/select2.min.css',

            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',

            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'

        );



		$page_scripts = array(

            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',

            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',

            'moment' => 'assets/global/plugins/moment.min.js',

            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',

            'tinymce' => 'assets/global/plugins/tinymce/tinymce.min.js',

			'course_edit' => 'assets/pages/scripts/src/edit_course.js',

            'reports' => 'assets/pages/scripts/reports.min.js'

		);



        if ($readonly)

        {

            $title_part1 = 'Visa utbildning';

        }

        else

        {

            $title_part1 = 'Redigera utbildning';

        }



		$view_data = array(

			'title_part1' => $title_part1,

			'title_part2' => 'Svensk Uppdragsutbildning',

            'readonly' => $readonly,

            'success_message' => $this->success_message,

            'error_message' => $this->error_message,

            'course' => $this->course_model->get($id),

            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),

            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),

            'report_cities' => $this->report_model->getCities(),

            'report_counties' => $this->report_model->getCounties(),

            'page_styles' => $page_styles,

            'page_scripts' => $page_scripts

		);



		$this->load->view('includes/headers/header', $view_data);

		$this->load->view('includes/nav_bar');

		$this->load->view('content/edit_course');

		$this->load->view('includes/footers/footer');

	}



    /**

     * Delete a course

    */

	public function delete_course()

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



			if ($this->course_model->delete($id))

			{

                if ($this->course_model->deletePrice($id))

                {

                    $response = array(

                        'status' => 'success'

                    );

                }

                else

                {

                    $response = array(

                        'status' => 'error',

                        'message' => 'Utbildning har tagits bort men priser kopplade till utbildningen kunde inte tas bort.'

                    );

                }

			}

			else

			{

				$response = array(

					'status' => 'error',

					'message' => 'Ett fel uppstod när utbildningen skulle tas bort.'

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



    public function get_participants($course_id = null)

    {

        $formd = array(

            'id' => $course_id

        );



        $this->form_validation->set_data($formd);



        $this->form_validation->set_rules('id', 'ID', 'required|numeric');



		if ($this->form_validation->run() == FALSE)

		{

			$response = array(

				'status' => 'error',

				'message' => validation_errors(' ', ' ')

			);

		}

        else

        {

            $course_id = $this->security->xss_clean($course_id);



            $this->db->select('tbl_participant.id AS participant_id, tbl_participant.first_name, tbl_participant.last_name, tbl_participant.company_id,');

            $this->db->select('tbl_customer.company_name');

            $this->db->select('tbl_course_event.id AS event_id, tbl_course_event.course_code, tbl_course_event.course_date');

            $this->db->from('tbl_course_event');

            $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.course_event_id = tbl_course_event.id', 'left');

            $this->db->join('tbl_participant', 'tbl_participant.id = tbl_course_event_participants.participant_id', 'left');

            $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');

            $this->db->where('tbl_course_event.course_id', $course_id);

            $query = $this->db->get();

            $result = $query->result();



            $response = array(

                'status' => 'success',

                'message' => '',

                'data' => $result

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



	public function get_courses_ajax()

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

            1 => 'course_name',

            2 => 'course_description',

            3 => 'maximum_participants'

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

		$this->db->select('id, course_name, course_description, maximum_participants');

        $this->db->from('tbl_course');



        /* If searching, use CI3 like statements */

        if (!empty($search))

        {

            $this->db->where("(course_name LIKE '%$search%' OR course_description LIKE '%$search%' OR maximum_participants LIKE '%$search%')", null, true);

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

        $recordsTotal = $this->db->count_all('tbl_course');



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

