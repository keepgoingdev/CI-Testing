<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Insert course data in to the tbl_course table
     * @param arary
     * @return mixed
    */
    public function save($data){
        if ($this->db->insert('tbl_course', $data))
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Update course information
     * @param array update_data
     * @param int id
     * @return bool
    */
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('tbl_course', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Deletes a course
     * @param int id
     * @return bool
    */
    public function delete($id)
    {
        if($this->db->delete('tbl_course', array('id' => $id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get a specific course
     * @param int id
     * @return object
    */
    public function get($id){
        $this->db->select('tbl_course.*');
        $this->db->select('tbl_course_prices.price_assemblin, tbl_course_prices.price_stena');
        $this->db->join('tbl_course_prices', 'tbl_course_prices.course_id = tbl_course.id', 'left');
        $this->db->from('tbl_course');
        $this->db->where('tbl_course.id', $id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Get all courses
     * @return object
    */
    public function get_all()
    {
        $query = $this->db->get('tbl_course');
        return $query->result();
    }

    /**
     * Insert course price data in tbl_course_prices
     * @param array
     * @return mixed
    */
    public function savePrice($data)
    {
        if ($this->db->insert('tbl_course_prices', $data))
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;
        }
    }

    /**
     * Update course price information
     * @param array update_data
     * @param int id
     * @return bool
    */
    public function updatePrice($id, $data)
    {
        $this->db->where('course_id', $id);
        
        if($this->db->update('tbl_course_prices', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Check if price data exists
     * @param int course_id
     * @return bool
    */
    public function priceExists($id)
    {
        $this->db->select('tbl_course_prices.id');
        $this->db->from('tbl_course_prices');
        $this->db->where('tbl_course_prices.course_id', $id);
		$query = $this->db->get();
        $num_rows = $query->num_rows();
        
        if ($num_rows == 1)
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

     /**
     * Deletes a course price
     * @param int id
     * @return bool
    */
    public function deletePrice($id)
    {
        if($this->db->delete('tbl_course_prices', array('course_id' => $id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}