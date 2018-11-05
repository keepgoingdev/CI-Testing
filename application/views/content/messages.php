	<div class="container-fluid">
		<div class="page-content">
		
			<div class="breadcrumbs">
				<h1>Meddelande</h1>
				<ol class="breadcrumb">
					<li>
						<a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
					</li>
					<li class="active">Meddelande</li>
				</ol>
			</div>
			
			<div class="page-content-container">
				<div class="page-content-row">
					<div class="page-content-col">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light portlet-fit portlet-datatable bordered">
									<div class="portlet-body">
								        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    
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
											<table class="table table-striped table-bordered table-hover table-checkable" id="messages_table" style="width:100%;">
												<thead>
													<tr class="heading">
														<th style="width:2%;">Nr.</th>
														<th>Titel</th>
                                                        <th>Meddelande</th>
														<th>Datum</th>
														<th>Alternativ</th>
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

        <script type="text/javascript">
            var ajax_url = '<?php echo base_url('messages/get_messages_ajax');?>';
            var base_url = '<?php echo base_url(); ?>';
        </script>