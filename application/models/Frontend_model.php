<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Check if course code is valid
     * @param string course_code
     * @return bool
    */
    public function is_course_code_valid($course_code)
    {
        $this->db->select('course_code');
        $this->db->where('course_code', $course_code);
        $query = $this->db->get('tbl_course_event');
        $num = $query->num_rows();
        if ($num != 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Get event details based on the course_code
     * @param string course_code
     * @return object
    */
    public function get_course_event_data($course_code)
    {
        $this->db->select("*");
        $this->db->from('tbl_course_event');
        $this->db->where('course_code', $course_code);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Get course detalis based on the course_id
     * @param int course_id
     * @return object
    */
    public function get_course_data($course_id)
    {
        $this->db->select("*");
        $this->db->from('tbl_course');
        $this->db->where('id', $course_id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Check if the participant is already regisitered
     * @param string personalnumber
     * @return mixed (string or bool)
    */
    public function get_participant_id($personalnumber)
    {
        $this->db->select("id");
        $this->db->from('tbl_participant');
        $this->db->where('personalnumber', $personalnumber);
		$query = $this->db->get();
        
        $num = $query->num_rows();
        if ($num != 1)
        {
            return false;
        }
        else
        {
            return $query->row()->id;
        }
    }
    
    /**
     * Check if participant is connected to this event
     * @param int event_id
     * @param int participant_id
     * @return bool
    */
    public function is_registered($event_id, $participant_id)
    {
        $this->db->select("tbl_course_event_participants.id, tbl_participant.company_id, tbl_participant.first_name, tbl_participant.last_name, tbl_participant.email, tbl_customer.company_name");
        $this->db->from('tbl_course_event_participants');
        $this->db->join('tbl_participant', 'tbl_participant.id = tbl_course_event_participants.participant_id', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        $query = $this->db->get();
        $num = $query->num_rows();
        
        if ($num != 1)
        {
            return false;
        }
        else
        {
            return $query->row();
        }
    }
    
    /**
     * Check if the company is registered
     * @param int event_id
     * @param int company_id
     * @return mixed (string / bool)
    */
    public function is_company_registered($event_id, $company_id)
    {
        $this->db->select("amount, sales_person, price");
        $this->db->from('tbl_course_event_ghosts');
        $this->db->where('course_event_id', $event_id);
        $this->db->where('customer_id', $company_id);
		$query = $this->db->get();
        
        $num = $query->num_rows();
        
        if ($num != 1)
        {
            return false;
        }
        else
        {
            return $query->row();
        }
    }
    
    /**
     * Change status of this participant so we know they where here
     * @param int event_id
     * @param int participant_id
     * @return bool
    */
    public function change_status($event_id, $participant_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('verified' => 1)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get participant company based on participant id
     * @param int participant_id
     * @return mixed (bool / string)
    */
    public function get_company_id($participant_id)
    {
        $this->db->select("company_id");
        $this->db->from('tbl_participant');
        $this->db->where('id', $participant_id);
		$query = $this->db->get();
        
        $num = $query->num_rows();
        if ($num != 1)
        {
            return false;
        }
        else
        {
            return $query->row()->company_id;
        }
    }
    
    /**
     * Add ghost company
     * @param int event_id
     * @param int company_id
     * @param int amount
     * @return bool
    */
    public function add_ghost_company($event_id, $company_id, $amount)
    {
        $insert_data = array(
            'course_event_id' => $event_id,
            'customer_id' => $company_id,
            'amount' => $amount
        );
        
        if ($this->db->insert('tbl_course_event_ghosts', $insert_data))
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
     * Add amount to ghost company
     * @param int event_id
     * @param int company_id
     * @param int new amount
     * @return bool
    */
    public function add_to_company($event_id, $company_id, $amount)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('customer_id', $company_id);
        
        if($this->db->update('tbl_course_event_ghosts', array('amount' => $amount)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Remove 1 from ghost companies
     * @param int event_id
     * @param int customer_id
     * @param int amount
     * @return bool
    */
    public function remove_one_from_company($event_id, $customer_id, $amount)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('customer_id', $customer_id);
        
        if($this->db->update('tbl_course_event_ghosts', array('amount' => $amount)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Remove ghost company if value is 1
     * @param int event_id
     * @param int customer_id
     * @return bool
    */
    public function remove_ghost_company($event_id, $customer_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('customer_id', $customer_id);
        
        if($this->db->delete('tbl_course_event_ghosts'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get all relevant companies for this event
     * @param int event_id
     * @return 
    */
    public function get_relevant_companies($event_id)
    {
        $this->db->select("customer_id");
        $this->db->from('tbl_course_event_ghosts');
        $this->db->where('course_event_id', $event_id);
		$query = $this->db->get();
        $results = $query->result();
        
        if (!empty($results))
        {
            $rc_data = array();
            foreach($results as $rc)
            {
                $rc_data[] = $rc->customer_id;
            }

            $this->db->select("id, company_name");
            $this->db->from('tbl_customer');
            $this->db->where_in('id', $rc_data);
            $query2 = $this->db->get();
            $results2 = $query2->result();
        }
        else
        {
            $results2 = $results;
        }
        
        return $results2;
    }
    
    /**
     * Connect participant to this event
     * @param int event_id
     * @param int participant_id
     * @return bool
    */
    public function connect_participant($event_id, $participant_id, $sales_person, $price)
    {
        if ($this->db->insert('tbl_course_event_participants', array('course_event_id' => $event_id, 'participant_id' => $participant_id, 'verified' => 1, 'sales_person' => $sales_person, 'price' => $price)))
        {
            return true;
        }
        else
        {
            return false;
        }
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
    
    /**
     * Add participant from the frontend
     * @param array data
     * @return mixed (int / bool)
    */
    public function add_participant($data)
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
     * Check if participant is registered as a participant
     * @param string personalnumber
     * @return bool
    */
    public function participant_exists($personalnumber)
    {
        $this->db->select("tbl_participant.id, tbl_participant.company_id, tbl_participant.first_name, tbl_participant.last_name, tbl_participant.phone, tbl_participant.email, tbl_customer.company_name");
        $this->db->from('tbl_participant');
        $this->db->join('tbl_customer', 'tbl_customer.id = tbl_participant.company_id', 'left');
        $this->db->where('tbl_participant.personalnumber', $personalnumber);
        $query = $this->db->get();
        $num = $query->num_rows();
        
        // Return single row
        if ($num >= 1)
        {
            return $query->row();
        }
        else
        {
            return false;
        }
    }
    
    public function company_exists($company_reg)
    {
        $this->db->select("tbl_customer.id, tbl_customer.company_name, tbl_customer.company_registration");
        $this->db->where("company_registration", $company_reg);
        $this->db->from("tbl_customer");
        $query = $this->db->get();
        $num = $query->num_rows();
        
        // Return single row
        if ($num >= 1)
        {
            return $query->row();
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Update a participant from the frontend
     * @param int participant_id
     * @param array data to be updated
     * @return bool
    */
    public function update_participant($participant_id, $update_data)
    {
        $this->db->where('id', $participant_id);
        
        if($this->db->update('tbl_participant', $update_data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Insert a new participant from the frontend
     * @param array
     * @return mixed
    */
    public function insert_participant($insert_data)
    {
        $this->db->insert('tbl_participant', $insert_data);
        
        $insert_id = $this->db->insert_id();
        
        if (isset($insert_id) && is_numeric($insert_id))
        {
            return $insert_id;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Insert a new customer
     * @param array
     * @return bool
    */
    public function insert_customer($insert_data)
    {
        $this->db->insert('tbl_customer', $insert_data);
        
        $insert_id = $this->db->insert_id();
        
        if (isset($insert_id) && is_numeric($insert_id))
        {
            return $insert_id;
        }
        else
        {
            return false;
        }
    }
}