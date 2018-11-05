<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get a list of counties based on the
     * counties that are in use.
     * @return object
    */
    public function getCounties()
    {
        $this->db->select('county');
        $this->db->distinct('county');
		$this->db->from('tbl_course_event');
		$this->db->order_by('county', 'ASC');
		return $this->db->get()->result_object();
    }

    /**
     * Get a list of cities based on the
     * cities that are in use.
     * @return object
    */
    public function getCities()
    {
        $this->db->select('city');
        $this->db->distinct('city');
		$this->db->from('tbl_course_event');
		$this->db->order_by('city', 'ASC');
		return $this->db->get()->result_object();
    }

    /**
     * Get all availible courses
     * @return array
     */
    public function getCourses()
    {
        $this->db->select('id, course_name');
        $this->db->from('tbl_course');        
        $this->db->order_by('course_name', 'ASC');
        return $this->db->get()->result();
    }
}