<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Filter Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Novastream AB
 * @link		http://www.novastream.se
 */
 
/**
  * CompanyID
  * Filters a array of objects by companyID
  * @param obj
  * @return bool
*/ 
if ( ! function_exists('companyID'))
{
    function companyID($obj) {
        static $idList = array();
        if(in_array($obj->company_id,$idList)) {
            return false;
        }
        $idList []= $obj->company_id;
        return true;
    }
}