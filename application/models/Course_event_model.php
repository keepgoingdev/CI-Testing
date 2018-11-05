<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_event_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Insert
     * @param array data
     * @return mixed (id on success and false on failure)
    */
    public function insert($data)
    {
        $this->db->insert('tbl_course_event', $data);
        $insert_id = $this->db->insert_id();
        
        if (!isset($insert_id) || !is_numeric($insert_id))
        {
            return false;
        }
        else
        {
            return $insert_id;
        }
    }
    
    /**
     * Get a specific course event
     * @param int id
     * @return object
    */
    public function get($id)
    {
        $this->db->select("*");
        $this->db->from('tbl_course_event');
        $this->db->where('id', $id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     *
     */
    public function code_exists($code)
    {
        $this->db->select('id');
        $this->db->from('tbl_course_event');
        $this->db->where('course_code', $code);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        
        if ($num_rows >= 1)
        {
            return false;
        }
        else 
        {
            return true;
        }
    }
    
    /**
     * Get all teachers connected to this course_event
     * @param int id
     * @return return
    */
    public function get_teacher($id)
    {
        $this->db->select("teacher_id");
        $this->db->from('tbl_course_event_teachers');
        $this->db->where('course_event_id', $id);
		$query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    /**
     * Update
     * @param
     * @return
    */
    public function update($update_data, $id)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('tbl_course_event', $update_data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Insert_teacher
     * @param array teachers
     * @param string event_id
     * @return
    */
    public function insert_teacher($teachers, $event_id)
    {
        foreach($teachers as $teacher)
        {
            $this->db->insert('tbl_course_event_teachers', array('course_event_id' => $event_id, 'teacher_id' => $teacher));
        }
        
        return true;
    }
    
    /**
     * Delete course event
     * @param int event_id
     * @return bool
    */
    public function delete($event_id)
    {
        if($this->db->delete('tbl_course_event', array('id' => $event_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Delete all customers connected to this course event
     * @param int event_id
     * @return bool
    */
    public function delete_customers($event_id)
    {
        if($this->db->delete('tbl_course_event_customer', array('course_event_id' => $event_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Delete all teachers within a specific event
     * @param int course_event_id
     * @return bool
    */
    public function delete_teachers($event_id)
    {
        if($this->db->delete('tbl_course_event_teachers', array('course_event_id' => $event_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function delete_participants($event_id)
    {
        if($this->db->delete('tbl_course_event_participants', array('course_event_id' => $event_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function delete_ghosts($event_id)
    {
        if($this->db->delete('tbl_course_event_ghosts', array('course_event_id' => $event_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Check if the participant is already registered to this event
     * @param int event_id
     * @param int participant_id
     * @return bool
    */
    public function is_participant_registered($event_id, $participant_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_course_event_participants');
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        $query = $this->db->get();
        $count = $query->num_rows();
        
        if ($count === 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Get maximum seats availible for this event
     * @param int event_id
     * @return int maximum_participants
    */
    public function seats_availible($event_id)
    {
        $this->db->select('maximum_participants');
        $this->db->from('tbl_course_event');
        $this->db->where('id', $event_id);
        $query = $this->db->get();
        $result = $query->row();
        return $result->maximum_participants;
    }
    
    /**
     * Gets seats taken for an event
     * @param int event_id
     * @return int
    */
    public function seats_taken($event_id)
    {
        $participants = 0;
        $ghosts = 0;
        $results = 0;
        
        $this->db->select('id');
        $this->db->from('tbl_course_event_participants');
        $this->db->where('course_event_id', $event_id);
        $participants = $this->db->count_all_results();
        
        $ghosts = $this->ghost_seats($event_id);
        
        $results = $participants + $ghosts;
        
        return $results;
    }
    
    /**
     * Returns the amount of ghosts seats taken for
     * this event.
     * @param int event_id
     * @return int ghost_seats
    */
    public function ghost_seats($event_id)
    {
        $this->db->select_sum('amount');
        $this->db->from('tbl_course_event_ghosts');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        $result = $query->row();
        return $result->amount;
    }
    
    /**
     * Adds all participants and ghost seats and checks
     * if there is any free slots for this event.
     * @param int event_id
     * @param int amount
     * @return bool
    */
    public function have_free_seats($event_id, $old_amount, $amount)
    {
        $this->db->select('id');
        $this->db->from('tbl_course_event_participants');
        $this->db->where('course_event_id', $event_id);
        $query = $this->db->get();
        
        $input1 = $query->num_rows();
        $input2 = $this->ghost_seats($event_id);
        $input3 = $old_amount;
        $input4 = $amount;
        
        $pre_total = $input1 + $input2;
        $pre_total = $pre_total - $input3;
        
        $total = $pre_total + $input4;
        $maximum = $this->seats_availible($event_id);
        
        if ($total > $maximum)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Does ghost already exists?
     * @param int course_event_id
     * @param int customer_id
     * @return mixed
    */
    public function ghost_exists($event_id, $customer_id)
    {
        $this->db->select("amount");
        $this->db->from('tbl_course_event_ghosts');
        $this->db->where('course_event_id', $event_id);
        $this->db->where('customer_id', $customer_id);
		$query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * Adds ghost seats to an specific event
     * @param array data
     * @return mixed bool int
    */
    public function add_ghost_seats($data, $contact_people)
    {
        if ($this->db->insert('tbl_course_event_ghosts', $data))
        {
            $insert_id = $this->db->insert_id();
            if(!is_null($contact_people) && isset($contact_people))
            {
                for($i = 0; $i < count($contact_people); $i++)
                {
                    $cdata = array(
                        'ghost_id' => $insert_id,
                        'contact_people_id' => $contact_people[$i]
                    );
                    $this->db->insert('tbl_course_event_ghosts_contact_people', $cdata);
                }
            }
        }
        else
        {
            return false;
        }
        return true;
    }
    
     /**
     * Update ghost seats to an specific event
     * @param int event_id
     * @param int customer_id
     * @param int amount
     * @return bool
    */
    public function update_ghost_seats($event_id, $company_id, $amount)
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
     * Checks if the teacher is allowed to teach this
     * course.
     * @param int course_id
     * @param array teachers
    */
    public function is_teacher_allowed($course_id, $teachers)
    {
        $where_clause = "FIND_IN_SET('".$course_id."', courses)";
            
        $this->db->select('id');
        $this->db->from('tbl_teacher');
        $this->db->where_in('id', $teachers);
        $this->db->where($where_clause);
        $query = $this->db->get();
        
        $number_allowed_teachers = $query->num_rows();
        $number_of_teachers = count($teachers);
        
        if ($number_allowed_teachers != $number_of_teachers)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Toggle verify status
     * @param int course_event_id
     * @param int participant_id
     * @param int verify 0 / 1
     * @return bool
    */
    public function verify($event_id, $participant_id, $verified)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('verified' => $verified)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
        
    /**
     * Toggle invoice status
     * @param int course_event_id
     * @param int participant_id
     * @param int invoice 0 / 1
     * @return bool
    */
    public function invoice($event_id, $participant_id, $invoice)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('invoice_sent' => $invoice)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Toggle invoice status for all participants
     * @param int course_event_id
     * @param int invoice 0 / 1
     * @return bool
    */
    public function invoiceAll($event_id, $invoice)
    {
        $this->db->where('course_event_id', $event_id);
        
        if($this->db->update('tbl_course_event_participants', array('invoice_sent' => $invoice)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Toggle diploma generated status
     * @param int course_event_id
     * @param int participant_id
     * @param int diploma_generated 0 / 1
     * @return bool
    */
    public function diploma($event_id, $participant_id, $diploma_generated)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('diploma_generated' => $diploma_generated)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Toggle certificate generated status
     * @param int course_event_id
     * @param int participant_id
     * @param int cert_generated 0 / 1
     * @return bool
    */
    public function cert($event_id, $participant_id, $cert_generated)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('cert_generated' => $cert_generated)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Toggle call status
     * @param int course_event_id
     * @param int participant_id
     * @param string mail_sent
     * @return bool
    */
    public function call_participant($event_id, $participant_id, $mail_sent)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('mail_sent' => $mail_sent)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
        
    /**
     * Toggle invoice status
     * @param int ghost_id
     * @param int invoice 0 / 1
     * @return bool
    */
    public function invoice_ghost($ghost_id, $invoice)
    {
        $this->db->where('id', $ghost_id);
        
        if($this->db->update('tbl_course_event_ghosts', array('invoice_sent' => $invoice)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Toggle call status
     * @param int ghost_id
     * @param string mail_sent
     * @return bool
    */
    public function call_ghost($ghost_id, $mail_sent)
    {
        $this->db->where('id', $ghost_id);
        
        if($this->db->update('tbl_course_event_ghosts', array('mail_sent' => $mail_sent)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Change price of a participant
     * @param int event_id
     * @param int participant_id
     * @param int price
    */
    public function update_pprice($event_id, $participant_id, $price)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('price' => $price)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Change price of a ghost
     * @param int ghost_id
     * @param int price
    */
    public function update_gprice($ghost_id, $price)
    {
        $this->db->where('id', $ghost_id);
        
        if($this->db->update('tbl_course_event_ghosts', array('price' => $price)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Change amount of a ghost
     * @param int ghost_id
     * @param int amount
    */
    public function update_gamount($ghost_id, $amount)
    {
        $this->db->where('id', $ghost_id);
        
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
     * Cancel participant
     * @param int event_id, participant_id
     * @return bool
    */
    public function cancel_participant($event_id, $participant_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if ($this->db->delete('tbl_course_event_participants'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Cancel ghost
     * @param int event_id, ghost
     * @return bool
    */
    public function cancel_ghost($ghost_id)
    {
        $this->db->where('id', $ghost_id);
        
        if ($this->db->delete('tbl_course_event_ghosts'))
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
    
    public function mail_sent($event_id, $participant_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('participant_id', $participant_id);
        
        if($this->db->update('tbl_course_event_participants', array('mail_sent' => date('Y-m-d'))))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function mail_sent_ghost($event_id, $ghost_id)
    {
        $this->db->where('course_event_id', $event_id);
        $this->db->where('id', $ghost_id);
        
        if($this->db->update('tbl_course_event_ghosts', array('mail_sent' => date('Y-m-d'))))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function change_event_date($event_id, $date_from, $date_to)
    {
        if($this->db->insert('tbl_course_event_dates', array('course_event_id' => $event_id, 'date_from' => $date_from, 'date_to' => $date_to)))
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }
    
    public function delete_event_dates($event_id)
    {
        if($this->db->delete('tbl_course_event_dates', array('course_event_id' => $event_id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function get_event_date($event_id)
    {
        $this->db->select("date_from, date_to");
        $this->db->from('tbl_course_event_dates');
        $this->db->where('course_event_id', $event_id);
		$query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}