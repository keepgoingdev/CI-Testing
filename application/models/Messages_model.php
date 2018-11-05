<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();        
    }
    
    /**
     * Add a message
     * @param array data
     * @return bool
    */
    public function add_message($data)
    {
        if ($this->db->insert('tbl_messages', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Remove a message
     * @param int message_id
     * @return bool
    */
    public function remove_message($id)
    {
        $this->db->where('id', $id);
        
        if($this->db->delete('tbl_messages'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Remove all messages for this user
     * @param int user_id
     * @return bool
    */
    public function remove_all_messages($id)
    {
        $this->db->where('user_id', $id);
        
        if($this->db->delete('tbl_messages'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Change message status to read
     * @param int user_id
     * @return bool
    */
    public function message_read($id)
    {
        $this->db->where('user_id', $id);
        
        if($this->db->update('tbl_messages', array('read' => 1)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Get a single message
     * @param int message_id
     * @return object
    */
    public function get_message($id)
    {
        $this->db->select("*");
        $this->db->from('tbl_messages');
        $this->db->where('id', $id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Get all unread messages
     * @param int user_id
     * @return object
    */
    public function get_unread_messages($id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $id);
        $this->db->where('read', 0);
        $this->db->limit(10);
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get('tbl_messages');
        return $query->result();
    }
    
    public function count_all_messages($id)
    {
        $this->db->select('id');
        $this->db->where('user_id', $id);
        $this->db->where('read', 0);
        $this->db->order_by('date', 'DESC');
        $query = $this->db->count_all_results('tbl_messages');
        return $query;
    }
}

?>