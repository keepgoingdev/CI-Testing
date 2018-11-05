<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
     /**
     * Checks to see if the user is a super admin     
     * @param int user_id
     * @return bool
    */
    public function is_super_admin($user_id)
    {
        $this->db->select('users.id, groups.name');
        $this->db->where('users.id', $user_id);
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id', 'left');
        $this->db->join('groups', 'groups.id = users_groups.group_id', 'left');
        $query = $this->db->get();
        $result = $query->row();
        $rowcount = $query->num_rows();
        
        if ($rowcount != 0)
        {
            $super_admin_group = $this->config->item('admin_group', 'ion_auth');
            
            if ($result->name != $super_admin_group)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Checks to see if the user is a regular admin     
     * @param int user_id
     * @return bool
    */
    public function is_regular_admin($user_id)
    {
        $this->db->select('users.id, groups.name');
        $this->db->where('users.id', $user_id);
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id', 'left');
        $this->db->join('groups', 'groups.id = users_groups.group_id', 'left');
        $query = $this->db->get();
        $result = $query->row();
        $rowcount = $query->num_rows();
        
        if ($rowcount != 0)
        {
            $regular_admin_group = $this->config->item('regular_admin_group', 'ion_auth');
            
            if ($result->name != $regular_admin_group)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
     /**
     * Checks to see if the user is a teacher     
     * @param int user_id
     * @return bool
    */
    public function is_teacher($user_id)
    {
        $this->db->select('users.id, groups.name');
        $this->db->where('users.id', $user_id);
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id', 'left');
        $this->db->join('groups', 'groups.id = users_groups.group_id', 'left');
        $query = $this->db->get();
        $result = $query->row();
        $rowcount = $query->num_rows();
        
        if ($rowcount != 0)
        {
            $teacher_group = $this->config->item('teacher_group', 'ion_auth');
            
            if ($result->name != $teacher_group)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
     /**
     * Checks to see if the user is a teacher with extended auth    
     * @param int user_id
     * @return bool
    */
    public function is_extended_teacher($user_id)
    {
        $this->db->select('users.id, groups.name');
        $this->db->where('users.id', $user_id);
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id', 'left');
        $this->db->join('groups', 'groups.id = users_groups.group_id', 'left');
        $query = $this->db->get();
        $result = $query->row();
        $rowcount = $query->num_rows();
        
        if ($rowcount != 0)
        {
            $extended_teacher_group = $this->config->item('extended_teacher_group', 'ion_auth');
            
            if ($result->name != $extended_teacher_group)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Checks to see if the user is a regular user  
     * @param int user_id
     * @return bool
    */
    public function is_user($user_id)
    {
        $this->db->select('users.id, groups.name');
        $this->db->where('users.id', $user_id);
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id', 'left');
        $this->db->join('groups', 'groups.id = users_groups.group_id', 'left');
        $query = $this->db->get();
        $result = $query->row();
        $rowcount = $query->num_rows();
        
        if ($rowcount != 0)
        {
            $user_group = $this->config->item('default_group', 'ion_auth');
            
            if ($result->name != $user_group)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
}