    <div class="container-fluid">
        <div class="page-content">
            <div class="breadcrumbs">
                <h1>Event</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('course_event'); ?>">Utbildningstillfälle</a>
                    </li>
                    <?php
                        if ($readonly)
                        {
                            echo '<li class="active">Visa '.$course_event->course_code.'</li>';
                        }
                        else
                        {
                            echo '<li class="active">Redigera '.$course_event->course_code.'</li>';
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
                                    <a href="<?php echo site_url('course_event'); ?>">
                                        <i class="fa fa-list-alt"></i> Utbildningstillfällen
                                    </a>
                                </li>
                                <?php
                                if ($this->auth == 'user' || $this->auth == 'admin' || $this->auth == 'super_admin' || $this->auth || $this->auth == 'extended_teacher')
                                {
                                ?>
                                <li>
                                    <a href="<?php echo site_url('course_event/new_course_event'); ?>">
                                        <i class="fa fa-plus"></i> Skapa utbildningstillfälle
                                    </a>
                                </li>
                                <?php
                                }
                                ?>
                                <?php
                                    if ($readonly)
                                    {
                                        echo '<li class="active">
                                            <a href="'.site_url('course_event/edit_course_event/').$course_event->id.'/true">
                                                <i class="fa fa-eye"></i> Visa utbildningstillfälle
                                            </a>
                                        </li>';
                                        if ($this->auth == 'user' || $this->auth == 'admin' || $this->auth == 'super_admin' || $this->auth == 'extended_teacher')
                                        {
                                            echo '<li>
                                                <a href="'.site_url('course_event/edit_course_event/').$course_event->id.'">
                                                    <i class="fa fa-pencil"></i> Redigera utbildningstillfälle
                                                </a>
                                            </li>';
                                        }
                                    }
                                    else
                                    {
                                        echo '<li>
                                            <a href="'.site_url('course_event/edit_course_event/').$course_event->id.'/true">
                                                <i class="fa fa-eye"></i> Visa utbildningstillfälle
                                            </a>
                                        </li>';
                                        if ($this->auth == 'user' || $this->auth == 'admin' || $this->auth == 'super_admin' || $this->auth == 'extended_teacher')
                                        {
                                            echo '<li class="active">
                                                <a href="'.site_url('course_event/edit_course_event/').$course_event->id.'">
                                                    <i class="fa fa-pencil"></i> Redigera utbildningstillfälle
                                                </a>
                                            </li>';
                                        }
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
                                                    <span class="caption-subject bold uppercase"> Visa utbildningstillfälle</span>';
                                                }
                                                else
                                                {
                                                    echo '<i class="fa fa-pencil font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Redigera utbildningstillfälle</span>';
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
                                            echo form_open(base_url('course_event/edit_course_event/'.$course_event->id), array("id" => "editcourseevent", "name" => "editcourseevent"));
                                        ?>
                                        <div class="form-body">

                                            <div class="form-group">
                                                <label for="course">Utbildning: *</label>
                                                <select id="course" name="course" class="form-control select2"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <?php
                                                        if (isset($courses))
                                                        {
                                                            foreach ($courses as $course)
                                                            {
                                                                if ($course->id == $course_event->course_id)
                                                                {
                                                                    echo '<option value="'.$course->id.'" selected>'.$course->course_name.'</option>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<option value="'.$course->id.'">'.$course->course_name.'</option>';
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('course_name'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="teacher">Utbildare: *</label>
                                                <select id="teacher" name="teacher[]" class="form-control select2_tags" multiple<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <?php
                                                        if (isset($teachers))
                                                        {
                                                            foreach ($teachers as $teacher)
                                                            {
                                                                if (!empty($this->course_event_model->get_teacher($course_event->id)))
                                                                {
                                                                    foreach($this->course_event_model->get_teacher($course_event->id) as $ti)
                                                                    {
                                                                        if ($ti->teacher_id == $teacher->id)
                                                                        {
                                                                            echo '<option value="'.$teacher->id.'" selected>'.$teacher->first_name.' '.$teacher->last_name.'</option>';
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '<option value="'.$teacher->id.'">'.$teacher->first_name.' '.$teacher->last_name.'</option>';
                                                                        }
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    echo '<option value="'.$teacher->id.'">'.$teacher->first_name.' '.$teacher->last_name.'</option>';
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('course_name'); ?></span>
                                            </div>

											<div class="form-group">
												<label for="extern_teacher">Ahlsell utbildning: </label>
                                                <input id="extern_teacher" type="checkbox" name="extern_teacher" value="1" class="form-control"<?php if(isset($course_event->extern_teacher) && $course_event->extern_teacher == 1){ echo ' checked="checked"'; } if ($readonly) { echo ' disabled="disabled"'; } ?>>
												<span class="text-danger"><?php echo form_error('extern_teacher'); ?></span>
											</div>

                                            <div class="form-group">
                                                <label for="course_code">Kurskod: *</label>
                                                <input id="course_code" type="text" class="form-control" name="course_code" placeholder="Kurskoden genereras automatiskt" value="<?php echo set_value('course_code', $course_event->course_code); ?>" autocomplete="off" readonly required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_code'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="customized">Typ av utbildning: *</label>
                                                <select id="customized" name="customized" class="form-control select2"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <option value="0"<?php if ($course_event->customized == 0) { echo ' selected';} ?>>Öppen</option>
                                                    <option value="1"<?php if ($course_event->customized == 1) { echo ' selected';} ?>>Företagsanpassad</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('customized'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_date">Datum (kursstart): *</label>
                                                <input id="course_date" type="text" class="form-control" name="course_date" value="<?php echo set_value('course_date', explode(' ', $course_event->course_date)[0]); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_date'); ?></span>
                                            </div>

                                            <input type="hidden" name="course_date_org" value="<?php echo set_value('course_date', $course_event->course_date); ?>">

                                            <div class="form-group">
                                                <label for="course_date">Datum (kursslut): *</label>
                                                <input id="course_date_end" type="text" class="form-control" name="course_date_end" value="<?php echo set_value('course_date_end', explode(' ', $course_event->course_date_end)[0]); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_date_end'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_from">Tid (kursstart): *</label>
                                                <input id="course_time_from" type="text" class="form-control" name="course_time_from" value="<?php echo set_value('course_time_from', substr($course_event->course_date, 11)); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_time_from'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_end">Tid (kursslut): *</label>
                                                <input id="course_time_end" type="text" class="form-control" name="course_time_end" value="<?php echo set_value('course_time_end', substr($course_event->course_date_end, 11)); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_date_end'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="localInformation">Platsinformation Exempelvis Hotel: </label>
                                                <input id="localInformation" type="text" class="form-control" name="localInformation" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                            </div>

                                            <div class="form-group">
                                                <label for="location">Fullständig gatuadress + gatunr (för utbildningstillfället): *</label>
                                                <input id="location" type="text" class="form-control" name="location" placeholder="Adress" value="<?php echo set_value('location', $course_event->location); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('location'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="zip">Postnummer: *</label>
                                                <input id="zip" type="text" class="form-control" name="zip" placeholder="Postnummer" value="<?php echo set_value('zip', $course_event->zip); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('zip'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="city">Ort: *</label>
                                                <input id="city" type="text" class="form-control" name="city" placeholder="Ort" value="<?php echo set_value('city', $course_event->city); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('city'); ?></span>
                                            </div>

                                            <input id="county" type="hidden" name="county" value="<?php echo set_value('county', $course_event->county); ?>">

                                            <div class="form-group">
                                                <label for="event_contact">Kontaktperson Plats (Exempelvis Hotel) + tel:</label>
                                                <input id="event_contact" type="text" class="form-control" name="event_contact" placeholder="Kontaktperson + tel" value="<?php echo set_value('event_contact', $course_event->event_contact); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('event_contact'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="food">Lunch, fika:</label>
                                                <input id="food" type="text" class="form-control" name="food" placeholder="Lunch/fika" value="<?php echo set_value('food', $course_event->food); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('food'); ?></span>
                                            </div>

                                            <div class="form-group">
												<label for="food_booked">Konferens/Lunch bokad: </label>
                                                <input id="food_booked" type="checkbox" name="food_booked" value="1" class="form-control"<?php if(isset($course_event->food_booked) && $course_event->food_booked == 1){ echo ' checked="checked"'; } if ($readonly) { echo ' disabled="disabled"'; } ?>>
												<span class="text-danger"><?php echo form_error('food_booked'); ?></span>
											</div>

                                            <div class="form-group">
                                                <label for="maximum_participants">Max antal deltagare:</label>
                                                <input id="maximum_participants" type="text" class="form-control" name="maximum_participants" placeholder="Välj utbildning överst för att hämta standardvärde" value="<?php echo set_value('maximum_participants', $course_event->maximum_participants); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?><?php if ($this->auth == 'user') { echo ' readonly'; } ?>>
                                                <span class="text-danger"><?php echo form_error('maximum_participants'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="living">Övrigt:</label>
                                                <input id="living" type="text" class="form-control" name="living" placeholder="Övrigt" value="<?php echo set_value('living', $course_event->living); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('living'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_material">Kursmaterial:</label>
                                                <input id="course_material" type="text" class="form-control" name="course_material" placeholder="Kursmaterial" value="<?php echo set_value('course_material', $course_event->course_material); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_material'); ?></span>
                                            </div>

                                            <div class="form-group">
												<label for="material_sent">Material skickat:</label>
                                                <input id="material_sent" type="checkbox" name="material_sent" value="1" class="form-control"<?php if(isset($course_event->material_sent) && $course_event->material_sent == 1){ echo ' checked="checked"'; } if ($readonly) { echo ' disabled="disabled"'; } ?>>
												<span class="text-danger"><?php echo form_error('material_sent'); ?></span>
											</div>

                                            <div class="form-group">
                                                <label for="send_material_to">Adress att skicka material till:</label>
                                                <input id="send_material_to" type="text" class="form-control" name="send_material_to" placeholder="Adress att skicka material till" value="<?php echo set_value('send_material_to', $course_event->send_material_to); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('send_material_to'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="freetext">Anteckningar:</label>
                                                <textarea name="freetext" id="freetext" class="form-control"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>><?php echo set_value('freetext', $course_event->freetext);?></textarea>
                                                <span class="text-danger"><?php echo form_error('freetext'); ?></span>
                                            </div>

                                            <?php

                                                // if create time is set and valid
                                                if (isset($course_event->create_time) && !empty($course_event->create_time) && $course_event->create_time != '0000-00-00 00:00:00')
                                                {
                                                    if (isset($course_event->user_id) && !empty($course_event->user_id) && is_numeric($course_event->user_id) && $course_event->user_id > 0)
                                                    {
                                                        if (!is_null($this->ion_auth->user($course_event->user_id)->row()))
                                                        {
                                                            echo '<pre><i>Skapad: '.$course_event->create_time.'</i> av '.$this->ion_auth->user($course_event->user_id)->row()->first_name.' '.$this->ion_auth->user($course_event->user_id)->row()->last_name.'<br>';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo '<pre><i>Skapad: '.$course_event->create_time.'</i><br>';
                                                    }
                                                }
                                                else
                                                {
                                                    echo '<pre>';
                                                }

                                                // if edit time is set and valid
                                                if (isset($course_event->edit_time) && !empty($course_event->edit_time) && $course_event->edit_time != '0000-00-00 00:00:00')
                                                {
                                                    if (isset($course_event->edited_by) && !empty($course_event->edited_by) && is_numeric($course_event->edited_by) && $course_event->edited_by > 0)
                                                    {
                                                        if (!is_null($this->ion_auth->user($course_event->edited_by)->row()))
                                                        {
                                                            echo '<i>Redigerad: '.$course_event->edit_time.'</i> av '.$this->ion_auth->user($course_event->edited_by)->row()->first_name.' '.$this->ion_auth->user($course_event->edited_by)->row()->last_name;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo '<i>Redigerad: '.$course_event->edit_time.'</i>';
                                                    }

                                                    echo '</pre>';
                                                }
                                                else
                                                {
                                                    echo '</pre>';
                                                }

                                                if (isset($dates) && !empty($dates))
                                                {
                                                    foreach ($dates as $d)
                                                    {
                                                        echo '<pre><strong>Detta utbildningstillfälle är flyttat från '.$d->date_from.' till '.$d->date_to.'</strong></pre>';
                                                    }
                                                }
                                            ?>

                                            <div class="form-actions">
                                                <a href="<?php echo site_url('course_event'); ?>" class="btn red">Avbryt</a>
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

        <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';

            //data initialization script for course time
            var courses_time = {};
            <?php
              if (isset($courses))
              {
                  foreach ($courses as $course)
                  {
            ?>
                    courses_time['<?php echo $course->id?>'] = {
                      'from' : '<?php echo substr($course->course_time_from, 0, 5) ?>',
                      'end' :  '<?php echo substr($course->course_time_end, 0, 5) ?>'
                    };
            <?php
                  }
              }
            ?>

            //data initialization for teachers course
            var teachers = [];
            
             <?php
                if (isset($teachers))
                {
                    foreach ($teachers as $teacher)
                    {
            ?>
                        teachers.push(
                        {
                            'id' : '<?php echo $teacher->id; ?>',
                            'courses' : '<?php echo $teacher->courses; ?>',
                            'name' : "<?php echo $teacher->first_name . ' ' . $teacher->last_name?>"
                        });
            <?php
                    }
                }
            ?>
        </script>
