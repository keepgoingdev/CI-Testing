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
                    <?php
                        if ($readonly)
                        {
                            echo '<li class="active">Visa '.$customer->company_name.'</li>';
                        }
                        else
                        {
                            echo '<li class="active">Redigera '.$customer->company_name.'</li>';
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
									<a href="<?php echo site_url('customers'); ?>">
									<i class="fa fa-list-alt"></i> Alla företag</a>
								</li>
                                <li>
									<a href="<?php echo site_url('customers/new_customer'); ?>">
									<i class="fa fa-plus"></i> Skapa företag</a>
								</li>
                                <?php
                                    if ($readonly)
                                    {
                                        echo '<li><a href="'.site_url('customers/edit_customer/'.$customer->id).'">
                                        <i class="fa fa-pencil"></i> Redigera företag</a></li>';

                                        echo '<li class="active"><a href="'.site_url('customers/edit_customer/'.$customer->id).'/true">
                                        <i class="fa fa-eye"></i> Visa företag</a></li>';
                                    }
                                    else
                                    {
                                        echo '<li class="active"><a href="'.site_url('customers/edit_customer/'.$customer->id).'">
                                        <i class="fa fa-pencil"></i> Redigera företag</a></li>';

                                        echo '<li><a href="'.site_url('customers/edit_customer/'.$customer->id).'/true">
                                        <i class="fa fa-eye"></i> Visa företag</a></li>';
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
                                                    echo '<i class="fa fa-eye font-red-sunglo"></i><span class="caption-subject bold uppercase"> Visa företag</span>';
                                                }
                                                else
                                                {
                                                    echo '<i class="fa fa-pencil font-red-sunglo"></i><span class="caption-subject bold uppercase"> Redigera företag</span>';
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
										<div class = "row">
											<div class = "col-lg-5 col-md-12 col-sm-12">
												<?php echo form_open(base_url('customers/edit_customer/'.$customer->id), array("class" => "form-signin", "id" => "customerform", "name" => "customerform")); ?>
												<div class="form-body">
													
													<div class="form-group">
														<label for="company_name">Företag: * </label>
														<input type="text" id="company_name" class="form-control" name="company_name" placeholder="Företag" value="<?php echo set_value('company_name', $customer->company_name); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>> 
														<span class="text-danger"><?php echo form_error('company_name'); ?></span>
													</div>
	                                                
	                                                <input type="hidden" name="cn_org" value="<?php echo $customer->company_name; ?>">

													<div class="form-group">
														<label for="company_location_address">Adress:</label>
	                                                    <input type="text" id="company_location_address" class="form-control" name="company_location_address" placeholder="Adress" value="<?php echo set_value('company_location_address', $customer->company_location_address); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
															<span class="text-danger"><?php echo form_error('company_location_address'); ?></span>
													</div>
	                                                
	                                                <div class="form-group">
														<label for="company_location_zip">Postnummer:</label>
	                                                    <input type="text" id="company_location_zip" class="form-control" name="company_location_zip" placeholder="Postnummer" value="<?php echo set_value('company_location_zip', $customer->company_location_zip); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
															<span class="text-danger"><?php echo form_error('company_location_zip'); ?></span>
													</div>
	                                                
	                                                <div class="form-group">
														<label for="company_location_city">Ort:</label>
	                                                    <input type="text" id="company_location_city" class="form-control" name="company_location_city" placeholder="Ort" value="<?php echo set_value('company_location_city', $customer->company_location_city); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
															<span class="text-danger"><?php echo form_error('company_location_city'); ?></span>
													</div>

													<div class="form-group">
														<label for="company_postal_address">Utdelningsadress: *</label>
														<input type="text" id="company_postal_address" class="form-control" name="company_postal_address" placeholder="Utdelningsadress" value="<?php echo set_value('company_postal_address', $customer->company_postal_address); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('company_postal_address'); ?></span>
													</div>
	                                                
	                                                <div class="form-group">
														<label for="company_postal_zip">Postnummer: *</label>
	                                                    <input type="text" id="company_postal_zip" class="form-control" name="company_postal_zip" placeholder="Postnummer" value="<?php echo set_value('company_postal_zip', $customer->company_postal_zip); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
															<span class="text-danger"><?php echo form_error('company_postal_zip'); ?></span>
													</div>
	                                                
	                                                <div class="form-group">
														<label for="company_postal_city">Ort: *</label>
	                                                    <input type="text" id="company_postal_city" class="form-control" name="company_postal_city" placeholder="Ort" value="<?php echo set_value('company_postal_city', $customer->company_postal_city); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
															<span class="text-danger"><?php echo form_error('company_postal_city'); ?></span>
													</div>

													<div class="form-group">
														<label for="contact_person">Kontaktperson: *</label>
														<input type="text" id="contact_person" class="form-control" name="contact_person" placeholder="Kontaktperson" value="<?php echo set_value('contact_person', $customer->contact_person); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('contact_person'); ?></span>
													</div>
	                                                
	                                                <div class="form-group">
	                                                    <label for="company_email">E-post:</label>
	                                                    <select name="company_email[]" id="company_email" class="form-control select2" multiple<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
	                                                        <?php
	                                                            if (isset($customer->company_email))
	                                                            {
	                                                                $email_addresses = $customer->company_email;
	                                                                $email_addresses = explode(",",$email_addresses);
	                                                                foreach ($email_addresses as $ea)
	                                                                {
	                                                                    echo '<option value="'.$ea.'" selected>'.$ea.'</option>';
	                                                                }
	                                                            }
	                                                        ?>
	                                                    </select>
	                                                    <span class="text-danger"><?php echo form_error('company_email'); ?></span>
	                                                </div>
											
													<div class="form-group">
														<label for="company_phone">Telefon:</label>
														<input type="text" id="company_phone" name="company_phone" class="form-control" placeholder="Telefon" value="<?php echo set_value('company_phone', $customer->company_phone); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('company_phone'); ?></span>
													</div>

													<div class="form-group">
														<label for="company_secondary_phone">Alternativ telefon:</label>
														<input type="text" id="company_secondary_phone" name="company_secondary_phone" class="form-control" placeholder="Alternativ telefon" value="<?php echo set_value('company_secondary_phone', $customer->company_secondary_phone); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('company_secondary_phone'); ?></span>
													</div>                                                        

													<div class="form-group">
														<label for="company_registration">Organisationsnummer: *</label>
														<input type="text" id="company_registration" name="company_registration" class="form-control" placeholder="Organisationsnummer" value="<?php echo set_value('company_registration', $customer->company_registration); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('company_registration'); ?></span>
													</div>
													
													<div class="form-group">
														<label for="company_vat">VAT-nummer:</label>
														<input type="text" id="company_vat" name="company_vat" class="form-control" placeholder="VAT-nummer" value="<?php echo set_value('company_vat', $customer->company_vat); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('company_vat'); ?></span>
													</div>                                                       
													
													<div class="form-group">
														<label for="company_website">Hemsida</label>
														<input type="text" id="company_website" name="company_website" class="form-control" placeholder="Hemsida" value="<?php echo set_value('company_website', $customer->company_website); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('company_website'); ?></span>
													</div>                                                    

													<div class="form-group">
														<label for="number_of_employees">Antal anställda</label>
														<input type="text" id="number_of_employees" name="number_of_employees" class="form-control" placeholder="Antal anställda" value="<?php echo set_value('number_of_employees', $customer->number_of_employees); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
														<span class="text-danger"><?php echo form_error('number_of_employees'); ?></span>
													</div>
	                                                
	                                                <div class="form-group">
	                                                    <label for="freetext">Anteckningar:</label>
	                                                    <textarea name="freetext" id="freetext" class="form-control"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>><?php echo set_value('freetext', $customer->freetext);?></textarea>
	                                                    <span class="text-danger"><?php echo form_error('freetext'); ?></span>
	                                                </div>
	                                                
	                                                <?php													
														 // if create time is set and valid
														 if (isset($customer->create_time) && !empty($customer->create_time) && $customer->create_time != '0000-00-00 00:00:00')
														 {
															 if (isset($customer->created_by) && !empty($customer->created_by) && is_numeric($customer->created_by) && $customer->created_by > 0)
															 {
																 echo '<pre><i>Skapad: '.$customer->create_time.'</i> av '.$this->ion_auth->user($customer->created_by)->row()->first_name.' '.$this->ion_auth->user($customer->created_by)->row()->last_name.'<br>';
															 }
															 else
															 {
																 echo '<pre><i>Skapad: '.$customer->create_time.'</i><br>';
															 }
														 }
														 else
														 {
															 echo '<pre>';
														 }
														 
														 // if edit time is set and valid
														 if (isset($customer->edit_time) && !empty($customer->edit_time) && $customer->edit_time != '0000-00-00 00:00:00')
														 {
															 if (isset($customer->edited_by) && !empty($customer->edited_by) && is_numeric($customer->edited_by) && $customer->edited_by > 0)
															 {
																 echo '<i>Redigerad: '.$customer->edit_time.'</i> av '.$this->ion_auth->user($customer->edited_by)->row()->first_name.' '.$this->ion_auth->user($customer->edited_by)->row()->last_name;
															 }
															 else
															 {
																 echo '<i>Redigerad: '.$customer->edit_time.'</i>';
															 }
															 
															 echo '</pre>';
														 }
														 else
														 {
															 echo '</pre>';
														 }
	                                                ?>																							
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
		                                                	<?php 
	                                                    		if (!$readonly)
	                                                    		{
	                                                    	?>
			                                                    <div class="btn-group pull-right" style = "margin-top: 1em;">
			                                                        <a class="btn sbold blue" @click="newContactPerson()"> Skapa
			                                                            <i class="fa fa-plus"></i>
			                                                        </a>
			                                                    </div>
		                                                    <?php
		                                                    	}
	                                                		?>
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
																<?php 
		                                                    		if (!$readonly)
		                                                    		{
	                                                    		?>
																	<th style="width:130px"></th>
																<?php
																	}
																?>
															</tr>
														</thead>
														<tbody>
															<tr role="row" v-for="(person, index) in contact_people">
																<td> {{ prettyIndex(index) }} </td>
																<td> {{ person.name }} </td>
																<td> {{ person.epost }} </td>
																<td> {{ person.phonenumber }} </td>
																<?php 
		                                                    		if (!$readonly)
		                                                    		{
	                                                    		?>
																	<td class = "text-center">	
																		<div class="btn-group" @click = "onBtnEditClick(index)">
			                                                        		<a class="btn green">
			                                                            		<i class="fa fa-edit"></i>
			                                                        		</a>
			                                                    		</div> 
																		<div class="btn-group" @click = "onBtnDelClick(index)">
			                                                        		<a class="btn red">
			                                                            		<i class="fa fa-trash"></i>
			                                                        		</a>
			                                                    		</div>
			                                                		</td>
			                                                	<?php
			                                                		}
			                                                	?>
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
	                                                <?php 
	                                                    if (!$readonly)
	                                                    {
	                                                        echo "<button type='button' class='btn blue' @click='submitForm()'>Spara</button>";
	                                                    }
	                                                ?>
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
			var is_edit_page = true;
			var customerUrl = "<?php echo site_url('customers'); ?>";
			var customerID = "<?php echo $customer->id; ?>"
		</script>