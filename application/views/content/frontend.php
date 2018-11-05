<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h2 style="text-align:center;"><?php echo $course_data->course_name; ?></h2>
        </div>

    </div>

    <div style="padding-top:20px;padding-bottom:20px;"></div>

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <form id="main_form" method="post" action="">

                <div class="form-body">

                    <div id="step_1">
                        
                        <div class="form-group">

                            <label>Personnummer: *</label>
                            <input type="text" id="personalnumber" name="personalnumber" class="form-control" placeholder="" required autofocus autocomplete="off">

                        </div>
                        
                        <div class="checkbox" id="accept_gdpr">
                            <label><input type="checkbox" value="1">Jag godkänner att mina personuppgifter behandlas enligt dataskyddsförordningen (GDPR). Läs mer om vår <a href="http://work-place.se/om-oss/integritetspolicy/" target="_blank">integritetspolicy.</a></label>
                        </div>

                        <div class="checkbox" id="no_personalnumber">
                            <label><input type="checkbox" value="1">Jag saknar svenskt personnummer.</label>
                        </div>
                        
                    </div>

                    <div id="step_2" style="display:none;">

                        <div class="form-group">

                            <label>Företag: *</label>
                            <select name="company_id" id="company" class="form-control select2" style="width:100%;">
                                <option value="">Välj företag</option>
                                <?php 
                                foreach($relevant_companies as $rc)
                                {
                                    echo '<option value="'.$rc->id.'">'.$rc->company_name.'</option>';
                                }
                                ?>
                            </select>

                            <br>
                            <a href="#" id="add_company">Lägg till företag</a>

                        </div>

                        <div class="form-group">

                            <label>Förnamn: *</label>
                            <input type="text" id="first_name" class="form-control" placeholder="" name="first_name" required autocomplete="off">

                        </div>

                        <div class="form-group">

                            <label>Efternamn: *</label>
                            <input type="text" id="last_name" class="form-control" placeholder="" name="last_name" required autocomplete="off">

                        </div>

                        <div class="form-group">

                            <label>E-post:</label>
                            <input type="email" id="email" class="form-control" placeholder="" name="email" autocomplete="off">

                        </div>
                        
                        <div class="form-group">

                            <label>Telefon: *</label>
                            <input type="text" id="phone" class="form-control" placeholder="" name="phone" required autocomplete="off">

                        </div>

                    </div>

                </div>

                <div style="padding-top:20px;padding-bottom:20px;"></div>

                <input type="hidden" id="event_id" name="event_id" value="<?php echo $course_event_data->id; ?>">
                <input type="hidden" id="participant_id" name="participant_id" value="">
                <input type="hidden" id="foreign_ssn" name="foreign_ssn" value="0">

                <div class="form-actions">
                    <button type="button" id="reset_btn" class="btn btn-success btn-register next_btn"><i class="fa fa-arrow-left"></i> Börja om</button>
                    <button type="button" id="next_btn" class="btn btn-success btn-register next_btn" disabled>Nästa <i class="fa fa-arrow-right"></i></button>
                </div>

            </form>

        </div>

    </div>

    <div style="padding-top:50px;"></div>

    <div class="row">

        <div class="col-md-4 col-md-offset-4">
            <img class="login-logo" src="<?php echo base_url('assets/apps/img/logo.png'); ?>" alt="Labbmiljö" />
        </div>

    </div>

</div>

<div id="add_company_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Lägg till företag</h4>
            </div>
            <div class="modal-body">
                <form id="add_company_form">
                    <div class="form-group">
                        <label for="company_reg">Org.nr: </label>
                        <input type="text" id="company_reg" class="form-control" name="company_reg" placeholder="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="company_amount">Antal platser: </label>
                        <input type="text" id="company_amount" class="form-control" name="company_amount" placeholder="" autocomplete="off">
                    </div>
                    <div id="no_company" class="checkbox">
                        <label>
                            <input value="1" type="checkbox">
                            Jag tillhör inget företag
                        </label>
                    </div>
                    <input type="hidden" id="company_event_id" name="company_event_id" value="<?php echo $course_event_data->id; ?>">
                    <span id="modal_error_msg1" class="text-danger" style="display:none;"></span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                <button type="button" id="company_submit_btn_first" class="btn btn-primary">Nästa</button>
            </div>
        </div>
    </div>
</div>

<div id="create_company_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Lägg till företag</h4>
            </div>
            <div class="modal-body">
                <form id="create_company_form">
                    <div class="form-group">
                        <label for="ccompany_name">Företag: *</label>
                        <input type="text" id="ccompany_name" class="form-control" name="ccompany_name" placeholder="" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="ccompany_reg">Org.nr: *</label>
                        <input type="text" id="ccompany_reg" class="form-control" name="ccompany_reg" placeholder="" required autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label for="ccompany_postal_address">Utdelningsadress: *</label>
                        <input type="text" id="ccompany_postal_address" class="form-control" name="ccompany_postal_address" placeholder="" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="ccompany_postal_zip">Postnummer: *</label>
                        <input type="text" id="ccompany_postal_zip" class="form-control" name="ccompany_postal_zip" placeholder="" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="ccompany_postal_city">Ort: *</label>
                        <input type="text" id="ccompany_postal_city" class="form-control" name="ccompany_postal_city" placeholder="" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="ccontact_person">Kontaktperson: *</label>
                        <input type="text" id="ccontact_person" class="form-control" name="ccontact_person" placeholder="" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="ccompany_email">E-post:</label>
                        <input type="text" id="ccompany_email" class="form-control" name="ccompany_email" placeholder="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="ccompany_phone">Telefon: *</label>
                        <input type="text" id="ccompany_phone" class="form-control" name="ccompany_phone" placeholder="" required autocomplete="off">
                    </div>
                    
                    <input type="hidden" id="ccompany_event_id" name="ccompany_event_id" value="<?php echo $course_event_data->id; ?>">
                    <input type="hidden" id="ccompany_amount" name="ccompany_amount" value="">

                    <span id="modal_error_msg2" class="text-danger" style="display:none;"></span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                <button type="button" id="company_submit_btn_second" class="btn btn-primary">Slutför</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>