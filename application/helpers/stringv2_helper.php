<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter String Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Novastream AB
 * @link		http://www.novastream.se
 */
 
/**
  * UcName
  * Capitalize the first letter of a name
  * @param string
  * @return string
  */ 
if ( ! function_exists('ucname'))
{
    function ucname($str = '')
    {
        // Nothing provided, nothing in return
        if(empty($str))
        {
            return $str;
        }
        else
        {
            // Make all chars lowercase
            $names = mb_strtolower($str, 'UTF-8');
            
            // Split the names by space (single word will get array pos 1)
            $names = explode(' ', $str);

            // Run through the names, make all chars lowercase and first char uppercase
            foreach ($names as $key => $value)
            {
                $names[$key] = mb_strtoupper(mb_substr($value, 0, 1));
                $names[$key] = $names[$key].mb_substr($value, 1);
            }

            // Convert the array to a string again
            $names = implode(" ", $names);
            
            // Split the names by -
            if (strpos($str, '-') !== false) 
            {
                $names = explode("-", $names);
                
                foreach ($names as $key => $value)
                {
                    $names[$key] = mb_strtoupper(mb_substr($value, 0, 1));
                    $names[$key] = $names[$key].mb_substr($value, 1);
                }

                $names = implode("-", $names);
            }

            return $names;
        }
    }
}

if ( ! function_exists('word_match'))
 {
	 function word_match($needles, $haystack)
    {
        foreach($needles as $needle){
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
 }