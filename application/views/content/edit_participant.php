
    <div class="container-fluid">
        <div class="page-content">
            
            <div class="breadcrumbs">
                <h1>Deltagare</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('participant'); ?>">Deltagare</a>
                    </li>
                    <?php
                        if ($readonly)
                        {
                            echo '<li class="active">Visa '.$participant->first_name.' '.$participant->last_name.'</li>';
                        }
                        else
                        {
                            echo '<li class="active">Redigera '.$participant->first_name.' '.$participant->last_name.'</li>';
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
                                    <a href="<?php echo site_url('participant'); ?>">
                                        <i class="fa fa-list-alt"></i> Alla deltagare
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('participant/new_participant'); ?>">
                                        <i class="fa fa-plus "></i> Skapa deltagare 
                                    </a>
                                </li>
                                <?php
                                    if ($readonly)
                                    {
                                        echo '<li class="active">
                                            <a href="'.site_url('participant/edit_participant/'.$participant->id).'/true">
                                                <i class="fa fa-eye"></i> Visa deltagare 
                                            </a>
                                        </li>';
                                        
                                        echo '<li>
                                            <a href="'.site_url('participant/edit_participant/'.$participant->id).'">
                                                <i class="fa fa-pencil"></i> Redigera deltagare 
                                            </a>
                                        </li>';
                                    }
                                    else
                                    {
                                        echo '<li>
                                            <a href="'.site_url('participant/edit_participant/'.$participant->id).'/true">
                                                <i class="fa fa-eye"></i> Visa deltagare 
                                            </a>
                                        </li>';
                                        
                                        echo '<li class="active">
                                            <a href="'.site_url('participant/edit_participant/'.$participant->id).'">
                                                <i class="fa fa-pencil"></i> Redigera deltagare 
                                            </a>
                                        </li>';
                                    }
                                ?>
                            </ul>
                        </nav>
                    </div>
                    
                    <div class="page-content-col">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <?php
                                                if ($readonly)
                                                {
                                                    echo '<i class="fa fa-eye font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Visa deltagare</span>';
                                                }
                                                else
                                                {
                                                    echo '<i class="fa fa-pencil font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Redigera deltagare</span>';
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
                                            echo form_open(base_url('participant/edit_participant/'.$participant->id), array("class" => "form-signin", "id" => "participantform", "name" => "participantform"));
                                        ?>
                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label for="personalnumber">Personnummer: * </label>
                                                    <input type="text" id="personalnumber" class="form-control" name="personalnumber" placeholder="Personnummer" value="<?php echo set_value('personalnumber', $participant->personalnumber); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <span class="text-danger"><?php echo form_error('personalnumber'); ?></span>
                                                </div>
                                                
                                                <input type="hidden" name="pn_org" value="<?php echo $participant->personalnumber; ?>">
                                                
                                                <div class="form-group">
                                                    <div class="checkbox" id="foreign_ssn">
                                                        <label><input type="checkbox" name="foreign_ssn" value="1"<?php if ($participant->foreign_ssn == '1') { echo ' checked';} if ($readonly) { echo ' disabled="disabled"';} ?>>Deltagaren saknar svenskt personnummer.</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="first_name">Förnamn: *</label>
                                                    <input type="text" id="first_name" class="form-control" name="first_name" placeholder="Förnamn" value="<?php echo set_value('first_name', $participant->first_name); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="last_name">Efternamn: *</label>
                                                    <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Efternamn" value="<?php echo set_value('last_name', $participant->last_name); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="company">Företag: *</label>
                                                    <select id="company" name="company" class="form-control select2"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                        <option value="<?php echo $customer->id; ?>" selected><?php echo $customer->company_name; ?></option>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('company'); ?></span>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="phone">Telefon:</label>
                                                    <input type="text" id="phone" class="form-control" name="phone" placeholder="Telefon" value="<?php echo set_value('phone', $participant->phone); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="email">Epost:</label>
                                                    <input type="email" id="email" class="form-control" name="email" placeholder="Epost" value="<?php echo set_value('email', $participant->email); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="freetext">Anteckningar:</label>
                                                    <textarea name="freetext" id="freetext" class="form-control"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>><?php echo set_value('freetext', $participant->freetext);?></textarea>
                                                    <span class="text-danger"><?php echo form_error('freetext'); ?></span>
                                                </div>
                                                
                                                <?php
                                                    // if create time is set and valid
                                                    if (isset($participant->create_time) && !empty($participant->create_time) && $participant->create_time != '0000-00-00 00:00:00')
                                                    {
                                                        if (isset($participant->created_by) && !empty($participant->created_by) && is_numeric($participant->created_by) && $participant->created_by > 0)
                                                        {
                                                            echo '<pre><i>Skapad: '.$participant->create_time.'</i> av '.$this->ion_auth->user($participant->created_by)->row()->first_name.' '.$this->ion_auth->user($participant->created_by)->row()->last_name.'<br>';
                                                        }
                                                        else
                                                        {
                                                            echo '<pre><i>Skapad: '.$participant->create_time.'</i><br>';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo '<pre>';
                                                    }
                                                    
                                                    // if edit time is set and valid
                                                    if (isset($participant->edit_time) && !empty($participant->edit_time) && $participant->edit_time != '0000-00-00 00:00:00')
                                                    {
                                                        if (isset($participant->edited_by) && !empty($participant->edited_by) && is_numeric($participant->edited_by) && $participant->edited_by > 0)
                                                        {
                                                            echo '<i>Redigerad: '.$participant->edit_time.'</i> av '.$this->ion_auth->user($participant->edited_by)->row()->first_name.' '.$this->ion_auth->user($participant->edited_by)->row()->last_name;
                                                        }
                                                        else
                                                        {
                                                            echo '<i>Redigerad: '.$participant->edit_time.'</i>';
                                                        }
                                                        
                                                        echo '</pre>';
                                                    }
                                                    else
                                                    {
                                                        echo '</pre>';
                                                    }
                                                ?>                                        
                                        
                                        <div class="form-actions">
                                            <a href="<?php echo site_url('participant'); ?>" class="btn red">Avbryt</a>
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
        var ajax_url = '<?php echo base_url('participant/search_companies');?>';
        var base_url = '<?php echo base_url(); ?>';
        var foreign_ssn = '<?php echo $participant->foreign_ssn; ?>';
    </script>