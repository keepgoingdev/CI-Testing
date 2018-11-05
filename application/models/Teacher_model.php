<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Returns the default teacher group id
     * @param string group_name
     * @return int group_id
    */
    public function get_default_teacher_group($name)
    {
        $this->db->select('id');
        $this->db->where('name', $name);
        $this->db->from('groups');
        $query = $this->db->get();
        $result = $query->row();
        
        if (isset($result->id))
        {
            return $result->id;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Update teacher information
     * @param array update_data
     * @param int user_id
     * @return bool
    */
    public function update($update_data, $user_id)
    {
        $this->db->where('user_id', $user_id);
        
        if($this->db->update('tbl_teacher', $update_data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Update teacher information
     * @param array update_data
     * @param int id
     * @return bool
    */
    public function update_teacher($update_data, $id)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('tbl_teacher', $update_data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Deletes a teacher based on user_id
     * @param int user_id
     * @return bool
    */
    public function delete($user_id)
    {
        if($this->db->delete('tbl_teacher', array('user_id' => $user_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
     /**
     * Deletes a teacher based on id
     * @param int id
     * @return bool
    */
    public function delete_teacher($id)
    {
        if($this->db->delete('tbl_teacher', array('id' => $id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Delete all the connections to this teacher
     * @param int teacher_id
     * @return bool
    */
    public function delete_from_events($id)
    {
        $this->db->where('teacher_id', $id);
        
        if($this->db->delete('tbl_course_event_teachers'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Creates a new teacher
     * @param array data
     * @return bool
    */
    public function insert($data)
    {
		if ($this->db->insert('tbl_teacher', $data))
		{
			return true;
		}
		else 
		{
			return false;
		}
    }
    
    /**
     * Get a specific teacher
     * @param int id
     * @return object
    */
    public function get($id){
        $this->db->select("*");
        $this->db->from('tbl_teacher');
        $this->db->where('id', $id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Get all teachers
     * @return object
    */
    public function get_all()
    {
        $query = $this->db->get('tbl_teacher');
        return $query->result();
    }
}