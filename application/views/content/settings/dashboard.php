
<div class="container-fluid">

	<div class="page-content">
		
		<div class="breadcrumbs">
			<h1>Inställningar</h1>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo base_url('dashboard'); ?>">Översikt</a>
				</li>
				<li class="active">Inställningar</li>
			</ol>
		</div>
        
        <div class="page-content-container">
                <div class="page-content-row">

                    <div class="page-sidebar">
                        <nav class="navbar">
                            <h3><i class="fa fa-tasks"></i> Alternativ</h3>
                            <ul class="nav navbar-nav margin-bottom-35">
                                <li class="active">
                                    <a href="<?php echo site_url('settings'); ?>">
                                        <i class="fa fa-pencil-square-o"></i> Inställningar
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('settings/users'); ?>">
                                        <i class="fa fa-user-plus"></i> Användare
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('settings/groups'); ?>">
                                        <i class="fa fa-users"></i> Grupper
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('settings/emailtemplate'); ?>">
                                        <i class="fa fa-envelope"></i> E-postmall
                                    </a>
                                </li>
                                <li>
                                    <a href="#" id="takebackup">
                                        <i class="fa fa-database"></i> Ladda hem databas
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="page-content-col">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="fa fa-pencil-square-o font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Inställningar</span>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                        if ($this->error_message != null)
                                        {
                                            echo '<div class="alert alert-danger alert-dismissable">
                                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"></button>
                                                    <h4 class="alert-heading">Meddelande!</h4>
                                                    '.$this->error_message.'
                                                </div>';
                                        }

                                        if ($this->success_message != null)
                                        {
                                            echo '<div class="alert alert-success alert-dismissable">
                                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"></button>
                                                    <h4 class="alert-heading">Meddelande!</h4>
                                                    '.$this->success_message.'
                                                </div>';
                                        }
                                    ?>
                                    
                                    <div class="portlet-body form">
                                        
                                        <?php 
                                            echo form_open(base_url('settings'), array("class" => "form-signin", "id" => "settings_form", "name" => "settings_form"));
                                        ?>
                                        
                                        <div class="form-body">
                                            
                                            <div class="form-group">
                                                <label for="site_title">Webbplatstitel:</label>         
                                                <input type="text" id="site_title" class="form-control" name="site_title" placeholder="Webbplatstitel" value="<?php echo set_value('site_title', $site_title); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="site_url">Webbplatsadress (URL):</label>         
                                                <input type="url" id="site_url" class="form-control" name="site_url" placeholder="Webbplatsadress (URL)" value="<?php echo set_value('site_url', $site_url); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="site_email">E-postadress:</label>         
                                                <input type="email" id="site_email" class="form-control" name="site_email" placeholder="E-postadress" value="<?php echo set_value('site_email', $site_email); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="log_threshold">Loggning:</label>
                                                <?php 
                                                    $log_options = array(
                                                        0 => 'Inaktiverad',
                                                        1 => 'Felmeddelanden',
                                                        2 => 'Debug',
                                                        3 => 'Information',
                                                        4 => 'Fullständig'
                                                    );
                                                    echo form_dropdown('log_threshold', $log_options, $log_threshold, array('class' => 'form-control select2', 'id' => 'log_threshold'));
                                                ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="min_password_length">Lösenordspolicy (min. antal tecken):</label>         
                                                <input type="text" id="min_password_length" class="form-control input_num_char" name="min_password_length" placeholder="Lösenordspolicy (min. antal tecken)" value="<?php echo set_value('min_password_length', $pass_policy_min); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="max_password_length">Lösenordspolicy (max. antal tecken):</label>         
                                                <input type="text" id="max_password_length" class="form-control input_num_char" name="max_password_length" placeholder="Lösenordspolicy (max. antal tecken)" value="<?php echo set_value('max_password_length', $pass_policy_max); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="maximum_login_attempts">Antal inloggningsförsök innan kontot blir låst:</label>         
                                                <input type="text" id="maximum_login_attempts" class="form-control input_num_char" name="maximum_login_attempts" placeholder="Antal inloggningsförsök innan kontot blir låst" value="<?php echo set_value('maximum_login_attempts', $login_attempts); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="lockout_time">Låst konto är låst så här länge:</label>         
                                                <input type="text" id="lockout_time" class="form-control input_num_sec" name="lockout_time" placeholder="Låst konto är låst så här länge" value="<?php echo set_value('lockout_time', $login_timeout); ?>" required>                          
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="super_admin_group">Grupp för super-administratörer:</label>
                                                <select name="super_admin_group" id="super_admin_group" class="form-control select2">
                                                    <?php
                                                        foreach ($groups as $group)
                                                        {
                                                            if ($group->name == $super_admin_group)
                                                            {
                                                                echo '<option value="'.$group->name.'" selected>'.$group->name.'</option>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<option value="'.$group->name.'">'.$group->name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="admin_group">Grupp för administratörer:</label>
                                                <select name="admin_group" id="admin_group" class="form-control select2">
                                                    <?php
                                                        foreach ($groups as $group)
                                                        {
                                                            if ($group->name == $admin_group)
                                                            {
                                                                echo '<option value="'.$group->name.'" selected>'.$group->name.'</option>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<option value="'.$group->name.'">'.$group->name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="user_group">Grupp för användare:</label>
                                                <select name="user_group" id="user_group" class="form-control select2">
                                                    <?php
                                                        foreach ($groups as $group)
                                                        {
                                                            if ($group->name == $user_group)
                                                            {
                                                                echo '<option value="'.$group->name.'" selected>'.$group->name.'</option>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<option value="'.$group->name.'">'.$group->name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="teacher_group">Grupp för utbildare:</label>
                                                <select name="teacher_group" id="teacher_group" class="form-control select2">
                                                    <?php
                                                        foreach ($groups as $group)
                                                        {
                                                            if ($group->name == $teacher_group)
                                                            {
                                                                echo '<option value="'.$group->name.'" selected>'.$group->name.'</option>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<option value="'.$group->name.'">'.$group->name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="extended_teacher_group">Grupp för utbildare med utökad behörighet:</label>
                                                <select name="extended_teacher_group" id="extended_teacher_group" class="form-control select2">
                                                    <?php
                                                        foreach ($groups as $group)
                                                        {
                                                            if ($group->name == $extended_teacher_group)
                                                            {
                                                                echo '<option value="'.$group->name.'" selected>'.$group->name.'</option>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<option value="'.$group->name.'">'.$group->name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        
                                            <div class="form-actions">
                                                <a href="<?php echo base_url('dashboard'); ?>" class="btn red">Avbryt</a>
                                                <button type="submit" class="btn blue">Spara</button>
                                            </div>
                                        
                                        <?php echo form_close(); ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		
	</div>
    
    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
    </script>