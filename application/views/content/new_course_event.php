    <div class="container-fluid">
        <div class="page-content">
            <div class="breadcrumbs">
                <h1>Utbildningstillfällen</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('course_event'); ?>">Utbildningstillfällen</a>
                    </li>
                    <li class="active">Skapa utbildningstillfälle</li>
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
                                <li class="active">
                                    <a href="<?php echo site_url('course_event/new_course_event'); ?>">
                                        <i class="fa fa-plus"></i> Skapa utbildningstillfälle
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
                                            <i class="fa fa-plus font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Nytt utbildningstillfälle</span>
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
                                    ?>

                                    <div class="portlet-body form">
                                        <?php
                                            echo form_open(base_url('course_event/new_course_event'), array("id" => "newcourseevent", "name" => "newcourseevent"));
                                        ?>
                                        <div class="form-body">

                                            <div class="form-group">
                                                <label for="course">Utbildning: *</label>
                                                <select id="course" name="course" class="form-control select2">
                                                    <option value="-1">Välj utbildning</option>
                                                    <?php
                                                        if (isset($courses))
                                                        {
                                                            foreach ($courses as $course)
                                                            {
                                                                echo '<option value="'.$course->id.'">'.$course->course_name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('course_name'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="teacher">Utbildare: *</label>
                                                <select id="teacher" name="teacher[]" class="form-control select2_tags" multiple>
                                                    <?php
                                                        if (isset($teachers))
                                                        {
                                                            foreach ($teachers as $teacher)
                                                            {
                                                                echo '<option value="'.$teacher->id.'">'.$teacher->first_name.' '.$teacher->last_name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('course_name'); ?></span>
                                            </div>

											<div class="form-group">
												<label for="extern_teacher">Ahlsell utbildning:</label>
												<input id="extern_teacher" type="checkbox" name="extern_teacher" value="1" class="form-control">
												<span class="text-danger"><?php echo form_error('extern_teacher'); ?></span>
											</div>

                                            <div class="form-group">
                                                <label for="course_code">Kurskod:</label>
                                                <input id="course_code" type="text" class="form-control" name="course_code" placeholder="Kurskoden genereras automatiskt" value="<?php echo set_value('course_code'); ?>" autocomplete="off" readonly>
                                                <span class="text-danger"><?php echo form_error('course_code'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="customized">Typ av utbildning: *</label>
                                                <select id="customized" name="customized" class="form-control select2">
                                                    <option value="0">Öppen</option>
                                                    <option value="1">Företagsanpassad</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('customized'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_date">Datum (kursstart): *</label>
                                                <input id="course_date" type="text" class="form-control" name="course_date" value="<?php echo set_value('course_date'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_date'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_date_end">Datum (kursslut): *</label>
                                                <input id="course_date_end" type="text" class="form-control" name="course_date_end" value="<?php echo set_value('course_date_end'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_date_end'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_from">Tid (kursstart): *</label>
                                                <input id="course_time_from" type="text" class="form-control" name="course_time_from" value="<?php echo set_value('course_time_from'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_time_from'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_end">Tid (kursslut): *</label>
                                                <input id="course_time_end" type="text" class="form-control" name="course_time_end" value="<?php echo set_value('course_time_end'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_date_end'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="localInformation">Platsinformation Exempelvis Hotel: </label>
                                                <input id="localInformation" type="text" class="form-control" name="localInformation" autocomplete="off" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="location">Fullständig gatuadress + gatunr (för utbildningstillfället): *</label>
                                                <input id="location" type="text" class="form-control" name="location" placeholder="Adress" value="<?php echo set_value('location'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('location'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="zip">Postnummer: *</label>
                                                <input id="zip" type="text" class="form-control" name="zip" placeholder="Postnummer" value="<?php echo set_value('zip'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('zip'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="city">Ort: *</label>
                                                <input id="city" type="text" class="form-control" name="city" placeholder="Ort" value="<?php echo set_value('city'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('city'); ?></span>
                                            </div>

                                            <input id="county" type="hidden" name="county" value="<?php echo set_value('county'); ?>">

                                            <div class="form-group">
                                                <label for="event_contact">Kontaktperson Plats (Exempelvis Hotel) + tel:</label>
                                                <input id="event_contact" type="text" class="form-control" name="event_contact" placeholder="Kontaktperson + tel" value="<?php echo set_value('event_contact'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('event_contact'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="food">Lunch, fika:</label>
                                                <input id="food" type="text" class="form-control" name="food" placeholder="Lunch/fika" value="<?php echo set_value('food'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('food'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="maximum_participants">Max antal deltagare:</label>
                                                <input id="maximum_participants" type="text" class="form-control" name="maximum_participants" placeholder="Välj utbildning överst för att hämta standardvärde" value="<?php echo set_value('maximum_participants'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('maximum_participants'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="living">Övrigt:</label>
                                                <input id="living" type="text" class="form-control" name="living" placeholder="Övrigt" value="<?php echo set_value('living'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('living'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_material">Kursmaterial:</label>
                                                <input id="course_material" type="text" class="form-control" name="course_material" placeholder="Kursmaterial" value="<?php echo set_value('course_material'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('course_material'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="send_material_to">Adress att skicka material till:</label>
                                                <input id="send_material_to" type="text" class="form-control" name="send_material_to" placeholder="Adress att skicka material till" value="<?php echo set_value('send_material_to'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('send_material_to'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="notify_internal">Skicka information till utbildare</label>
                                                <input id="notify_internal" type="checkbox" name="notify_internal" value="1" class="form-control">
                                                <span class="text-danger"><?php echo form_error('notify_internal'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="freetext">Anteckningar:</label>
                                                <textarea name="freetext" id="freetext" class="form-control"><?php echo set_value('freetext');?></textarea>
                                                <span class="text-danger"><?php echo form_error('freetext'); ?></span>
                                            </div>

                                            <div class="form-actions">
                                                <a href="<?php echo site_url('course_event'); ?>" class="btn red">Avbryt</a>
                                                <button type="submit" class="btn blue">Spara</button>
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
