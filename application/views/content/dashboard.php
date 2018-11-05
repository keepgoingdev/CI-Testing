<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

	<div class="page-content">
		
		<div class="breadcrumbs">
			<h1>Översikt</h1>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('dashboard'); ?>">Hem</a>
				</li>
				<li class="active">Översikt</li>
			</ol>
		</div>
        
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        
        <div class="page-content-container">
            <div class="page-content-row">
                <div class="page-content-col">
                    <div class="row">
                        <div class="col-md-12 ">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	</div>
    
    <div id="course_event_info" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div id="mcontent"></div>
                    <h4>Utbildare</h4>
                    <div id="teacherwrap">
                        <table id="ttable" class="table table-striped table-bordered table-hover table-checkable">
                            <thead>
                                <tr class="heading">                                
                                    <th>Namn:</th>
                                    <th>Telefon:</th>
                                    <th>Epost:</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                    <h4>Deltagare</h4>
                    <div id="participantwrap">
                        <table id="ptable" class="table table-striped table-bordered table-hover table-checkable">
                            <thead>
                                <tr class="heading">                                
                                    <th>Namn:</th>
                                    <th>Personnummer:</th>
                                    <th>Företag:</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                    <h4>Företag</h4>
                    <div id="ghostwrap">
                        <table id="gtable" class="table table-striped table-bordered table-hover table-checkable">
                            <thead>
                                <tr class="heading">                                
                                    <th>Företag:</th>
                                    <th>Antal platser:</th>
                                    <th>Telefon:</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                    <a id="view_course_event" href="#" class="btn btn-primary">Gå till utbildning <i class="fa fa-search"></i></a>
                    <a id="start_course_event" href="#" target="_blank" class="btn btn-success">Starta utbildning <i class="fa fa-play"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        var ajax_url= '<?php echo site_url('login/get_course_events');?>';
        var base_url = '<?php echo base_url(); ?>';
        var auth = '<?php echo $this->auth; ?>';
    </script>