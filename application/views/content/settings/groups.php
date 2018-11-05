
<div class="container-fluid">

	<div class="page-content">
		
		<div class="breadcrumbs">
			<h1>Inställningar</h1>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo base_url('dashboard'); ?>">Översikt</a>
				</li>
                <li>
					<a href="<?php echo base_url('settings'); ?>">Inställningar</a>
				</li>
				<li class="active">Grupper</li>
			</ol>
		</div>
        
        <div class="page-content-container">
                <div class="page-content-row">

                    <div class="page-sidebar">
                        <nav class="navbar">
                            <h3><i class="fa fa-tasks"></i> Alternativ</h3>
                            <ul class="nav navbar-nav margin-bottom-35">
                                <li>
                                    <a href="<?php echo base_url('settings'); ?>">
                                        <i class="fa fa-pencil-square-o"></i> Inställningar
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('settings/users'); ?>">
                                        <i class="fa fa-user-plus"></i> Användare
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="<?php echo base_url('settings/groups'); ?>">
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
                                            <span class="caption-subject bold uppercase"> Grupper</span>
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
                                    
                                    <div class="alert alert-success alert-dismissible fade in" id="success_message_container" style="display:none;">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"></button>
                                        <strong>Meddelande!</strong>
                                        <span class="message_content"></span>
                                    </div>

                                    <div class="alert alert-danger alert-dismissible fade in" id="error_message_container" style="display:none;">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"></button>
                                        <strong>Fel!</strong>
                                        <span class="message_content"></span>
                                    </div>
                                    
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn sbold green" data-toggle="modal" data-target="#create_group_modal"> Skapa
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn green  btn-outline dropdown-toggle" data-toggle="dropdown">Verktyg
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right">
                                                            <li>
                                                                <a href="#" id="btn-copy">
                                                                    <i class="fa fa-clipboard"></i> Kopiera 
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" id="btn-excel">
                                                                    <i class="fa fa-file-excel-o"></i> Spara som Excel 
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" id="btn-csv">
                                                                    <i class="fa fa-file-code-o"></i> Spara som CSV 
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" id="btn-pdf">
                                                                    <i class="fa fa-file-pdf-o"></i> Spara som PDF
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="table-container">
											<table class="table table-striped table-bordered table-hover table-checkable" id="groups_table">
												<thead>
													<tr role="row" class="heading">
														<th width="2%">Nr.</th>
														<th width="18%">Namn</th>
														<th width="20%">Beskrivning</th>
														<th width="20%">Alternativ</th>
													</tr>
												</thead>
												<tbody> </tbody>
											</table>
										</div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		
	</div>
    
   <div id="create_group_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Skapa en grupp</h4>
                </div>
                <div class="modal-body">
                    <form id="add_group_form">
                        <div class="form-group">
                            <label for="group_name">Namn:</label>
                            <input id="group_name" class="form-control" type="text" placeholder="Ange ett passande namn" required>
                        </div>
                        <div class="form-group">
                            <label for="group_desc">Beskrivning:</label>
                            <input id="group_desc" class="form-control" type="text" placeholder="Ange en kort beskrivning" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                    <button type="button" id="group_submit_btn" class="btn btn-primary">Spara</button>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        var ajax_url = '<?php echo base_url('Settings/get_groups_ajax');?>';
        var base_url = '<?php echo base_url(); ?>';
    </script>