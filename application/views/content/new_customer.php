	<div class="container-fluid">
		<div class="page-content">

			<div class="breadcrumbs">
				<h1>Företag</h1>
				<ol class="breadcrumb">
					<li>
						<a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
					</li>
					<li>
						<a href="<?php echo site_url('customers'); ?>">Företag</a>
					</li>
					<li class="active">Skapa företag</li>
				</ol>
			</div>

			<div class="page-content-container">
				<div class="page-content-row">

					<div class="page-sidebar">
						<nav class="navbar">
							<h3><i class="fa fa-tasks"></i> Alternativ</h3>
							<ul class="nav navbar-nav margin-bottom-35">
								<li>
									<a href="<?php echo base_url('customers'); ?>">
									<i class="fa fa-list-alt"></i> Alla företag</a>
								</li>
                                <li class="active">
									<a href="<?php echo base_url('customers/new_customer'); ?>">
									<i class="fa fa-plus"></i> Skapa företag</a>
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
											<span class="caption-subject bold uppercase"> Nytt företag</span>
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
										<div class = "row">
											<div class = "col-lg-5 col-md-12 col-sm-12">
												<?php echo form_open(base_url('customers/new_customer'), array("class" => "form-signin", "id" => "customerform", "name" => "customerform")); ?>
													<div class="form-body">
														
														<div class="form-group">
															<label for="company_name">Företag: * </label>
															<input type="text" id="company_name" class="form-control" name="company_name" placeholder="Företag" value="<?php echo set_value('company_name'); ?>" autocomplete="off" required> 
															<span class="text-danger"><?php echo form_error('company_name'); ?></span>
														</div>

														<div class="form-group">
															<label for="company_location_address">Adress:</label>
		                                                    <input type="text" id="company_location_address" class="form-control" name="company_location_address" placeholder="Adress" value="<?php echo set_value('company_location_address'); ?>" autocomplete="off">
																<span class="text-danger"><?php echo form_error('company_location_address'); ?></span>
														</div>
		                                                
		                                                <div class="form-group">
															<label for="company_location_zip">Postnummer:</label>
		                                                    <input type="text" id="company_location_zip" class="form-control" name="company_location_zip" placeholder="Postnummer" value="<?php echo set_value('company_location_zip'); ?>" autocomplete="off">
																<span class="text-danger"><?php echo form_error('company_location_zip'); ?></span>
														</div>
		                                                
		                                                <div class="form-group">
															<label for="company_location_city">Ort:</label>
		                                                    <input type="text" id="company_location_city" class="form-control" name="company_location_city" placeholder="Ort" value="<?php echo set_value('company_location_city'); ?>" autocomplete="off">
																<span class="text-danger"><?php echo form_error('company_location_city'); ?></span>
														</div>

														<div class="form-group">
															<label for="company_postal_address">Utdelningsadress: *</label>
															<input type="text" id="company_postal_address" class="form-control" name="company_postal_address" placeholder="Utdelningsadress" value="<?php echo set_value('company_postal_address'); ?>" autocomplete="off" required>
															<span class="text-danger"><?php echo form_error('company_postal_address'); ?></span>
														</div>
		                                                
		                                                <div class="form-group">
															<label for="company_postal_zip">Postnummer: *</label>
		                                                    <input type="text" id="company_postal_zip" class="form-control" name="company_postal_zip" placeholder="Postnummer" value="<?php echo set_value('company_postal_zip'); ?>" autocomplete="off" required>
																<span class="text-danger"><?php echo form_error('company_postal_zip'); ?></span>
														</div>
		                                                
		                                                <div class="form-group">
															<label for="company_postal_city">Ort: *</label>
		                                                    <input type="text" id="company_postal_city" class="form-control" name="company_postal_city" placeholder="Ort" value="<?php echo set_value('company_postal_city'); ?>" autocomplete="off" required>
																<span class="text-danger"><?php echo form_error('company_location_city'); ?></span>
														</div>

														<div class="form-group">
															<label for="contact_person">Kontaktperson: *</label>
															<input type="text" id="contact_person" class="form-control" name="contact_person"  placeholder="Kontaktperson" value="<?php echo set_value('contact_person'); ?>" autocomplete="off" required>
															<span class="text-danger"><?php echo form_error('contact_person'); ?></span>
														</div>
		                                                
		                                                <div class="form-group">
															<label for="company_email">E-post:</label>
															<select name="company_email[]" id="company_email" class="form-control select2" multiple>
		                                                    </select>
															<span class="text-danger"><?php echo form_error('company_email'); ?></span>
														</div>
												
														<div class="form-group">
															<label for="company_phone">Telefon:</label>
															<input type="text" id="company_phone" name="company_phone" class="form-control" placeholder="Telefon" value="<?php echo set_value('company_phone'); ?>" autocomplete="off">
															<span class="text-danger"><?php echo form_error('company_phone'); ?></span>
														</div>

														<div class="form-group">
															<label for="company_secondary_phone">Alternativ telefon:</label>
															<input type="text" id="company_secondary_phone" name="company_secondary_phone" class="form-control" placeholder="Alternativ telefon" value="<?php echo set_value('company_secondary_phone'); ?>" autocomplete="off">
															<span class="text-danger"><?php echo form_error('company_secondary_phone'); ?></span>
														</div>                                                        

														<div class="form-group">
															<label for="company_registration">Organisationsnummer: *</label>
															<input type="text" id="company_registration" name="company_registration" class="form-control" placeholder="Organisationsnummer" value="<?php echo set_value('company_registration'); ?>" autocomplete="off" required>
															<span class="text-danger"><?php echo form_error('company_registration'); ?></span>
														</div>
														
														<div class="form-group">
															<label for="company_vat">VAT-nummer:</label>
															<input type="text" id="company_vat" name="company_vat" class="form-control" placeholder="VAT-nummer" value="<?php echo set_value('company_vat'); ?>" autocomplete="off">
															<span class="text-danger"><?php echo form_error('company_vat'); ?></span>
														</div>                                                       
														
														<div class="form-group">
															<label for="company_website">Hemsida</label>
															<input type="text" id="company_website" name="company_website" class="form-control" placeholder="Hemsida" value="<?php echo set_value('company_website'); ?>" autocomplete="off">
															<span class="text-danger"><?php echo form_error('company_website'); ?></span>
														</div>                                                    

														<div class="form-group">
															<label for="number_of_employees">Antal anställda</label>
															<input type="text" id="number_of_employees" name="number_of_employees" class="form-control" placeholder="Antal anställda" value="<?php echo set_value('number_of_employees'); ?>" autocomplete="off">
															<span class="text-danger"><?php echo form_error('number_of_employees'); ?></span>
														</div>
		                                                
		                                                <div class="form-group">
		                                                    <label for="freetext">Anteckningar:</label>
		                                                    <textarea name="freetext" id="freetext" class="form-control"><?php echo set_value('freetext');?></textarea>
		                                                    <span class="text-danger"><?php echo form_error('freetext'); ?></span>
		                                                </div>
													</div>
												<?php echo form_close(); ?>
											</div>

											<div class = "col-lg-7 col-md-12 col-sm-12" id="contact_person_table">
												<div class="table-toolbar">
		                                            <div class="row">
		                                                <div class="col-md-12">
		                                                	<div class = "pull-left caption font-green-seagreen" style = "margin-top: 2em;">
		                                                		<span class="caption-subject bold uppercase">Contact People</span>
		                                                	</div>
		                                                    <div class="btn-group pull-right" style = "margin-top: 2em;">
		                                                        <a class="btn sbold blue" @click="newContactPerson()"> New ContactPerson
		                                                            <i class="fa fa-plus"></i>
		                                                        </a>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        </div>
												<div class="table-container">
													<table class="table table-striped table-bordered table-hover table-checkable">
														<thead>
															<tr class="heading">
																<th style="width:2%;">Nr.</th>
																<th style="width:20%;">Namn</th>
																<th style="width:40%;">Epost</th>
																<th>PhoneNumber</th>
																<th></th>
															</tr>
														</thead>
														<tbody>
															<tr role="row" v-for="(person, index) in contact_people">
																<td> {{ prettyIndex(index) }} </td>
																<td> {{ person.name }} </td>
																<td> {{ person.epost }} </td>
																<td> {{ person.phonenumber }} </td>
																<td class = "text-center">	
																	<div class="btn-group" @click = "onBtnEditClick(index)">
		                                                        		<a class="btn green">
		                                                            		<i class="fa fa-edit edit-button"></i>
		                                                        		</a>
		                                                    		</div> 
																	<div class="btn-group" @click = "onBtnDelClick(index)">
		                                                        		<a class="btn red delete-button">
		                                                            		<i class="fa fa-trash"></i>
		                                                        		</a>
		                                                    		</div>
		                                                		</td>
															</tr>
														</tbody>
													</table>
													<modal v-show="modal_active">
  													</modal>
													<!-- template for the modal component -->
													<script type="text/x-template" id="modal-template">
													  <transition name="modal">
													    <div class="modal-mask">
													      <div class="modal-wrapper">
													        <div class="modal-container">

													          <div class="modal-header">
													            <slot name="header">
													              <h4><strong>Contact Person Add</strong></h4>
													            </slot>
													          </div>

													          <div class="modal-body">
													            <slot name="body">

													            	<div class = "row">
													              		<div class = "col-md-4">
													              			<label>Name:</label>
													              		</div>
													              		<div class = "col-md-4">
													              			<label>EPost:</label>
													              		</div>
													              		<div class = "col-md-4">
													              			<label>PhoneNumber:</label>
													              		</div>
													              	</div>
													              	<div class = "row">
													              		<div class = "col-md-4">
													              			<input type = "text" v-model="edit_data.name" name = "name"/>
													              		</div>
													              		<div class = "col-md-4">
													              			<input type = "text" v-model="edit_data.epost" name = "epost" />
													              		</div>
													              		<div class = "col-md-4">
													              			<input type = "text" v-model="edit_data.phonenumber" name = "phonenumber" />
													              		</div>
													              	</div>

													            </slot>
													          </div>

													          <div class="modal-footer">
													            <slot name="footer">
													              <button class="modal-default-button" @click="onBtnOkClick()">
													                OK
													              </button>
													              <button class="modal-default-button" @click="onBtnCancelClick()" style="margin-right:10px">
													                Cancel
													              </button>
													            </slot>
													          </div>
													        </div>
													      </div>
													    </div>
													  </transition>
													</script>
												</div>
												<div class="form-actions pull-right">
													<a href="<?php echo site_url('customers'); ?>" class="btn red">Avbryt</a>
		                                			<button type="button" class="btn blue" @click="submitForm()">Spara</button>
												</div>
											</div>
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
			//Declare variable showing this page is edit
			var is_edit_page = false;
		</script>