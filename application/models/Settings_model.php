<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();        
    }
    
    /**
     * Loads the ion_auth.php configuration file and updates the default groups
     * Be sure to leave whitespace as is, not needed but it's nicer.
     * @param array update_ion_auth
     * @return bool
    */
    public function update_ion_auth($update_ion_auth)
    {
        if (!isset($update_ion_auth) || empty($update_ion_auth))
        {
            return false;
        }
        else 
        {
            if (!file_exists(APPPATH . '/config/ion_auth.php'))
            {
                return false;               
            }
            else 
            {
                $ion_auth_file = read_file(APPPATH . '/config/ion_auth.php');
                $min_password_length = $this->config->item('min_password_length', 'ion_auth');
                $max_password_length = $this->config->item('max_password_length', 'ion_auth');
                $maximum_login_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');
                $lockout_time = $this->config->item('lockout_time', 'ion_auth');
                $super_admin_group = $this->config->item('admin_group', 'ion_auth');
                $admin_group = $this->config->item('regular_admin_group', 'ion_auth');
                $user_group = $this->config->item('default_group', 'ion_auth');
                $teacher_group = $this->config->item('teacher_group', 'ion_auth');
                $extended_teacher_group = $this->config->item('extended_teacher_group', 'ion_auth');
                
                foreach ($update_ion_auth as $key => $value)
                {
                    if ($key == 'min_password_length' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['min_password_length'] = $min_password_length;", "config['min_password_length'] = $value;", $ion_auth_file);
                    }
                    if ($key == 'max_password_length' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['max_password_length'] = $max_password_length;", "config['max_password_length'] = $value;", $ion_auth_file);
                    }
                    if ($key == 'maximum_login_attempts' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['maximum_login_attempts'] = $maximum_login_attempts;", "config['maximum_login_attempts'] = $value;", $ion_auth_file);
                    }
                    if ($key == 'lockout_time' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['lockout_time'] = $lockout_time;", "config['lockout_time'] = $value;", $ion_auth_file);
                    }
                    if ($key == 'super_admin_group' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['admin_group'] = '$super_admin_group';", "config['admin_group'] = '$value';", $ion_auth_file);
                    }
                    if ($key == 'admin_group' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['regular_admin_group'] = '$admin_group';", "config['regular_admin_group'] = '$value';", $ion_auth_file);
                    }
                    if ($key == 'user_group' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['default_group'] = '$user_group';", "config['default_group'] = '$value';", $ion_auth_file);
                    }
                    if ($key == 'teacher_group' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['teacher_group'] = '$teacher_group';", "config['teacher_group'] = '$value';", $ion_auth_file);
                    }
                    if ($key == 'extended_teacher_group' && !empty($value))
                    {
                        $ion_auth_file = str_replace("config['extended_teacher_group'] = '$extended_teacher_group';", "config['extended_teacher_group'] = '$value';", $ion_auth_file);
                    }
                }

                if ( ! write_file(APPPATH . '/config/ion_auth.php', $ion_auth_file))
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }
    
    /**
     * Loads the config.php configuration file and updates system wide
     * settings. Be sure to leave whitespace as is, not needed but
     * it's nicer.
     * @param array update_ci_config
     * @return bool
    */
    public function update_ci_config($update_ci_config)
    {
        if (!isset($update_ci_config) || empty($update_ci_config))
        {
            return false;
        }
        else 
        {
            if (!file_exists(APPPATH . '/config/config.php'))
            {
                return false;               
            }
            else 
            {
                $ci_config_file = read_file(APPPATH . '/config/config.php');
                $site_title = $this->config->item('site_title');
                $site_url = $this->config->item('base_url');
                $site_email = $this->config->item('site_email');
                $log_threshold = $this->config->item('log_threshold');
                
                foreach ($update_ci_config as $key => $value)
                {
                    if ($key == 'site_title' && !empty($value))
                    {
                        $ci_config_file = str_replace("config['site_title'] = '$site_title';", "config['site_title'] = '$value';", $ci_config_file);
                    }
                    if ($key == 'site_url' && !empty($value))
                    {
                        $ci_config_file = str_replace("config['base_url'] = '$site_url';", "config['base_url'] = '$value';", $ci_config_file);
                    }
                    if ($key == 'site_email' && !empty($value))
                    {
                        $ci_config_file = str_replace("config['site_email'] = '$site_email';", "config['site_email'] = '$value';", $ci_config_file);
                    }
                    if ($key == 'log_threshold')
                    {
                        $ci_config_file = str_replace("config['log_threshold'] = $log_threshold;", "config['log_threshold'] = $value;", $ci_config_file);
                    }
                }                

                if ( ! write_file(APPPATH . '/config/config.php', $ci_config_file))
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }

    /**
     * Get a specific setting from the settings table
     * @param string
     * @return mixed
    */
    public function get_setting($key = null)
    {
        if ($key != null)
        {
            $this->db->select('vValue');
            $this->db->where('vKey', $key);
            $this->db->from('settings');
            $result = $this->db->get()->result_object();

            if (count($result > 0))
            {
                return $result[0]->vValue;
            }

            return false;

        }
        else 
        {
            return false;
        }
    }

    /**
     * Update a specific setting
     * @param string
     * @param string
     * @return bool
    */
    public function update_setting($key = null, $value = null)
    {
        if ($key != null && $value != null)
        {
            $this->db->where('vkey', $key);
            
            if ($this->db->update('settings', array('vValue' => $value)))
            {
                return true;
            }
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
}

?>