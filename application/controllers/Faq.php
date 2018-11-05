<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

    /**
     * This controllers constructor
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth'));

        if (!$this->ion_auth->logged_in())
        { 
            redirect('auth/login');
            exit;
        }
    }

    /**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/faq
	 *	- or -
	 * 		http://example.com/faq/index
	 */
    public function index()
    {
        $view_data = array(
            'title_part1' => 'FAQ',
            'title_part2' => 'Svensk Uppdragsutbildning'            
        );

        $this->load->view('faq/faq', $view_data);
    }    
}