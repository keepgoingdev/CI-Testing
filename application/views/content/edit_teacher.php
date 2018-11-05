
    <div class="container-fluid">
        <div class="page-content">

            <div class="breadcrumbs">
                <h1>Utbildare</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('teacher'); ?>">Utbildare</a>
                    </li>
                    <?php
                        if ($readonly)
                        {
                            echo '<li class="active">Visa '.$teacher->first_name.' '.$teacher->last_name.'</li>';
                        }
                        else
                        {
                            echo '<li class="active">Redigera '.$teacher->first_name.' '.$teacher->last_name.'</li>';
                        }
                    ?>
                </ol>
            </div>

            <div class="page-content-container">
                <div class="page-content-row">

                    <div class="page-sidebar">
                        <nav class="navbar">
                            <h3><i class="fa fa-tasks"></i> Alternativ</h3>
                            <ul class="nav navbar-nav margin-bottom-35">
                                <li>
                                    <a href="<?php echo base_url('teacher'); ?>">
                                        <i class="fa fa-list-alt"></i> Alla utbildare
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('teacher/new_teacher'); ?>">
                                        <i class="fa fa-plus"></i> Skapa utbildare
                                    </a>
                                </li>
                                <?php
                                    if ($readonly)
                                    {
                                        echo '<li class="active">
                                            <a href="'.site_url("teacher/edit_teacher/$id").'/true">
                                                <i class="fa fa-eye"></i> Visa utbildare
                                            </a>
                                        </li>';
                                        echo '<li>
                                            <a href="'.site_url("teacher/edit_teacher/$id").'">
                                                <i class="fa fa-pencil"></i> Redigera utbildare
                                            </a>
                                        </li>';
                                    }
                                    else
                                    {
                                        echo '<li>
                                            <a href="'.site_url("teacher/edit_teacher/$id").'/true">
                                                <i class="fa fa-eye"></i> Visa utbildare
                                            </a>
                                        </li>';
                                        echo '<li class="active">
                                            <a href="'.site_url("teacher/edit_teacher/$id").'">
                                                <i class="fa fa-pencil"></i> Redigera utbildare
                                            </a>
                                        </li>';
                                    }
                                ?>
                            </ul>
                        </nav>
                    </div>

                    <div class="page-content-col">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <?php
                                                if ($readonly)
                                                {
                                                    echo '<i class="fa fa-eye font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Visa utbildare</span>';
                                                }
                                                else
                                                {
                                                    echo '<i class="fa fa-pencil font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Redigera utbildare</span>';
                                                }
                                            ?>
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
                                            echo form_open(base_url('teacher/edit_teacher/'.$teacher->id.''), array("class" => "form-signin", "id" => "loginform", "name" => "loginform"));
                                        ?>
                                        
                                        <div class="form-body">

                                            <div class="form-group">
                                                <label for="first_name">Förnamn: * </label>
                                                <input type="text" id="first_name" class="form-control" name="first_name" placeholder="Förnamn" value="<?php echo set_value('first_name', $teacher->first_name); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="last_name">Efternamn: *</label>
                                                <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Efternamn" value="<?php echo set_value('last_name', $teacher->last_name); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Adress: *</label>
                                                <input type="text" id="address" class="form-control" name="address" placeholder="Adress" value="<?php echo set_value('address', $teacher->address); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="password">Lösenord: * <sup><a href="#" id="gen_new_password">Nytt lösenord</a></sup></label>
                                                <input type="text" id="password" class="form-control" name="password" placeholder="Lämna blankt för behålla nuvarande lösenord" value="" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('password'); ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="email">E-post: *</label>
                                                <input type="email" id="email" name="email" class="form-control" placeholder="E-post" value="<?php echo set_value('email_address', $teacher->email); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="phone">Telefon: *</label>
                                                <input type="text" id="phone" class="form-control" name="phone"  placeholder="Telefon" value="<?php echo set_value('phone', $teacher->phone); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="alt_phone">Alternativ telefon:</label>
                                                    <input type="text" id="alt_phone" name="secondary_phone" class="form-control" placeholder="Alternativ telefon" value="<?php echo set_value('secondary_phone', $teacher->secondary_phone); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <span class="text-danger"><?php echo form_error('secondary_phone'); ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="company">Företag:</label>
                                                <input type="text" id="company" name="company" class="form-control" placeholder="Företag" value="<?php echo set_value('company', $teacher->company); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('company'); ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="courses">Utbildningar *</label>
                                                <select name="courses[]" id="courses" class="form-control" multiple<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <?php
                                                        $teacher_courses = explode(",", $teacher->courses);
                                                        foreach ($courses as $course)
                                                        {
                                                            if (in_array($course->id, $teacher_courses))
                                                            {
                                                                echo '<option value="'.$course->id.'" selected>'.$course->course_name.'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$course->id.'">'.$course->course_name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('courses'); ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="extended_auth">Utökad behörighet</label>
                                                <input id="extended_auth" type="checkbox" name="extended_auth" value="1" class="form-control"<?php if ($teacher->extended_auth == 1) { echo ' checked';} if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('extended_auth'); ?></span>
                                            </div>
                                            
                                            <input type="hidden" name="user_id" value="<?php echo $teacher->user_id; ?>">
                                            
                                            <div class="form-group">
                                                <label for="freetext">Anteckningar:</label>
                                                <textarea name="freetext" id="freetext" class="form-control"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>><?php echo set_value('freetext', $teacher->freetext);?></textarea>
                                                <span class="text-danger"><?php echo form_error('freetext'); ?></span>
                                            </div>
                                            
                                            <?php
                                                // if create time is set and valid
                                                if (isset($teacher->create_time) && !empty($teacher->create_time) && $teacher->create_time != '0000-00-00 00:00:00')
                                                {
                                                    if (isset($teacher->created_by) && !empty($teacher->created_by) && is_numeric($teacher->created_by) && $teacher->created_by > 0)
                                                    {
                                                        echo '<pre><i>Skapad: '.$teacher->create_time.'</i> av '.$this->ion_auth->user($teacher->created_by)->row()->first_name.' '.$this->ion_auth->user($teacher->created_by)->row()->last_name.'<br>';
                                                    }
                                                    else
                                                    {
                                                        echo '<pre><i>Skapad: '.$teacher->create_time.'</i><br>';
                                                    }
                                                }
                                                else
                                                {
                                                    echo '<pre>';
                                                }
                                                
                                                // if edit time is set and valid
                                                if (isset($teacher->edit_time) && !empty($teacher->edit_time) && $teacher->edit_time != '0000-00-00 00:00:00')
                                                {
                                                    if (isset($teacher->edited_by) && !empty($teacher->edited_by) && is_numeric($teacher->edited_by) && $teacher->edited_by > 0)
                                                    {
                                                        echo '<i>Redigerad: '.$teacher->edit_time.'</i> av '.$this->ion_auth->user($teacher->edited_by)->row()->first_name.' '.$this->ion_auth->user($teacher->edited_by)->row()->last_name;
                                                    }
                                                    else
                                                    {
                                                        echo '<i>Redigerad: '.$teacher->edit_time.'</i>';
                                                    }
                                                    
                                                    echo '</pre>';
                                                }
                                                else
                                                {
                                                    echo '</pre>';
                                                }
                                            ?>
                                        
                                       <div class="form-actions">
                                            <a href="<?php echo site_url('teacher'); ?>" class="btn red">Avbryt</a>
                                            <?php 
                                                if (!$readonly)
                                                {
                                                    echo '<button type="submit" class="btn blue">Spara</button>';
                                                }
                                            ?>
                                        </div>
                                            
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



