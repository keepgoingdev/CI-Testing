<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert a new customer
     * @param array
     * @return bool
    */
    public function insert($data, $contact_people)
    {
        if ($this->db->insert('tbl_customer', $data))
        {
            $insert_id = $this->db->insert_id();

            if(!is_null($contact_people) && isset($contact_people))
                foreach ($contact_people as $contact_person) {
                    $data = array(
                        'name' => $contact_person->name,
                        'epost' => $contact_person->epost,
                        'phonenumber' => $contact_person->phonenumber,
                        'customer_id' => $insert_id
                    );
                    if(!$this->db->insert('tbl_contact_people', $data))
                        return false;
                }
        }
        else
        {
            return false;
        }
        return true;
    }
    
    /**
     * Update a existing customer
     * @param int customer_id
     * @param array
     * @return bool
    */
    public function update($id, $data, $contact_people)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('tbl_customer', $data))
        {
            //delete origin contacat_people data
            $this->db->delete('tbl_contact_people', array('customer_id' => $id));

            if(!is_null($contact_people) && isset($contact_people))
                foreach ($contact_people as $contact_person) 
                {
                    $contact_data = array(
                        'name' => $contact_person->name,
                        'epost' => $contact_person->epost,
                        'phonenumber' => $contact_person->phonenumber,
                        'customer_id' => $id
                    );
                    //if(((int)$contact_person->id) == -1)
                    //{
                        if(!$this->db->insert('tbl_contact_people', $contact_data))
                            return false;
                    //}
                    /*
                    else
                    {
                        $this->db->where('id', $contact_person->id);
                        if(!$this->db->update('tbl_contact_people', $contact_data))
                            return false;
                    }
                    */
                }
        }
        else
        {
            return false;
        }
        return true;
    }
    
    /**
     * Delete a existing customer
     * @param int customer_id
     * @return bool
    */
    public function delete($id)
    {
        $this->db->where('id', $id);
        
        if($this->db->delete('tbl_customer'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Delete all the connections to this customer
     * @param int customer_id
     * @return bool
    */
    public function delete_from_events($id)
    {
        $this->db->where('customer_id', $id);
        
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
     * Get a specific customer
     * @param int id
     * @return object
    */
    public function get($id)
    {
        $this->db->select("*");
        $this->db->from('tbl_customer');
        $this->db->where('id', $id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    

    /**
    * Get contact people
    * @param int id
    */
    public function get_contact_people($customer_id)
    {
        $this->db->select("*");
        $this->db->from('tbl_contact_people');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * Get all customers
     * @return object
    */
    public function get_all()
    {
        $query = $this->db->get('tbl_customer');
        return $query->result();
    }   

}