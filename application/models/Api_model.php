<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();        
    }
    
    /**
     * Get all course events
     * @param string filter
     * @return object
    */
    public function get_course_events($filter = 'newer')
    {
        // Amount of ghosts
        $this->db->select('SUM(tbl_course_event_ghosts.amount) AS num_ghosts');
        $this->db->select('tbl_course_event_ghosts.course_event_id AS ghost_event_id');
        $this->db->from('tbl_course_event_ghosts');
        $this->db->group_by('ghost_event_id');
        $subquery = $this->db->get_compiled_select();
        $this->db->reset_query();
        
        // Amount of participnats
        $this->db->select('COUNT(DISTINCT tbl_course_event_participants.id) AS num_participants');
        $this->db->select('SUM(tbl_course_event_participants.price) AS sum_participants');
        $this->db->select('tbl_course_event_participants.course_event_id AS participant_event_id');
        $this->db->from('tbl_course_event_participants');
        $this->db->group_by('participant_event_id');
        $subquery2 = $this->db->get_compiled_select();
        $this->db->reset_query();
        
        $this->db->select('tbl_course_event.id, tbl_course.course_name, tbl_course_event.course_date, tbl_course_event.location, tbl_course_event.maximum_participants');
        $this->db->select('ghosts.num_ghosts');
        $this->db->select('participants.num_participants');
        $this->db->join('tbl_course', 'tbl_course.id = tbl_course_event.course_id', 'left');
        $this->db->join("($subquery) ghosts", 'ghosts.ghost_event_id = tbl_course_event.id', 'left');
        $this->db->join("($subquery2) participants", 'participants.participant_event_id = tbl_course_event.id', 'left');
        $this->db->from('tbl_course_event');
		$query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}
?>