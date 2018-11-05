
<div class="container-fluid">

	<div class="page-content">
		
		<div class="breadcrumbs">
			<h1>E-postmall</h1>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo base_url('dashboard'); ?>">Översikt</a>
				</li>
				<li class="active">E-postmall</li>
			</ol>
		</div>
        
        <div class="page-content-container">
                <div class="page-content-row">

                    <div class="page-sidebar">
                        <nav class="navbar">
                            <h3><i class="fa fa-tasks"></i> Alternativ</h3>
                            <ul class="nav navbar-nav margin-bottom-35">
                                <li>
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
                                <li class="active">
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
                        <div>
                            <b>Shortcodes</b>
                            <br>
                            <code>{location} = Plats</code>
                            <br>
                            <code>{course_name} = Namn</code>
                            <br>
                            <code>{course_date} = Från datum</code>
                            <br>
                            <code>{course_date_end} = Till datum</code>
                            <br>
                            <code>{teachers} = Utbildare</code>
                        </div>
                    </div>

                    <div class="page-content-col">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="fa fa-pencil-square-o font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> E-postmall</span>
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
                                            echo form_open(base_url('settings/emailtemplate'), array("class" => "form-signin", "id" => "emailtemplateForm", "name" => "emailtemplateForm"));
                                        ?>
                                        
                                        <div class="form-body">
                                            
                                            <div class="form-group">
                                                <label for="default_mail_template">E-postmall för kallelser:</label>         
                                                <textarea id="default_mail_template" name="default_mail_template" class="form-control"><?php echo set_value('default_mail_template', $email_template); ?></textarea>
                                            </div>
                                            
                                        </div>
                                        
                                            <div class="form-actions">
                                                <a href="<?php echo base_url('dashboard'); ?>" class="btn red">Avbryt</a>
                                                <button type="button" id="restore_email_template" class="btn green">Återställ</button>
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
        var site_title = '<?php echo $this->config->item('site_title'); ?>';
        var site_logo = base_url + 'assets/apps/img/svu_sauf_id06.png';
    </script>