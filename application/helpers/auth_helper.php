<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Auth Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Novastream AB
 * @link		http://www.novastream.se
 */
 
 if ( ! function_exists('auth'))
 {
	/**
	  */
	 function auth($super_admin = false, $admin = false, $teacher = false, $extended_teacher = false, $user = false)
	 {
         $auth = '';
         
         if ($super_admin != false)
         {
             $auth = 'super_admin';
         }
         
         if ($admin != false)
         {
             $auth = 'admin';
         }
         
         if ($teacher != false)
         {
             $auth = 'teacher';
         }
         
         if ($extended_teacher != false)
         {
             $auth = 'extended_teacher';
         }
         
         if ($user != false)
         {
             $auth = 'user';
         }
         
         return $auth;
	 }
 }