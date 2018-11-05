<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Script Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Novastream AB
 * @link		http://www.novastream.se
 */
 
 if ( ! function_exists('script_tag'))
 {
	/**
	  * Script Tag
	  * Adds <script src=""></script>
	  * Uses base_url to define the scripts path
	  * @param string
	  * @return string
	  */
	 function script_tag($src, $async = false, $charset = false, $defer = false, $type = false)
	 {
		 $script = '';
		 
		 if (isset($src) && !empty($src))
		 {
			$script = '<script src="'.base_url(''.$src.'').'';
			
			if ($async != false)
			{
				$script .= ' async';
			}
			
			if ($charset != false)
			{
				$script .= ' charset="'.$charset.'"';
			}
			
			if ($defer != false)
			{
				$script .= ' defer';
			}
			
			if ($type != false)
			{
				$script .= ' type="'.$type.'"';
			}

			$script .= '"></script>';
				
		 }
		 
		 return $script."\n";
	 }
 }