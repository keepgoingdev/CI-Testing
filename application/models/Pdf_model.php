<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();        
    }
    
    public function update_cert_status($event_id, $participant_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('cert_generated' => date('Y-m-d'))))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function update_diploma_status($event_id, $participant_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('diploma_generated' => date('Y-m-d'))))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get course event details needed for PDF generation
     * @param int event_id
     * @return object
    */
    public function get_course_event($event_id)
    {
        $this->db->select("user_id, course_id, course_date, course_date_end, location, city, zip, food, event_contact, living, course_material, send_material_to, freetext");
        $this->db->from('tbl_course_event');
        $this->db->where('id', $event_id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Get course details needed for PDF generation
     * @param int course_id
     * @return object
    */
    public function get_course($course_id)
    {
        $this->db->select("course_time, cert_template, diploma_template");
        $this->db->from('tbl_course');
        $this->db->where('id', $course_id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Get participant details needed for PDF generation
     * @param int participant_id
     * @return object
    */
    public function get_participant($participant_id)
    {
        $this->db->select("personalnumber, CONCAT(first_name,(' '), last_name) AS full_name");
        $this->db->from('tbl_participant');
        $this->db->where('id', $participant_id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    public function get_participant_event_details($event_id, $participant_id)
    {
        $this->db->select("diploma_generated, cert_generated");
        $this->db->from('tbl_course_event_participants');
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    public function get_by_event($event_id)
    {
        $this->db->select("tbl_participant.id, tbl_participant.personalnumber, tbl_participant.first_name, tbl_participant.last_name, tbl_participant.phone, tbl_participant.email, tbl_customer.company_name, tbl_customer.company_postal_address, tbl_customer.company_postal_zip, tbl_customer.company_postal_city, tbl_customer.contact_person, tbl_customer.company_email, tbl_customer.company_registration, tbl_course_event_participants.price, tbl_course_event_participants.sales_person, tbl_course_event_participants.mail_sent, tbl_course_event_participants.diploma_generated");
        $this->db->from('tbl_participant');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
        $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.participant_id = tbl_participant.id', 'left');
        $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
        $this->db->order_by('tbl_customer.company_name', 'ASC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function get_ghosts($event_id)
    {
        $this->db->select("tbl_course_event_ghosts.amount, tbl_course_event_ghosts.sales_person, tbl_course_event_ghosts.price, tbl_course_event_ghosts.mail_sent, tbl_customer.company_name, tbl_customer.company_postal_address, tbl_customer.company_postal_zip, tbl_customer.company_postal_city, tbl_customer.contact_person, tbl_customer.company_email, tbl_customer.company_registration");
        $this->db->from('tbl_course_event_ghosts');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_course_event_ghosts.customer_id', 'left');
        $this->db->where('tbl_course_event_ghosts.course_event_id', $event_id);
        $this->db->order_by('tbl_customer.company_name', 'ASC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function get_teachers($event_id)
    {
        $this->db->select("GROUP_CONCAT(CONCAT(tbl_teacher.first_name,(' '), tbl_teacher.last_name) SEPARATOR ',') AS teachers");
        $this->db->from('tbl_teacher');
        $this->db->join('tbl_course_event_teachers', 'tbl_course_event_teachers.teacher_id = tbl_teacher.id', 'left');
        $this->db->where('tbl_course_event_teachers.course_event_id', $event_id);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    public function count_participants($event_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_course_event_participants');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        $num_participants = $query->num_rows();
        
        $this->db->select_sum('amount');
        $this->db->from('tbl_course_event_ghosts');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        $result = $query->row();
        
        $total = $num_participants + $result->amount;
        return $total;
        
    }
    
    public function event_is_moved($event_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_course_event_dates');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        $num_dates = $query->num_rows();
        
        if ($num_dates >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function calculate_participant($event_id)
    {
        $this->db->select_sum('price');
        $this->db->from('tbl_course_event_participants');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        $result = $query->row();
        return $result->price;
    }
    
    public function calculate_ghost($event_id)
    {
        $this->db->select('SUM(price * amount) AS total');
        $this->db->from('tbl_course_event_ghosts');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        $result = $query->row();
        return $result->total;
    }
    
    public function get_contact_details($event_id)
    {
        $this->db->select('tbl_course_event_participants.participant_id');
        $this->db->select('tbl_participant.company_id');
        $this->db->select('tbl_customer.company_name, tbl_customer.company_postal_address, tbl_customer.company_postal_zip, tbl_customer.company_postal_city, tbl_customer.contact_person');
        $this->db->from('tbl_course_event_participants');
        $this->db->join('tbl_participant', 'tbl_participant.id = tbl_course_event_participants.participant_id', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
        $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
        //$this->db->distinct();
        //$this->db->group_by('tbl_customer.company_name');
        $query = $this->db->get();
        $result = $query->result();
        $result = array_filter($result,'companyID');

        return $result;
    }
    
    public function get_contact_detail($event_id, $participant_id)
    {
        $this->db->select('tbl_course_event_participants.participant_id');
        $this->db->select('tbl_participant.company_id');
        $this->db->select('tbl_customer.company_name, tbl_customer.company_postal_address, tbl_customer.company_postal_zip, tbl_customer.company_postal_city, tbl_customer.contact_person');
        $this->db->from('tbl_course_event_participants');
        $this->db->join('tbl_participant', 'tbl_participant.id = tbl_course_event_participants.participant_id', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
        $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
        $this->db->where('tbl_course_event_participants.participant_id', $participant_id);
        $this->db->limit(1);
        
        $query = $this->db->get();
        //$result = $query->result();
        return $query->row();
    }
}
?>