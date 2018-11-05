<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_event extends CI_Controller {

    public $success_message = null;
    public $error_message = null;
    public $auth = '';

    /**
     * This controllers constructor
    **/
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->model(array('course_event_model','course_model','teacher_model','participant_model','customer_model','messages_model','settings_model'));
        $this->load->helper(array('gravatar'));
        $this->config->load('static_mails');

        if (!$this->ion_auth->logged_in())
        {
            redirect(site_url('login'));
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
	 * 		http://example.com/course_events
	 *	- or -
	 * 		http://example.com/course_events/index
    **/
    public function index()
    {
        $query = $this->input->get('query', true);

        $page_styles = array(
            'select2' => 'assets/global/plugins/select2/css/select2.min.css',
            'select2_bootstrap' => 'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
            'datatables' => 'assets/global/plugins/datatables/datatables.min.css',
            'editable' => 'assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css'
        );

        $page_scripts = array(
            'bootbox' => 'assets/global/plugins/bootbox/bootbox.min.js',
            'select2' => 'assets/global/plugins/select2/js/select2.full.min.js',
            'select2lang' => 'assets/global/plugins/select2/js/i18n/sv.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'datatables' => 'assets/global/plugins/datatables/datatables.min.js',
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'editable' => 'assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js',
            'page_specific' => 'assets/pages/scripts/src/course_event_ajax.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );

        $view_data = array(
            'title_part1' => 'Utbildningstillfällen',
            'title_part2' => 'Svensk Uppdragsutbildning',
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'customers' => $this->customer_model->get_all(),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts,
            'query' => $query
        );

        $this->load->view('includes/headers/header', $view_data);
        $this->load->view('includes/nav_bar');
        $this->load->view('content/course_event');
        $this->load->view('includes/footers/footer');
    }

    /**
	 * New Course event Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/new_course_event
	 *	- or -
	 * 		http://example.com/new_course_event/index
    **/
    public function new_course_event()
    {
        // Teacher and extended teacher is not allowed to create events
        if ($this->auth == 'teacher')
        {
            redirect('dashboard');
            exit;
        }

        // Check for post
        if($this->input->post())
        {
            // Validation rules
            $this->form_validation->set_rules("course", "Utbildning", "required|numeric|trim");
            $this->form_validation->set_rules("teacher[]", "Utbildare", "required|trim");
            $this->form_validation->set_rules("customized", "Typ av utbildning", "required|numeric|trim");
            $this->form_validation->set_rules("course_date", "Utbildningsdatum (kursstart)", "required|max_length[30]|trim");
            $this->form_validation->set_rules("course_date_end", "Utbildningsdatum (kursslut)", "required|max_length[30]|trim");

            $this->form_validation->set_rules("course_time_from", "Utbildningsdatum (kursstart)", "required|max_length[30]|trim");
            $this->form_validation->set_rules("course_time_end", "Utbildningsdatum (kursslut)", "required|max_length[30]|trim");


            $this->form_validation->set_rules("location", "Företag & fullständig adress", "required|max_length[100]|trim");
            $this->form_validation->set_rules("zip", "Postnummer", "required|max_length[6]|trim");
            $this->form_validation->set_rules("city", "Ort", "required|max_length[100]|trim");
            $this->form_validation->set_rules("county", "Län", "max_length[50]|trim");
            $this->form_validation->set_rules("event_contact", "Kontaktperson + tel", "max_length[100]|trim");
            $this->form_validation->set_rules("living", "Boende utbildare", "max_length[255]|trim");
            $this->form_validation->set_rules("maximum_participants", "Max antal deltagare", "required|numeric|max_length[11]|trim");
            $this->form_validation->set_rules("food", "Lunch, fika", "max_length[255]|trim");
            $this->form_validation->set_rules("course_material", "Kursmaterial", "max_length[255]|trim");
            $this->form_validation->set_rules("send_material_to", "Adress att skicka material till", "max_length[100]|trim");
            $this->form_validation->set_rules("extern_teacher", "Extern utbildare", "max_length[1]|trim");

            // Run the validation
            if ($this->form_validation->run() == FALSE)
            {
                $this->error_message = validation_errors(' ', ' ');
            }
            else
            {
                // Save post and run xss clean
                $course_id = $this->input->post('course', TRUE);
                $teacher_id = $this->input->post('teacher[], TRUE');
                $customized = $this->input->post('customized', TRUE);
                $course_date = $this->input->post('course_date', TRUE) . " " . $this->input->post('course_time_from', TRUE);
                $course_date_end = $this->input->post('course_date_end', TRUE) . " " . $this->input->post('course_time_end', TRUE);
                $location = $this->input->post('location', TRUE);
                $zip = $this->input->post('zip', TRUE);
                $city = $this->input->post('city', TRUE);
                $county = $this->input->post('county', TRUE);
                $event_contact = $this->input->post('event_contact', TRUE);
                $living = $this->input->post('living', TRUE);
                $maximum_participants = $this->input->post('maximum_participants', TRUE);
                $food = $this->input->post('food', TRUE);
                $course_material = $this->input->post('course_material', TRUE);
                $send_material_to = $this->input->post('send_material_to', TRUE);
                $freetext = $this->input->post('freetext', TRUE);
                $extern_teacher = $this->input->post('extern_teacher', TRUE) ? 1 : 0;

                // Generate course code
                $course_code = '';
                $course_code_pt1 = date("Ymd", strtotime($course_date));
                $course_code_pt2 = $this->course_model->get($course_id)->course_name;
                $course_code_pt3 = mb_substr($course_code_pt2, 0, 4);
                $course_code_pt4 = mb_substr($city, 0, 4);
                $course_code_pt5 = $this->teacher_model->get($teacher_id[0]);
                $course_code_pt6 = mb_substr($course_code_pt5->first_name, 0, 1);
                $course_code_pt7 = mb_substr($course_code_pt5->last_name, 0, 1);
                $course_code_pt8 = $course_code_pt1.$course_code_pt3.$course_code_pt4.$course_code_pt6.$course_code_pt7;
                $course_code_pt9 = str_replace(array('å','ä','ö','Å','Ä','Ö'), array('a','a','o','a','a','o'), $course_code_pt8);
                $course_code_pt10 = strtoupper($course_code_pt9);
                $course_code_pt10 = str_replace(' ', '', $course_code_pt10);
                $course_code_pt10 = preg_replace('/\s+/', '', $course_code_pt10);
                $course_code_pt10 = preg_replace('~\x{00a0}~', '', $course_code_pt10);
                $code_counter = 1;
                $code_gen = true;

                while($code_gen)
                {
                    $course_code = $course_code_pt10.$code_counter;

                    if ($this->course_event_model->code_exists($course_code))
                    {
                        $code_gen = false;
                    }
                    else
                    {
                        $code_counter++;
                    }
                }

                if ($this->course_event_model->is_teacher_allowed($course_id, $teacher_id))
                {
                    $data = array(
                        'user_id' => $this->ion_auth->user()->row()->id,
                        'course_id' => $course_id,
                        'course_code' => $course_code,
                        'customized' => $customized,
                        'course_date' => $course_date,
                        'course_date_end' => $course_date_end,
                        'location' => $location,
                        'zip' => $zip,
                        'city' => $city,
                        'county' => $county,
                        'event_contact' => $event_contact,
                        'living' => $living,
                        'maximum_participants' => $maximum_participants,
                        'food' => $food,
                        'course_material' => $course_material,
                        'send_material_to' => $send_material_to,
                        'freetext' => $freetext,
                        'extern_teacher' => $extern_teacher,
                        'create_time' => date('Y-m-d H:i:s')
                    );

                    if($course_event_id = $this->course_event_model->insert($data))
                    {
                        if (!$this->course_event_model->insert_teacher($teacher_id, $course_event_id))
                        {
                            $this->error_message = 'Ett fel uppstod när de valda utbildarna skulle kopplas till detta event.';
                        }
                    }
                    else
                    {
                        $this->error_message = 'Ett fel uppstod när eventet skulle skapas.';
                    }

                    if ($this->error_message == null)
                    {
                        if (isset($_POST['notify_internal']))
                        {
                            $course_data = $this->course_model->get($course_id);

                            $emailconfig = array(
                                'protocol' => $this->config->item('protocol'),
                                'smtp_host' => $this->config->item('smtp_host'),
                                'smtp_port' => $this->config->item('smtp_port'),
                                'smtp_user' => $this->config->item('smtp_user'),
                                'smtp_pass' => $this->config->item('smtp_pass'),
                                'smtp_crypto' => $this->config->item('smtp_crypto'),
                                'smtp_timeout' => $this->config->item('smtp_timeout'),
                                'mailtype'  => $this->config->item('mailtype'),
                                'charset'   => $this->config->item('charset'),
                                'crlf' => $this->config->item('crlf'),
                                'newline' => $this->config->item('newline')
                            );

                            $this->email->initialize($emailconfig);

                            foreach ($teacher_id as $t)
                            {
                                $tt_data = $this->teacher_model->get($t);
                                $tt1 = $tt_data->first_name." ".$tt_data->last_name;
                                $tt2 = $tt_data->email;

                                $this->email->clear(TRUE);
                                $this->email->to($tt2);
                                $this->email->from($this->config->item('site_email'), $this->config->item('site_title'));
                                $this->email->subject("Nytt utbildningstillfälle");

                                $htmlmessage = '<p>Hej ';
                                $htmlmessage .= $tt1;
                                $htmlmessage .= '<br><br>';
                                $htmlmessage .= 'Du har blivit inbokad att hålla följande utbildning: '.$course_data->course_name.' <br><br>';
                                $htmlmessage .= '<strong>Kurskod:</strong> '.$course_code.'<br>';
                                $htmlmessage .= '<strong>Datum/Tid (kursstart):</strong> '.$course_date.'<br>';
                                $htmlmessage .= '<strong>Datum/Tid (kursslut):</strong> '.$course_date_end.'<br>';
                                $htmlmessage .= '<strong>Adress:</strong> '.$location.'<br>';
                                $htmlmessage .= '<strong>Postnummer:</strong> '.$zip.'<br>';
                                $htmlmessage .= '<strong>Kontakt:</strong> '.$event_contact.'<br><br>';
                                $htmlmessage .= '<br><br>';
                                $htmlmessage .= 'Med vänliga hälsningar';
                                $htmlmessage .= '<br>';
                                $htmlmessage .= $this->config->item('site_title');
                                $htmlmessage .= '<br>';
                                $htmlmessage .= 'Hemsida: <a href="http://svenskuppdragsutbildning.com">www.svenskuppdragsutbildning.com</a>';
                                $htmlmessage .= '<br>';
                                $htmlmessage .= 'E-post: ';
                                $htmlmessage .= $this->config->item('site_email');
                                $htmlmessage .= '<br>';
                                $htmlmessage .= 'Telefon: 0451 - 70 69 00</p>';

                                $this->email->message($htmlmessage);

                                try {
                                    $this->email->send();
                                }
                                catch(Exception $e) {
                                    // be quiet
                                }

                            }
                        }

                        redirect('course_event');
                        exit;
                    }
                }
                else
                {
                    $this->error_message = 'En eller flera utbildare har inte tillgång till denna utbildning.';
                }

                if ($this->error_message != null)
                {
                    $this->error_message = 'En eller flera utbildare har inte tillgång till denna utbildning.';
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
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'page_specific' => 'assets/pages/scripts/src/course_event_new_edit.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );

        $view_data = array(
            'title_part1' => 'Nytt utbildningstillfälle',
            'title_part2' => 'Svensk Uppdragsutbildning',
            'courses' => $this->course_model->get_all(),
            'teachers' => $this->teacher_model->get_all(),
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts,
            'gmaps' => true
        );

        $this->load->view('includes/headers/header', $view_data);
        $this->load->view('includes/nav_bar');
        $this->load->view('content/new_course_event');
        $this->load->view('includes/footers/footer');
    }

    /**
	 * Edit Course event Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/edit_course_event
	 *	- or -
	 * 		http://example.com/edit_course_event/index
    **/
    public function edit_course_event($id = null, $readonly = false)
    {
        // if user belongs to teacher group
        if ($this->auth == 'teacher')
        {
            redirect('dashboard');
            exit;
        }

        // if id is null
        if ($id == null)
        {
            redirect('course_event');
            exit;
        }
        else
        {
            if (!is_numeric($id))
            {
                redirect('course_event');
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
            // get booked participants for this event
            $seats_taken = $this->course_event_model->seats_taken($id);

            // form validation rules
            $this->form_validation->set_rules("course", "Utbildning", "required|numeric|trim");
            $this->form_validation->set_rules("teacher[]", "Utbildare", "required|trim");
            $this->form_validation->set_rules("customized", "Typ av utbildning", "required|numeric|trim");
            $this->form_validation->set_rules("course_date", "Utbildningsdatum (kursstart)", "required|max_length[30]|trim");
            $this->form_validation->set_rules("course_date_end", "Utbildningsdatum (kursslut)", "required|max_length[30]|trim");

            $this->form_validation->set_rules("course_time_from", "Utbildningsdatum (kursstart)", "required|max_length[30]|trim");
            $this->form_validation->set_rules("course_time_end", "Utbildningsdatum (kursslut)", "required|max_length[30]|trim");


            $this->form_validation->set_rules("location", "Företag & fullständig adress", "required|max_length[100]|trim");
            $this->form_validation->set_rules("zip", "Postnummer", "required|max_length[6]|trim");
            $this->form_validation->set_rules("city", "Ort", "required|max_length[100]|trim");
            $this->form_validation->set_rules("county", "Län", "max_length[50]|trim");
            $this->form_validation->set_rules("event_contact", "Kontaktperson + tel", "max_length[100]|trim");
            $this->form_validation->set_rules("living", "Boende utbildare", "max_length[255]|trim");

            if (isset($seats_taken) && !empty($seats_taken))
            {
                if (is_numeric($seats_taken) && $seats_taken != 0)
                {
                    $this->form_validation->set_rules("maximum_participants", "Max antal deltagare", "required|numeric|max_length[11]|greater_than_equal_to[$seats_taken]|trim");
                }
                else
                {
                    $this->form_validation->set_rules("maximum_participants", "Max antal deltagare", "required|numeric|max_length[11]|trim");
                }
            }
            else
            {
                $this->form_validation->set_rules("maximum_participants", "Max antal deltagare", "required|numeric|max_length[11]|trim");
            }

            $this->form_validation->set_rules("food", "Lunch, fika", "max_length[255]|trim");

            if ($this->input->post('food_booked'))
            {
                $this->form_validation->set_rules("food_booked", "Konferens/Lunch bokad", "max_length[4]|numeric|trim");
            }

            $this->form_validation->set_rules("course_material", "Kursmaterial", "max_length[255]|trim");
            $this->form_validation->set_rules("send_material_to", "Adress att skicka material till", "max_length[100]|trim");

            if ($this->input->post('material_sent'))
            {
                $this->form_validation->set_rules("material_sent", "Material skickat", "max_length[4]|numeric|trim");
            }

            $this->form_validation->set_rules("extern_teacher", "Extern utbildare", "max_length[1]|trim");

            if ($this->form_validation->run() == FALSE)
            {
                $this->error_message = validation_errors(' ', ' ');
            }
            else
            {
                $course_id = $this->input->post('course', TRUE);
                $teacher_id = $this->input->post('teacher[]', TRUE);
                $customized = $this->input->post('customized', TRUE);

                $course_date = $this->input->post('course_date', TRUE) . " " . $this->input->post('course_time_from', TRUE);
                $course_date_org = $this->input->post('course_date_org', TRUE);
                $course_date_end = $this->input->post('course_date_end', TRUE) . " " . $this->input->post('course_time_end', TRUE);

                $location = $this->input->post('location', TRUE);
                $zip = $this->input->post('zip', TRUE);
                $city = $this->input->post('city', TRUE);
                $county = $this->input->post('county', TRUE);
                $event_contact = $this->input->post('event_contact', TRUE);
                $living = $this->input->post('living', TRUE);
                $maximum_participants = $this->input->post('maximum_participants', TRUE);
                $food = $this->input->post('food', TRUE);

                if ($this->input->post('food_booked', TRUE))
                {
                    $food_booked = 1;
                }
                else
                {
                    $food_booked = 0;
                }

                $course_material = $this->input->post('course_material', TRUE);
                $send_material_to = $this->input->post('send_material_to', TRUE);

                if ($this->input->post('material_sent', TRUE))
                {
                    $material_sent = 1;
                }
                else
                {
                    $material_sent = 0;
                }

                $freetext = $this->input->post('freetext', TRUE);
                $extern_teacher = $this->input->post('extern_teacher', TRUE) ? 1 : 0;

                if ($course_date != $course_date_org)
                {
                    $this->course_event_model->change_event_date($id, $course_date_org, $course_date);
                }

                $data = array(
                    'course_id' => $course_id,
                    'customized' => $customized,
                    'course_date' => $course_date,
                    'course_date_end' => $course_date_end,
                    'location' => $location,
                    'zip' => $zip,
                    'city' => $city,
                    'county' => $county,
                    'event_contact' => $event_contact,
                    'living' => $living,
                    'maximum_participants' => $maximum_participants,
                    'food' => $food,
                    'food_booked' => $food_booked,
                    'course_material' => $course_material,
                    'send_material_to' => $send_material_to,
                    'material_sent' => $material_sent,
                    'freetext' => $freetext,
                    'extern_teacher' => $extern_teacher,
                    'edited_by' => $this->ion_auth->user()->row()->id,
                    'edit_time' => date('Y-m-d H:i:s')
                );

                if($this->course_event_model->update($data, $id))
                {
                    if ($this->course_event_model->delete_teachers($id))
                    {
                        if (!$this->course_event_model->insert_teacher($teacher_id, $id))
                        {
                            $this->error_message = 'Ett fel uppstod när de valda utbildarna skulle kopplas till detta event.';
                        }
                        else
                        {
                            $this->success_message = 'Utbildningstillfället har sparats.';
                        }
                    }
                    else
                    {
                        $this->error_message = 'Ett fel uppstod när de valda utbildarna skulle kopplas till detta event.';
                    }
                }
                else
                {
                    $this->error_message = 'Ett fel uppstod när eventet skulle uppdateras.';
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
            'jquery_input_mask' => 'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'moment' => 'assets/global/plugins/moment.min.js',
            'datetimepicker' => 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            'page_specific' => 'assets/pages/scripts/src/course_event_new_edit.js',
            'reports' => 'assets/pages/scripts/reports.min.js'
        );

        if ($readonly)
        {
            $title_part1 = 'Visa utbildningstillfälle';
        }
        else
        {
            $title_part1 = 'Redigera utbildningstillfälle';
        }

        $view_data = array(
            'title_part1' => $title_part1,
            'title_part2' => 'Svensk Uppdragsutbildning',
            'gmaps' => true,
            'readonly' => $readonly,
            'course_event' => $this->course_event_model->get($id),
            'courses' => $this->course_model->get_all(),
            'teachers' => $this->teacher_model->get_all(),
            'dates' => $this->course_event_model->get_event_date($id),
            'messages' => $this->messages_model->get_unread_messages($this->ion_auth->user()->row()->id),
            'messages_count' => $this->messages_model->count_all_messages($this->ion_auth->user()->row()->id),
            'report_cities' => $this->report_model->getCities(),
            'report_counties' => $this->report_model->getCounties(),
            'page_styles' => $page_styles,
            'page_scripts' => $page_scripts
        );

        $this->load->view('includes/headers/header', $view_data);
        $this->load->view('includes/nav_bar');
        $this->load->view('content/edit_course_event');
        $this->load->view('includes/footers/footer');
    }

    /**
     * Set all participant as invoice sent
     * @AJAX ONLY
    */
    public function set_invoice_status($event_id = null)
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
            $formd = array(
                'id' => $event_id
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);

                if (!$this->course_event_model->invoiceAll($event_id, 1))
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när alla deltagare skulle faktureras.'
                    );
                }
                else
                {

                    $response = array(
                        'status' => 'success',
                        'message' => ''
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
     * Cancel a course event
     * @AJAX ONLY
    */
    public function cancel_event($event_id = null, $canceled = null)
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
            $formd = array(
                'id' => $event_id,
                'canceled' => $canceled
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('canceled', 'Status', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $canceled = $this->security->xss_clean($canceled);

                if ($canceled == 1)
                {
                    $prep_data = array(
                        'canceled' => 0
                    );
                }
                if ($canceled == 0)
                {
                    $prep_data = array(
                        'canceled' => 1
                    );
                }

                if (!$this->course_event_model->update($prep_data, $event_id))
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när utbildningstillfället skulle ställas in.'
                    );
                }
                else
                {

                    $response = array(
                        'status' => 'success',
                        'message' => ''
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
     * Delete a course event
     * @AJAX ONLY
    */
    public function delete_event($event_id = null)
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
            $formd = array(
                'id' => $event_id
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);

                if (!$this->course_event_model->delete($event_id))
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när utbildningstillfället skulle tas bort.'
                    );
                }
                else
                {
                    if (!$this->course_event_model->delete_teachers($event_id))
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod när utbildare kopplade till utbildningstillfället skulle tas bort.'
                        );
                    }
                    else
                    {
                        if (!$this->course_event_model->delete_participants($event_id))
                        {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Ett fel uppstod när deltagare kopplade till utbildningstillfället skulle tas bort.'
                            );
                        }
                        else
                        {
                            if (!$this->course_event_model->delete_ghosts($event_id))
                            {
                                $response = array(
                                    'status' => 'error',
                                    'message' => 'Ett fel uppstod när deltagare kopplade till utbildningstillfället skulle tas bort.'
                                );
                            }
                            else
                            {
                                if (!$this->course_event_model->delete_event_dates($event_id))
                                {
                                    $response = array(
                                        'status' => 'error',
                                        'message' => 'Ett fel uppstod när datum kopplade till utbildningstillfället skulle tas bort.'
                                    );
                                }
                                else
                                {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => ''
                                    );
                                }
                            }
                        }
                    }
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
     * Check if a participant exists based on the participant personalnumber
     * @AJAX ONLY
    */
    public function check_participant($personalnumber = null, $foreign_ssn = null)
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
            $formd = array(
                'personalnumber' => $personalnumber,
                'foreign_ssn' => $foreign_ssn
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('personalnumber', 'Personnummer', 'required|max_length[50]|trim');
            $this->form_validation->set_rules('foreign_ssn', 'Deltagaren saknar svenskt personnummer', 'required|numeric|max_length[1]|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => form_validation(' ', ' ')
                );
            }
            else
            {
                $personalnumber = $this->security->xss_clean($personalnumber);
                $foreign_ssn = $this->security->xss_clean($foreign_ssn);
                $format_error = false;
                $trigger_words = array('Å', 'M', 'D', 'X');

                if ($foreign_ssn != 1)
                {
                    if (word_match($trigger_words, $personalnumber))
                    {
                        $format_error = true;
                    }
                }

                if ($format_error) {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Felaktigt personnummer. Personnummer ska anges i formatet: ÅÅMMDD-XXXX.'
                    );
                }
                else
                {
                    $participant = $this->participant_model->exists($personalnumber);

                    if ($participant != false)
                    {
                        $response = array(
                            'status' => 'success',
                            'message' => '',
                            'data' => $participant
                        );
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => '',
                            'data' => ''
                        );
                    }
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
     * Create a company (shortcut from customers.php)
     * @AJAX ONLY
    */
    public function add_company()
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
            $this->form_validation->set_rules("company_name", "Företag", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_reg", "Org.nr", "required|max_length[11]|trim");
            $this->form_validation->set_rules("company_postal_address", "Utdelningsadress", "required|max_length[100]|trim");
            $this->form_validation->set_rules("company_postal_zip", "Postnummer", "required|max_length[6]|trim");
            $this->form_validation->set_rules("company_postal_city", "Ort", "required|max_length[100]|trim");
            $this->form_validation->set_rules("contact_person", "Kontaktperson", "required|max_length[50]|trim");
            $this->form_validation->set_rules("company_email[]", "Epost", "valid_email|max_length[500]|trim");

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $company_name = $this->input->post('company_name', TRUE);
                $company_reg = $this->input->post('company_reg', TRUE);
                $company_postal_address = $this->input->post('company_postal_address', TRUE);
                $company_postal_zip = $this->input->post('company_postal_zip', TRUE);
                $company_postal_city = $this->input->post('company_postal_city', TRUE);
                $contact_person = ucname($this->input->post('contact_person', TRUE));
                $company_email = $this->input->post('company_email', TRUE);

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
                    'company_registration' => $company_reg,
                    'company_postal_address' => $company_postal_address,
                    'company_postal_zip' => $company_postal_zip,
                    'company_postal_city' => $company_postal_city,
                    'contact_person' => $contact_person,
                    'company_email' => $company_email,
                    'create_time' => date('Y-m-d H:i:s')
                );

                if ($this->customer_model->insert($prepdata))
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
                        'message' => 'Ett fel uppstod när företaget skulle sparas.'
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
     * Register and add a participant for this event
     * @AJAX ONLY
    */
    public function add_participant()
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
            $this->form_validation->set_rules("personalnumber", "Personnummer", "required|max_length[50]|is_unique[tbl_participant.personalnumber]|trim", array('is_unique' => 'En deltagare med detta personnummer finns redan registrerad i systemet.'));

            $this->form_validation->set_rules("sales_person", "Säljare", "required|max_length[255]|trim");
            $this->form_validation->set_rules("price", "Pris", "required|numeric|max_length[11]|trim");
            $this->form_validation->set_rules("first_name", "Förnamn", "required|max_length[50]|trim");
            $this->form_validation->set_rules("last_name", "Efternamn", "required|max_length[50]|trim");

            $this->form_validation->set_rules('company', 'Företag', 'required|numeric|max_length[11]|trim', array('required' => 'Fältet företag är obligatoriskt.', 'numeric' => 'Fältet företag är obligatoriskt.'));
            $this->form_validation->set_rules("email", "E-post", "valid_email|max_length[50]|trim");
            $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|max_length[11]|trim', array('required' => 'ID för detta event saknas. Försök igen.', 'numeric' => 'ID för detta event saknas. Försök igen.'));

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $personalnumber = $this->input->post('personalnumber');

                if ($this->input->post('foreign_ssn', true))
                {
                    $foreign_ssn = 1;
                }
                else
                {
                    $foreign_ssn = 0;
                }

                $sales_person = ucname($this->input->post('sales_person'));
                $price = $this->input->post('price');
                $first_name = ucname($this->input->post('first_name'));
                $last_name = ucname($this->input->post('last_name'));
                $company_id = $this->input->post('company');
                $phone = $this->input->post('phone');
                $email = $this->input->post('email');
                $event_id = $this->input->post('event_id');

                $prepdata = array(
                    'foreign_ssn' => $foreign_ssn,
                    'personalnumber' => $personalnumber,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'company_id' => $company_id,
                    'phone' => $phone,
                    'email' => $email,
                    'create_time' => date('Y-m-d H:i:s')
                );

                if ($this->course_event_model->have_free_seats($event_id, 0, 1))
                {
                    $participant_id = $this->participant_model->insert($prepdata);

                    if ($participant_id != false)
                    {
                        $prepdata2 = array(
                            'course_event_id' => $event_id,
                            'participant_id' => $participant_id,
                            'sales_person' => $sales_person,
                            'price' => $price,
                        );

                        if ($this->participant_model->connect_participant($prepdata2))
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
                                'message' => 'Deltagaren har sparats men kunde inte kopplas till denna utbildning.'
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Ett fel uppstod när deltagaren skulle sparas.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Det finns inte tillräckligt med lediga platser. Är utbildningen fullbokad?'
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
     * Add a already registered participant for this event
     * @AJAX ONLY
    */
    public function add_participant_short()
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
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim', array('required' => 'ID för deltagaren saknas. Försök igen.', 'numeric' => 'ID för deltagaren saknas. Försök igen.'));
            $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|trim', array('required' => 'ID för detta event saknas. Försök igen..', 'numeric' => 'ID för detta event saknas. Försök igen.'));
            $this->form_validation->set_rules("sales_person", "Säljare", "required|trim");
            $this->form_validation->set_rules("price", "Pris", "required|numeric|trim");

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $participant_id = $this->input->post('participant_id');
                $event_id = $this->input->post('event_id');
                $sales_person = ucname($this->input->post('sales_person'));
                $price = $this->input->post('price');

                $prepdata = array(
                    'course_event_id' => $event_id,
                    'participant_id' => $participant_id,
                    'sales_person' => $sales_person,
                    'price' => $price
                );

                // Is there enough free seats?
                if ($this->course_event_model->have_free_seats($event_id, 0, 1))
                {
                    if ($this->course_event_model->is_participant_registered($event_id, $participant_id))
                    {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Deltagaren är redan registrerad till denna utbildning.'
                        );
                    }
                    else
                    {
                        if ($this->participant_model->connect_participant($prepdata))
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
                                'message' => 'Deltagaren kunde inte kopplas till denna utbildning.'
                            );
                        }
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Det finns inte tillräckligt med lediga platser. Är utbildningen fullbokad?'
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
     * Add ghosts to a event
     * @AJAX ONLY
    */
    public function add_ghosts()
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
            $this->form_validation->set_rules('company_id', 'Företag', 'required|numeric|trim', array('required' => 'Fältet företag är obligatoriskt.', 'numeric' => 'Fältet företag är obligatoriskt.'));
            $this->form_validation->set_rules("amount", "Antal deltagare", "required|numeric|trim");
            $this->form_validation->set_rules("sales_person", "Säljare", "required|trim");
            $this->form_validation->set_rules("price", "Pris", "required|numeric|trim");
            $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|trim', array('required' => 'ID för detta event saknas. Försök igen.', 'numeric' => 'ID för detta event saknas. Försök igen.'));

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $company_id = $this->input->post('company_id');
                $amount = $this->input->post('amount');
                $sales_person = ucname($this->input->post('sales_person'));
                $price = $this->input->post('price');
                $event_id = $this->input->post('event_id');
                $contact_people = json_decode($this->input->post('contact_people'));

                // Is there enough free seats?
                if ($this->course_event_model->have_free_seats($event_id, 0, $amount))
                {
                    $old_amount = $this->course_event_model->ghost_exists($event_id, $company_id);

                    if (!isset($old_amount->amount) || empty($old_amount->amount))
                    {
                        $prepdata = array(
                            'course_event_id' => $event_id,
                            'customer_id' => $company_id,
                            'amount' => $amount,
                            'sales_person' => $sales_person,
                            'price' => $price
                        );

                        if ($this->course_event_model->add_ghost_seats($prepdata, $contact_people))
                        {
                            $response = array(
                                'status' => 'success',
                                'message' => ''
                            );
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error2',
                                'message' => 'Ett fel uppstod när deltagarna skulle kopplas till utbildningen.'
                            );
                            $response = $contact_people;
                        }
                    }
                    else
                    {
                        $new_amount = $amount + $old_amount->amount;

                        if ($this->course_event_model->update_ghost_seats($event_id, $company_id, $new_amount))
                        {
                            $response = array(
                                'status' => 'success1',
                                'message' => ''
                            );
                        }
                        else
                        {
                            $response = array(
                                'status' => 'error2',
                                'message' => 'Ett fel uppstod när deltagarna skulle kopplas till utbildningen.'
                            );
                        }
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Det finns inte tillräckligt med lediga platser. Är utbildningen fullbokad?'
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
     * Get the amount of participant
     * @AJAX ONLY
    */
    public function get_amount_participants()
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
            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $course_id = $this->input->post('id');

                if ($course_id != -1)
                {
                    $mp = $this->course_model->get($course_id);
                    $mp = $mp->maximum_participants;

                    $response = array(
                        'status' => 'success',
                        'message' => '',
                        'data' => $mp
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => '',
                        'data' => ''
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
     * Get all participant seats for a specific a event
     * @AJAX ONLY
    */
    public function get_participants($event_id = null)
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
            $formd = array(
                'id' => $event_id
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);

                $this->db->select("tbl_participant.id, tbl_participant.personalnumber, CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) as full_name, tbl_participant.email");
                $this->db->select('tbl_customer.company_name, tbl_customer.id as company_id');
                $this->db->select('tbl_course_event_participants.verified, tbl_course_event_participants.price, tbl_course_event_participants.sales_person, tbl_course_event_participants.mail_sent, tbl_course_event_participants.diploma_generated, tbl_course_event_participants.cert_generated, tbl_course_event_participants.invoice_sent');
                $this->db->from('tbl_participant');
                $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
                $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.participant_id = tbl_participant.id', 'left');
                $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
                $this->db->order_by('tbl_customer.company_name', 'ASC');
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
    }

    /**
     * Get all ghost seats for a specific a event
     * @AJAX ONLY
    */
    public function get_ghosts($event_id = null)
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
            $formd = array(
                'id' => $event_id
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ', ' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);

                $this->db->select('tbl_course_event_ghosts.id, tbl_course_event_ghosts.customer_id, tbl_course_event_ghosts.amount, tbl_course_event_ghosts.sales_person, tbl_course_event_ghosts.price, tbl_course_event_ghosts.mail_sent, tbl_course_event_ghosts.invoice_sent, count(tcegcp.id) as contact_people_count');
                $this->db->select('tbl_customer.company_name, tbl_customer.company_email, tbl_customer.company_phone');
                $this->db->from('tbl_course_event_ghosts');
                $this->db->join('tbl_customer', 'tbl_customer.id = tbl_course_event_ghosts.customer_id', 'left');

                $this->db->join('tbl_course_event_ghosts_contact_people as tcegcp', 'tbl_course_event_ghosts.id = tcegcp.ghost_id', 'left');
                $this->db->group_by('tbl_course_event_ghosts.id');

                $this->db->where('tbl_course_event_ghosts.course_event_id', $event_id);
                $this->db->order_by('tbl_customer.company_name', 'ASC');
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
    }

    /**
     * Change verify status of a participant
     * @AJAX ONLY
    */
    public function verify_participant($event_id = null, $participant_id = null, $verified = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id,
                'verified' => $verified
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('verified', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);
                $verified = $this->security->xss_clean($verified);

                if ($verified == 1)
                {
                    $ve = 0;
                }
                if ($verified == 0)
                {
                    $ve = 1;
                }

                if ($this->course_event_model->verify($event_id, $participant_id, $ve))
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
                        'message' => 'Ett fel uppstod när deltagaren skulle verifieras'
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
     * Change the invoice status of a participant for a specific event
     * @AJAX ONLY
    */
    public function invoice_participant($event_id = null, $participant_id = null, $invoice = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id,
                'invoice' => $invoice
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('invoice', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);
                $invoice = $this->security->xss_clean($invoice);

                if ($invoice == 1)
                {
                    $ie = 0;
                }
                if ($invoice == 0)
                {
                    $ie = 1;
                }

                if ($this->course_event_model->invoice($event_id, $participant_id, $ie))
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
                        'message' => 'Ett fel uppstod när faktura status skulle ändras.'
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
     * Change the diploma generated status for an participant
     * @AJAX ONLY
    */
    public function diploma_generated($event_id = null, $participant_id = null, $diploma_generated = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id,
                'diploma_generated' => $diploma_generated
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|max_length[11]|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|max_length[11]|trim');
            $this->form_validation->set_rules('diploma_generated', 'Datum när intyg genererades', 'required|max_length[10]|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);
                $diploma_generated = $this->security->xss_clean($diploma_generated);

                if ($diploma_generated != 0)
                {
                    $dg = 0;
                }
                else
                {
                    $dg = date('Y-m-d');
                }

                if ($this->course_event_model->diploma($event_id, $participant_id, $dg))
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
                        'message' => 'Ett fel uppstod när status för intyg skulle ändras.'
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
     * Change the certificate generated status for an participant
     * @AJAX ONLY
    */
    public function cert_generated($event_id = null, $participant_id = null, $cert_generated = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id,
                'cert_generated' => $cert_generated
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|max_length[11]|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|max_length[11]|trim');
            $this->form_validation->set_rules('cert_generated', 'Datum när certifikat genererades', 'required|max_length[10]|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);
                $cert_generated = $this->security->xss_clean($cert_generated);

                if ($cert_generated != 0)
                {
                    $cg = 0;
                }
                else
                {
                    $cg = date('Y-m-d');
                }

                if ($this->course_event_model->cert($event_id, $participant_id, $cg))
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
                        'message' => 'Ett fel uppstod när status för intyg skulle ändras.'
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
     * Change the certificate / diploma generated status
     * @AJAX ONLY
    */
    public function change_certdip_status($event_id = null, $status = null)
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
            $formd = array(
                'id' => $event_id,
                'status' => $status
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $status = $this->security->xss_clean($status);

                if ($this->course_event_model->update(array('certdip_sent' => $status), $event_id))
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
                        'message' => 'Ett fel uppstod när status för Certifikat / Intyg skulle ändras.'
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
     * Change the mails sent status
     * @AJAX ONLY
    */
    public function change_mailssent_status($event_id = null, $status = null)
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
            $formd = array(
                'id' => $event_id,
                'status' => $status
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $status = $this->security->xss_clean($status);

                if ($this->course_event_model->update(array('mails_sent' => $status), $event_id))
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
                        'message' => 'Ett fel uppstod när status för Certifikat / Intyg skulle ändras.'
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
     * Change the call status of a participant for a specific event
     * @AJAX ONLY
    */
    public function call_participant($event_id = null, $participant_id = null, $mail_sent = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id,
                'mail_sent' => $mail_sent
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('mail_sent', 'Status', 'required|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);
                $mail_sent = $this->security->xss_clean($mail_sent);

                $ie = '-1';

                if ($mail_sent == -1)
                {
                    $ie = date('Y-m-d');
                }
                else
                {
                    $ie = '-1';
                }

                if ($this->course_event_model->call_participant($event_id, $participant_id, $ie))
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
                        'message' => 'Ett fel uppstod när status för kallelsen skulle ändras.'
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
     * Change the invoice status of a ghost user for a specific event
     * @AJAX ONLY
    */
    public function invoice_ghost($ghost_id = null, $invoice = null)
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
            $formd = array(
                'ghost_id' => $ghost_id,
                'invoice' => $invoice
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('ghost_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('invoice', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $ghost_id = $this->security->xss_clean($ghost_id);
                $invoice = $this->security->xss_clean($invoice);

                if ($invoice == 1)
                {
                    $ie = 0;
                }
                if ($invoice == 0)
                {
                    $ie = 1;
                }

                if ($this->course_event_model->invoice_ghost($ghost_id, $ie))
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
                        'message' => 'Ett fel uppstod när faktura status skulle ändras.'
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
     * Change the call status of a ghost user for a specific event
     * @AJAX ONLY
    */
    public function call_ghost($ghost_id = null, $mail_sent = null)
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
            $formd = array(
                'ghost_id' => $ghost_id,
                'mail_sent' => $mail_sent
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('ghost_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('mail_sent', 'Status', 'required|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $ghost_id = $this->security->xss_clean($ghost_id);
                $mail_sent = $this->security->xss_clean($mail_sent);

                $ie = '-1';

                if ($mail_sent == -1)
                {
                    $ie = date('Y-m-d');
                }
                else
                {
                    $ie = '-1';
                }

                if ($this->course_event_model->call_ghost($ghost_id, $ie))
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
                        'message' => 'Ett fel uppstod när status för kallelsen skulle ändras.'
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
     * Change the price of a participant seat for a specific event
     * @AJAX ONLY
    */
    public function update_pprice($event_id = null, $participant_id = null, $price = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id,
                'price' => $price
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('price', 'Pris', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);
                $price = $this->security->xss_clean($price);

                if ($this->course_event_model->update_pprice($event_id, $participant_id, $price))
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
                        'message' => 'Ett fel uppstod när priset skulle ändras.'
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
     * Change the price of a ghost seat for a specific event
     * @AJAX ONLY
    */
    public function update_gprice($ghost_id = null, $price = null)
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
            $formd = array(
                'ghost_id' => $ghost_id,
                'price' => $price
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('ghost_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('price', 'Pris', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $ghost_id = $this->security->xss_clean($ghost_id);
                $price = $this->security->xss_clean($price);

                if ($this->course_event_model->update_gprice($ghost_id, $price))
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
                        'message' => 'Ett fel uppstod när priset skulle ändras.'
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
     * Change the amount of ghost seats for a specific event
     * @AJAX ONLY
    */
    public function update_gamount($event_id = null, $ghost_id = null, $old_amount = null, $amount = null)
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
            $formd = array(
                'id' => $event_id,
                'ghost_id' => $ghost_id,
                'old_amount' => $old_amount,
                'amount' => $amount
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('ghost_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('old_amount', 'Antal', 'required|numeric|trim');
            $this->form_validation->set_rules('amount', 'Antal', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $ghost_id = $this->security->xss_clean($ghost_id);
                $amount = $this->security->xss_clean($amount);

                if ($this->course_event_model->have_free_seats($event_id, $old_amount, $amount))
                {
                    if ($this->course_event_model->update_gamount($ghost_id, $amount))
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
                            'message' => 'Ett fel uppstod när antalet skulle ändras.'
                        );
                    }
                }
                else
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Det finns inte tillräckligt med lediga platser. Är utbildningen fullbokad?'
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
     * Update a ghosts email address
     * @AJAX ONLY
     */
    public function update_ghost_email()
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
            $this->form_validation->set_rules('ghost_id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('company_email[]', 'E-post', 'valid_email|max_length[500]|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $ghost_id = $this->input->post('ghost_id', true);
                $company_email = $this->input->post('company_email', true);

                if (!empty($company_email))
                {
                    $company_email = implode(",", $company_email);
                }

                $prep_data = array(
                    'company_email' => $company_email
                );

                if ($this->customer_model->update($ghost_id, $prep_data))
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
                        'message' => 'Ett fel uppstod när e-postadressen skulle uppdteras.'
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
     * Remove a participant seat from a course event
     * @AJAX ONLY
    */
    public function cancel_participant($event_id = null, $participant_id = null)
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
            $formd = array(
                'id' => $event_id,
                'participant_id' => $participant_id
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');
            $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $event_id = $this->security->xss_clean($event_id);
                $participant_id = $this->security->xss_clean($participant_id);

                if (!$this->course_event_model->cancel_participant($event_id, $participant_id))
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när deltagaren skulle avbokas.'
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'success',
                        'message' => ''
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
     * Remove a ghost seat from a course event
     * @AJAX ONLY
    */
    public function cancel_ghost($ghost_id = null)
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
            $formd = array(
                'id' => $ghost_id,
            );

            $this->form_validation->set_data($formd);

            $this->form_validation->set_rules('id', 'ID', 'required|numeric|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors(' ',' ')
                );
            }
            else
            {
                $ghost_id = $this->security->xss_clean($ghost_id);

                if (!$this->course_event_model->cancel_ghost($ghost_id))
                {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Ett fel uppstod när företaget skulle avbokas.'
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'success',
                        'message' => ''
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
     * Send email to all participant and companies inside
     * a specific event
    */
    public function send_multiple_email($event_id = null)
    {
        // Store the event id in an array
        $formd = array(
            'event_id' => $event_id
        );

        // Create $_POST data
        $this->form_validation->set_data($formd);

        // Set validation rules
        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|trim');

        // Run validation
        if ($this->form_validation->run() == FALSE)
        {
            print_r(form_validation(' ', ' '));
        }
        else
        {
            // Run xss_clean to make sure
            $event_id = $this->security->xss_clean($event_id);

            // Get information about the participants
            $this->db->select("tbl_participant.id, CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) as full_name, tbl_participant.email");
            $this->db->select("tbl_customer.company_email");
            $this->db->from('tbl_participant');
            $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
            $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.participant_id = tbl_participant.id', 'left');
            $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
            $query = $this->db->get();
            $result = $query->result();

            
            //Get ghosts_id from course event
            $this->db->select("id");
            $this->db->from("tbl_course_event_ghosts");
            $this->db->where("course_event_id", $event_id);
            $result_ghost_ids = $this->db->get()->result();
            $ghost_ids = array();
            foreach ($result_ghost_ids as $value) {
                $ghost_ids[] = $value->id;
            }

            // Get information about the companies aka ghosts
            if(isset($ghost_ids) && count($ghost_ids) > 0 && !empty($ghost_ids))
            {
                $this->db->select('tbl_course_event_ghosts_contact_people.id');
                $this->db->select('tbl_contact_people.name AS full_name, tbl_contact_people.epost AS email');
                $this->db->from('tbl_course_event_ghosts_contact_people');
                $this->db->join('tbl_contact_people', 'tbl_contact_people.id = tbl_course_event_ghosts_contact_people.contact_people_id', 'left');
                $this->db->where_in('tbl_course_event_ghosts_contact_people.ghost_id', $ghost_ids);
                $query2 = $this->db->get();
                $result2 = $query2->result();
            }
            else
                $result2 = array();

            // Get information about the course event
            $this->db->select('tbl_course_event.course_date, tbl_course_event.course_date_end, tbl_course_event.location, tbl_course_event.zip');
            $this->db->select('tbl_course.course_name, tbl_course.course_time, tbl_course.email_template');
            $this->db->select("GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name,(' '), tbl_teacher.last_name) SEPARATOR ',') AS teachers");
            $this->db->select("GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.email) SEPARATOR ',') AS teachers_email");
            $this->db->from('tbl_course_event');
            $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
            $this->db->join('tbl_course_event_teachers', 'tbl_course_event_teachers.course_event_id = tbl_course_event.id', 'left');
            $this->db->join('tbl_teacher', 'tbl_teacher.id = tbl_course_event_teachers.teacher_id', 'left');
            $this->db->where('tbl_course_event.id', $event_id);
            $query3 = $this->db->get();
            $result3 = $query3->row();

            // Init an empty array
            $email_addresses = array();
            $teachers_addresses = $result3->teachers_email;

            // Add static email addresses
            $static_addresses = $this->config->item('static_mails');

            // Push teachers email addresses to the static_addresses array
            array_push($static_addresses, $teachers_addresses);

            // Merge static addresses with email addresses
            $email_addresses = array_merge($email_addresses, $static_addresses);

            // Start adding participants
            foreach ($result as $r1)
            {
                // Check for company address
                if (!empty($r1->company_email))
                {
                    // Create an array
                    $em1 = explode(",", $r1->company_email);

                    // Merge arrays
                    $email_addresses = array_merge($email_addresses, $em1);
                }

                // Check for participants address
                if (!empty($r1->email))
                {
                    // Add participant address to array
                    array_push($email_addresses, $r1->email);

                    // Change mail status to sent
                    $this->course_event_model->mail_sent($event_id, $r1->id);
                }
                else
                {
                    $msgs = 'E-post saknas för '.$r1->full_name;
                    $this->messages_model->add_message(array('user_id' => $this->ion_auth->user()->row()->id, 'icon' => 'fa fa-envelope-o', 'title' => 'Det gick inte skicka ett meddelande.', 'message' => $msgs, 'date' => date('Y-m-d H:i:s'), 'read' => 0));
                }
            }

            // Start adding companies aka ghosts
            foreach ($result2 as $r2)
            {
                // Check for company address aka ghost address
                if (!empty($r2->email))
                {
                    // Create an array
                    $em2 = explode(",", $r2->email);

                    // Merge arrays
                    $email_addresses = array_merge($email_addresses, $em2);

                    // Change mail status to sent
                    $this->course_event_model->mail_sent_ghost($event_id, $r2->id);
                }
                else
                {
                    $msgs = 'E-post saknas för '.$r2->full_name;
                    $this->messages_model->add_message(array('user_id' => $this->ion_auth->user()->row()->id, 'icon' => 'fa fa-envelope-o', 'title' => 'Det gick inte skicka ett meddelande.', 'message' => $msgs, 'date' => date('Y-m-d H:i:s'), 'read' => 0));
                }
            }

            // Remove dupes and create a comma sperated list
            $email_addresses = array_filter($email_addresses);
            $email_addresses = array_unique($email_addresses);
            $email_addresses = implode(";", $email_addresses);

            // Start buildning the email template
            $subject = "Kallelse - Utbildningsdatum: $result3->course_date";

            // init a empty html variable
            $html = '';

            // get default email template
            if (!empty($result3->email_template))
            {
                $html = $result3->email_template;
            }
            else
            {
                $html = $this->settings_model->get_setting('default_mail_template');
            }

            // replace shortcodes
            $html = str_replace("{location}",$result3->location,$html);
            $html = str_replace("{course_name}",$result3->course_name,$html);
            $html = str_replace("{course_date}",$result3->course_date,$html);
            $html = str_replace("{course_date_end}",$result3->course_date_end,$html);
            $html = str_replace("{teachers}",$result3->teachers,$html);

            // use double quotes
            $html = str_replace('"','""',$html);

            // remove linebreaks
            $html = str_replace(array("\r","\n"),"",$html);

            // utf8 decode
            $html = utf8_decode($html);

            // Get system email from CI3 config file
            $to_email = $this->config->item('site_email');

            // Create *.vbs file to be imported in Outlook
            $emlFile = "Set objoutlookApp = CreateObject(\"Outlook.Application\")\r\n";
            $emlFile .= "Set objmessage = objoutlookApp.CreateItem(olMailItem)\r\n";
            $emlFile .= "objmessage.TO = \"$to_email\"\r\n";
            $emlFile .= "objmessage.CC = \"\"\r\n";
            $emlFile .= "objmessage.BCC = \"$email_addresses\"\r\n";
            $emlFile .= "objmessage.Subject = \"$subject\"\r\n";
            $emlFile .= "objmessage.HTMLBody = \"$html\"\r\n";
            $emlFile .= "objmessage.display\r\n";
            $emlFile .= "set objmessage = Nothing\r\n";
            $emlFile .= "set objoutlookApp = Nothing\r\n";
            $emlFile .= "wscript.quit";

            // Create a filename for the *.vbs file
            $filename = $result3->course_name." ".$result3->course_date.".vbs";
            $filename = str_replace(" ", "_", $filename);

            // Update mails_sent before downloading the file since headers will be thrown
            $this->course_event_model->update(array('mails_sent' => 1), $event_id);

            // Set the correct headers and force download
            force_download($filename, $emlFile);
        }
    }

    /**
     * Send a single email to a participant
    */
    public function send_single_email($event_id = null, $participant_id = null)
    {
        $formd = array(
            'event_id' => $event_id,
            'participant_id' => $participant_id
        );

        $this->form_validation->set_data($formd);

        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|trim');
        $this->form_validation->set_rules('participant_id', 'ID', 'required|numeric|trim');

        if ($this->form_validation->run() == FALSE)
        {
            print_r(form_validation(' ', ' '));
        }
        else
        {
            $event_id = $this->security->xss_clean($event_id);

            $this->db->select("tbl_participant.id, CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) as full_name, tbl_participant.email");
            $this->db->select("tbl_customer.company_email");
            $this->db->select('tbl_course_event.course_date, tbl_course_event.course_date_end, tbl_course_event.location, tbl_course_event.zip');
            $this->db->select('tbl_course.course_name, tbl_course.course_time, tbl_course.email_template');
            $this->db->select("GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name,(' '), tbl_teacher.last_name) SEPARATOR ',') AS teachers");
            $this->db->select("GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.email) SEPARATOR ',') AS teachers_email");
            $this->db->from('tbl_participant');
            $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.participant_id = tbl_participant.id', 'left');
            $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
            $this->db->join('tbl_course_event', 'tbl_course_event.id = tbl_course_event_participants.course_event_id', 'left');
            $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
            $this->db->join('tbl_course_event_teachers', 'tbl_course_event_teachers.course_event_id = tbl_course_event.id', 'left');
            $this->db->join('tbl_teacher', 'tbl_teacher.id = tbl_course_event_teachers.teacher_id', 'left');
            $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
            $this->db->where('tbl_course_event_participants.participant_id', $participant_id);
            $query = $this->db->get();
            $r = $query->row();

            // Does this participant have an email
            if (!empty($r->email))
            {
                // Company email addresses to array
                $email_addresses = explode(",", $r->company_email);
                $teachers_addresses = $r->teachers_email;
                $static_addresses = array();

                // If no value is found, create an empty array
                if (!is_array($email_addresses))
                {
                    $email_addresses = array();
                }

                // Push static addresses to the array
                $static_addresses = $this->config->item('static_mails');

                // Push teachers email addresses to the static_addresses array
                array_push($static_addresses, $teachers_addresses);

                // Remove dupes and create a comma sperated list
                $email_addresses = array_filter($email_addresses);
                $email_addresses = array_unique($email_addresses);
                $email_addresses = implode(";", $email_addresses);
                $static_addresses = implode(";", $static_addresses);

                $subject = "Kallelse - Utbildningsdatum: $r->course_date";

                // init a empty html variable
                $html = '';

                // get default email template
                if (!empty($r->email_template))
                {
                    $html = $r->email_template;
                }
                else
                {
                    $html = $this->settings_model->get_setting('default_mail_template');
                }

                // replace shortcodes
                $html = str_replace("{location}",$r->location,$html);
                $html = str_replace("{course_name}",$r->course_name,$html);
                $html = str_replace("{course_date}",$r->course_date,$html);
                $html = str_replace("{course_date_end}",$r->course_date_end,$html);
                $html = str_replace("{teachers}",$r->teachers,$html);

                // use double quotes
                $html = str_replace('"','""',$html);

                // remove linebreaks
                $html = str_replace(array("\r","\n"),"",$html);

                // utf8 decode
                $html = utf8_decode($html);

                $emlFile = "Set objoutlookApp = CreateObject(\"Outlook.Application\")\r\n";
                $emlFile .= "Set objmessage = objoutlookApp.CreateItem(olMailItem)\r\n";
                $emlFile .= "objmessage.TO = \"$email_addresses\"\r\n";
                $emlFile .= "objmessage.CC = \"\"\r\n";
                $emlFile .= "objmessage.BCC = \"$static_addresses\"\r\n";
                $emlFile .= "objmessage.Subject = \"$subject\"\r\n";
                $emlFile .= "objmessage.HTMLBody = \"$html\"\r\n";
                $emlFile .= "objmessage.display\r\n";
                $emlFile .= "set objmessage = Nothing\r\n";
                $emlFile .= "set objoutlookApp = Nothing\r\n";
                $emlFile .= "wscript.quit";

                $filename = $r->course_name." ".$r->full_name." ".$r->course_date.".vbs";
                $filename = str_replace(" ", "_", $filename);

                $this->course_event_model->mail_sent($event_id, $r->id);
                force_download($filename, $emlFile);
            }
            else
            {
                $msgs = 'E-post saknas för '.$r->full_name;
                echo $msgs;
                $this->messages_model->add_message(array('user_id' => $this->ion_auth->user()->row()->id, 'icon' => 'fa fa-envelope-o', 'title' => 'Det gick inte skicka ett meddelande.', 'message' => $msgs, 'date' => date('Y-m-d H:i:s'), 'read' => 0));
            }
        }
    }

    /**
     * Send email to a single company
     */
    public function send_ghost_email($event_id = null, $company_id = null,  $ghost_id = null)
    {
        $formd = array(
            'event_id'      => $event_id,
            'company_id'    => $company_id,
            'ghost_id'      => $ghost_id
        );

        $this->form_validation->set_data($formd);

        $this->form_validation->set_rules('event_id', 'ID', 'required|numeric|trim');
        $this->form_validation->set_rules('company_id', 'ID', 'required|numeric|trim');
        $this->form_validation->set_rules('ghost_id', 'ID', 'required|numeric|trim');

        if ($this->form_validation->run() == FALSE)
        {
            print_r(form_validation(' ', ' '));
        }
        else
        {
            $event_id = $this->security->xss_clean($event_id);
            $company_id = $this->security->xss_clean($company_id);
            $ghost_id = $this->security->xss_clean($ghost_id);

            $this->db->select('tbl_course_event_ghosts.id');
            $this->db->select('tbl_customer.company_name AS full_name, tbl_customer.company_email AS email');
            $this->db->select('tbl_course_event.course_date, tbl_course_event.course_date_end, tbl_course_event.location, tbl_course_event.zip');
            $this->db->select('tbl_course.course_name, tbl_course.course_time, tbl_course.email_template');
            $this->db->select("GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name,(' '), tbl_teacher.last_name) SEPARATOR ',') AS teachers");
            $this->db->select("GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.email) SEPARATOR ',') AS teachers_email");
            $this->db->from('tbl_course_event_ghosts');
            $this->db->join('tbl_customer', 'tbl_customer.id = tbl_course_event_ghosts.customer_id', 'left');
            $this->db->join('tbl_course_event', 'tbl_course_event.id = tbl_course_event_ghosts.course_event_id', 'left');
            $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
            $this->db->join('tbl_course_event_teachers', 'tbl_course_event_teachers.course_event_id = tbl_course_event.id', 'left');
            $this->db->join('tbl_teacher', 'tbl_teacher.id = tbl_course_event_teachers.teacher_id', 'left');
            $this->db->where('tbl_course_event_ghosts.course_event_id', $event_id);
            $this->db->where('tbl_course_event_ghosts.customer_id', $company_id);
            $this->db->group_by('tbl_course_event_ghosts.id');
            $query = $this->db->get();
            $r = $query->row();

            //ghosts contact people email
            $this->db->select('tcegcp.id, tcp.epost as email');
            $this->db->from('tbl_course_event_ghosts_contact_people as tcegcp');
            $this->db->where('tcegcp.ghost_id', $ghost_id);
            $this->db->join('tbl_contact_people as tcp', 'tcegcp.contact_people_id = tcp.id');
            $result_ghost_email = $this->db->get()->result();

            $ghosts_contact_emails = array();
            foreach ($result_ghost_email as $value) {
                $ghosts_contact_emails[] = $value->email;
            }

            // Does this company aka ghost have an email
            if (!empty($r->email))
            {
                // Create a empty array
                $email_addresses = array();
                $teachers_addresses = $r->teachers_email;
                $static_addresses = array();

                
                // Add static email addresses
                $static_addresses = $this->config->item('static_mails');

                // Push teachers email addresses to the static_addresses array
                array_push($static_addresses, $teachers_addresses);

                // Explode companies aka ghosts addresses
                //$em = explode(",", $r->email);

                //Get aka ghosts emails
                $em = $ghosts_contact_emails;

                // Merge with original array
                $email_addresses = array_merge($email_addresses, $em);

                // Remove dupes and create a comma sperated list
                $email_addresses = array_filter($email_addresses);
                $email_addresses = array_unique($email_addresses);
                $email_addresses = implode(";", $email_addresses);
                $static_addresses = implode(";", $static_addresses);

                $subject = "Kallelse - Utbildningsdatum: $r->course_date";

                // init a empty html variable
                $html = '';

                // get default email template
                if (!empty($r->email_template))
                {
                    $html = $r->email_template;
                }
                else
                {
                    $html = $this->settings_model->get_setting('default_mail_template');
                }

                // replace shortcodes
                $html = str_replace("{location}",$r->location,$html);
                $html = str_replace("{course_name}",$r->course_name,$html);
                $html = str_replace("{course_date}",$r->course_date,$html);
                $html = str_replace("{course_date_end}",$r->course_date_end,$html);
                $html = str_replace("{teachers}",$r->teachers,$html);

                // use double quotes
                $html = str_replace('"','""',$html);

                // remove linebreaks
                $html = str_replace(array("\r","\n"),"",$html);

                // utf8 decode
                $html = utf8_decode($html);

                $emlFile = "Set objoutlookApp = CreateObject(\"Outlook.Application\")\r\n";
                $emlFile .= "Set objmessage = objoutlookApp.CreateItem(olMailItem)\r\n";
                $emlFile .= "objmessage.TO = \"$email_addresses\"\r\n";
                $emlFile .= "objmessage.CC = \"\"\r\n";
                $emlFile .= "objmessage.BCC = \"$static_addresses\"\r\n";
                $emlFile .= "objmessage.Subject = \"$subject\"\r\n";
                $emlFile .= "objmessage.HTMLBody = \"$html\"\r\n";
                $emlFile .= "objmessage.display\r\n";
                $emlFile .= "set objmessage = Nothing\r\n";
                $emlFile .= "set objoutlookApp = Nothing\r\n";
                $emlFile .= "wscript.quit";

                $filename = $r->course_name." ".$r->full_name." ".$r->course_date.".vbs";
                $filename = str_replace(" ", "_", $filename);

                $this->course_event_model->mail_sent_ghost($event_id, $r->id);
                force_download($filename, $emlFile);
            }
            else
            {
                $msgs = 'E-post saknas för '.$r->full_name;
                echo $msgs;
                $this->messages_model->add_message(array('user_id' => $this->ion_auth->user()->row()->id, 'icon' => 'fa fa-envelope-o', 'title' => 'Det gick inte skicka ett meddelande.', 'message' => $msgs, 'date' => date('Y-m-d H:i:s'), 'read' => 0));
            }
        }
    }

    /**
     * Return companies as JSON to be
     * used with Select2
     * @AJAX ONLY
    */
    public function search_companies()
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
            $term = $this->input->get('term', true);

            $response = $this->course_event_model->search_companies($term);

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
     * Return course event information
     * as JSON to be used with DataTables
     * @AJAX ONLY
    */
    public function get_course_event_ajax()
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
                0 => 'tbl_course_event.id',
                1 => 'tbl_course_event.course_code',
                2 => 'tbl_course.course_name',
                3 => 'teachers',
                4 => 'tbl_course_event.location',
                5 => 'tbl_course_event.city',
                6 => 'tbl_course_event.course_date'
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
            $this->db->select('tbl_course_event.id, tbl_course_event.course_code, tbl_course_event.customized, tbl_course_event.canceled, tbl_course_event.course_date, tbl_course_event.location, tbl_course_event.city, tbl_course_event.maximum_participants, tbl_course_event.mails_sent, tbl_course_event.certdip_sent');
            $this->db->select('tbl_course.course_name');
            $this->db->select("GROUP_CONCAT(tbl_teacher.user_id) teachers_ids, GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name,(' '), tbl_teacher.last_name) SEPARATOR ',') AS teachers");

            $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
            $this->db->join('tbl_course_event_teachers', 'tbl_course_event_teachers.course_event_id = tbl_course_event.id', 'left');
            $this->db->join('tbl_teacher', 'tbl_teacher.id = tbl_course_event_teachers.teacher_id', 'left');

            $this->db->from('tbl_course_event');
            $this->db->group_by(array("tbl_course_event.id"));

            /* If searching, use CI3 like statements */
            if (!empty($search))
            {
                $this->db->where("CONCAT((tbl_course_event.course_code), (tbl_course_event.location), (tbl_course_event.city), (tbl_course.course_name), (tbl_teacher.first_name), (' '), (tbl_teacher.last_name), (tbl_course_event.course_date)) LIKE '%$search%'", null, true);
                /*$this->db->where("(tbl_course_event.course_code LIKE '%$search%' OR tbl_course_event.location LIKE '%$search%' OR tbl_course_event.city LIKE '%$search%' OR tbl_course_event.course_date LIKE '%$search%' OR tbl_course.course_name LIKE '%$search%' OR CONCAT((tbl_teacher.first_name),(' '),(tbl_teacher.last_name)) LIKE '%$search%')", null, true);*/

            }

            /* Use custom order only if order_column isset and not empty */
            if (!empty($order_column))
            {
                $this->db->order_by($table_fields[$order_column], $order_dir);
            }
            else
            {
                $this->db->order_by('tbl_course_event.id', $order_dir);
            }

            /* Count filtered result if searching */
            if (!empty($search) || isset($tid))
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
            $recordsTotal = $this->db->count_all_results('tbl_course_event');

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

    /**
     * Get all contact people for a specific a company
     * @AJAX ONLY
    */
    public function get_contactpeople_by_customer_id($customer_id)
    {

        $this->db->select("*");
        $this->db->from('tbl_contact_people');
        $this->db->where('customer_id', $customer_id);

        $results = $this->db->get()->result_array();

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

    /**
     * Get all contact people for a specific a ghost id
     * @AJAX ONLY
    */
    public function get_contactpeople_by_ghost_id($ghost_id)
    {

        $this->db->select("tcegcp.id as id, tcp.id as contact_people_id, tcp.name as name, tcp.epost as epost, tcp.phonenumber as phonenumber");
        $this->db->from('tbl_course_event_ghosts_contact_people as tcegcp');
        $this->db->join('tbl_contact_people as tcp', 'tcp.id = tcegcp.contact_people_id', 'left');
        $this->db->where('ghost_id', $ghost_id);

        $results = $this->db->get()->result_array();

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
