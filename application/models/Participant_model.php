<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Participant_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Insert a new participant
     * @param array
     * @return bool
    */
    public function insert($data)
    {
        if ($this->db->insert('tbl_participant', $data))
        {
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        else
        {
            return false;
        }
    }
    
     /**
     * Update a existing participant
     * @param int participant_id
     * @param array
     * @return bool
    */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('tbl_participant', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Delete a existing participant
     * @param int participant_id
     * @return bool
    */
    public function delete($id)
    {
        $this->db->where('id', $id);
        
        if($this->db->delete('tbl_participant'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get a specific participant
     * @param int id
     * @return object
    */
    public function get($id)
    {
        $this->db->select("*");
        $this->db->from('tbl_participant');
        $this->db->where('id', $id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Check if the participant already exists
    */
    public function exists($pn)
    {
        $this->db->select('id');
        $this->db->from('tbl_participant');
        $this->db->where('personalnumber', $pn);
        $query = $this->db->get();
        $count = $query->num_rows();
        
        if ($count === 0)
        {
            return false;
        }
        else
        {
            return $query->row();
        }
    }
    
    /**
     * Add a participant to an event
     * @param array
     * @return bool
    */
    public function connect_participant($data)
    {
        if ($this->db->insert('tbl_course_event_participants', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Delete all the connections to this user
     * @param int participant_id
     * @return bool
    */
    public function delete_from_events($id)
    {
        $this->db->where('participant_id', $id);
        
        if($this->db->delete('tbl_course_event_participants'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get all participants by company
     * @param company_id
     * @return object
    */
    public function get_by_company($id)
    {
        $this->db->select("*");
        $this->db->from('tbl_participant');
        $this->db->where('company_id', $id);
		$query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    /**
     * Get participants based on course_event_id
     * @param int course_event_id
     * @return object
    */
    public function get_by_event($event_id)
    {
        $this->db->select("tbl_participant.id, tbl_participant.personalnumber, CONCAT((tbl_participant.first_name),(' '),(tbl_participant.last_name)) as full_name, tbl_participant.email, tbl_customer.company_name, tbl_customer.id as company_id, tbl_course_event_participants.verified, tbl_course_event_participants.price, tbl_course_event_participants.sales_person, tbl_course_event_participants.mail_sent, tbl_course_event_participants.diploma_generated, tbl_course_event_participants.cert_generated");
        $this->db->from('tbl_participant');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id');
        $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.participant_id = tbl_participant.id', 'left');
        $this->db->where('tbl_course_event_participants.course_event_id', $event_id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    /**
     * Get participants events
     * @param int participant_id
     * @return bool
    */
    public function get_by_participant($id)
    {
        $this->db->select("tbl_course.id as course_id, tbl_course.course_name, tbl_course_event.course_code, tbl_course_event.course_date, tbl_course_event.id as course_event_id");
        $this->db->from('tbl_course_event');
        $this->db->join('tbl_course_event_participants', 'tbl_course_event_participants.course_event_id = tbl_course_event.id');
        $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id');
        $this->db->where('tbl_course_event_participants.participant_id', $id);
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
    
    /**
     * Search companies by search term
     * @param string term
     * @return object
    */
    public function search_companies($term)
    {
        $this->db->select("id, company_name");
        $this->db->from('tbl_customer');
        $this->db->like('company_name', $term);
        $this->db->limit(10);
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }
}