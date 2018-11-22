<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    /**
     * This controllers constructor
    */
    public function __construct()
    {
        parent::__construct();
    }   

    /**
     * Sms Remind Controller
     */
    public function sms_reminder()
    {
        //Get the datetime range
        $datetime = new DateTime('tomorrow');
        $tomorrow_start_date = $datetime->format('Y-m-d H:i:s');
        $datetime  = $datetime->modify("+1 day");
        $datetime  = $datetime->modify("-1 second");
        $tomorrow_end_date = $datetime->format('Y-m-d H:i:s');
        
        //Get Course Event Ids which start tomorrow
        $this->db->select('id');
        $this->db->where('course_date >=', $tomorrow_start_date);
        $this->db->where('course_date <=', $tomorrow_end_date);
        $this->db->from('tbl_course_event');
        $result = $this->db->get()->result_array();

        $course_event_ids = array();
        foreach( $result as $value )
        {
            $course_event_ids[] = $value['id'];
        }

        //Get already sent course_event_participants_ids
        $this->db->select('course_event_participant_id');
        $this->db->from('tbl_participants_sms_sent');
        $result = $this->db->get()->result_array();

        $course_event_participants_ids = array();
        foreach( $result as $value )
        {
            $course_event_participants_ids[] = $value['course_event_participant_id'];
        }

        //Get Participants
        $this->db->select('tcep.id, tcep.course_event_id, tcep.participant_id, tp.first_name, tp.last_name, tp.personalnumber, tce.course_id, tc.course_name');
        $this->db->join('tbl_participant as tp', 'tp.id = tcep.participant_id', 'left');
        $this->db->join('tbl_course_event as tce', 'tce.id = tcep.course_event_id', 'left');
        $this->db->join('tbl_course as tc', 'tc.id = (select course_id from tbl_course_event as tce2 where tce2.id = tce.id)', 'left');
        $this->db->where_in('tcep.course_event_id', $course_event_ids);
        $this->db->where_not_in('tcep.id', $course_event_participants_ids);
        $this->db->from('tbl_course_event_participants as tcep');
        $this->db->order_by('tcep.id');
        $result = $this->db->get()->result_array();
    }
}