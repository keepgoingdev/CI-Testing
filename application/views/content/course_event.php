<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">
    <div class="page-content">
        <div class="breadcrumbs">
            <h1>Utbildningstillfällen</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo site_url('Dashboard'); ?>">Översikt</a>
                </li>
                <li class="active">Utbildningstillfällen</li>
            </ol>
        </div>

        <div class="page-content-container">
            <div class="page-content-row">    
                <div class="page-sidebar">
                    <nav class="navbar">
                        <h3><i class="fa fa-tasks"></i> Alternativ</h3>

                        <ul class="nav navbar-nav margin-bottom-35">
                            <li class="active">
                                <a href="<?php echo site_url('course_event'); ?>">
                                    <i class="fa fa-list-alt"></i> Utbildiningstillfällen</a>
                            </li>
                            <?php
                                if ($this->auth == 'user' || $this->auth == 'admin' || $this->auth == 'super_admin' || $this->auth == 'extended_teacher')
                                {
                            ?>
                            <li>
                                <a href="<?php echo site_url('course_event/new_course_event'); ?>">
                                    <i class="fa fa-plus"></i> Skapa utbildningstillfälle</a>
                            </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </nav>
                </div>    

                <div class="page-content-col">    
                    <div class="row">
                        <div class="col-md-12">    
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-body">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                    if ($this->auth == 'user' || $this->auth == 'admin' || $this->auth == 'super_admin' || $this->auth == 'extended_teacher')
                                                    {
                                                ?>
                                                <div class="btn-group">
                                                    <a href="<?php echo site_url('course_event/new_course_event'); ?>" class="btn sbold green"> Skapa
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                                <?php
                                                    }
                                                ?>
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
                                        <table class="table table-striped table-bordered table-hover table-checkable" id="course_event_table">
                                            <thead>
                                                <tr class="heading">
                                                    <th style="width:2%;">Nr.</th>
                                                    <th>Kurskod</th>
                                                    <th>Utbildning</th>
                                                    <th>Utbildare</th>
                                                    <th>Adress</th>
                                                    <th>Ort</th>
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

    <div id="create_participant_modal" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Lägg till deltagare</h4>
                </div>
                <div class="modal-body">
                    <form id="add_participant_form">
                        <div id="step_one">
                            <div class="form-group">
                                <div class="mt-radio-list">
                                    <label class="mt-radio">
                                        <input id="option_one" type="radio" checked="" value="opt1" name="optionsRadios">
                                        Jag vill ange personuppgifter
                                    </label>
                                    <label class="mt-radio">
                                        <input id="option_two" type="radio" checked="" value="opt2" name="optionsRadios">
                                        Jag vill endast ange antal deltagare
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="step_two" class = "form-body" style="display:none;">
                            <div class="form-group">
                                <label for="personalnumber">Personnummer: * </label>
                                <input type="text" id="personalnumber" class="form-control" name="personalnumber" autocomplete="off" required>                              
                            </div>
                            <div class="form-group">
                                <div class="checkbox" id="foreign_ssn">
                                    <label><input type="checkbox" name="foreign_ssn" value="1">Deltagaren saknar svenskt personnummer.</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sales_person">Säljare: * </label>
                                <input type="text" id="sales_person" class="form-control" name="sales_person" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Pris: * </label>
                                <input type="text" id="price" class="form-control" name="price" autocomplete="off" required>
                            </div>
                        </div>

                        <div id="step_three" style="display:none;">
                            <div class="form-group">
                                <label for="company1">Företag: *</label>
                                <select id="company1" name="company1" class="form-control" style="width:100%;">
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="contactpeople">ContactPeople: *</label>
                                <select id="contactpeople" name="contactpeople" class="form-control" style="width:100%;">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">Antal deltagare: * </label>
                                <input type="text" id="amount" class="form-control" name="amount" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="sales_person1">Säljare: * </label>
                                <input type="text" id="sales_person1" class="form-control" name="sales_person1" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="price1">Pris: * </label>
                                <input type="text" id="price1" class="form-control" name="price1" autocomplete="off" required>
                            </div>
                        </div>

                        <div id="step_four" style="display:none;">
                            <div class="form-group">
                                <label for="first_name">Förnamn: * </label>
                                <input type="text" id="first_name" class="form-control" name="first_name" autocomplete="off" required>                              
                            </div>
                            <div class="form-group">
                                <label for="last_name">Efternamn: * </label>
                                <input type="text" id="last_name" class="form-control" name="last_name" autocomplete="off" required>                              
                            </div>
                            <div class="form-group">
                                <label for="company2">Företag: *</label>
                                <select id="company2" name="company2" class="form-control" style="width:100%;">                                    
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="phone">Telefon:</label>
                                <input type="text" id="phone" class="form-control" name="phone" placeholder="Telefon" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="email">Epost:</label>
                                <input type="email" id="email" class="form-control" name="email" placeholder="Epost" autocomplete="off">
                            </div>
                        </div>
                        <span id="modal_error_msg" class="text-danger" style="display:none;"></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                    <button type="button" id="participant_submit_btn" class="btn btn-primary">Nästa</button>
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
                    <form id="add_company_form">
                        <div class="form-group">
                            <label for="ccompany_name">Företag: *</label>
                            <input type="text" id="ccompany_name" class="form-control" name="company_name" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="ccompany_reg">Org.nr: *</label>
                            <input type="text" id="ccompany_reg" class="form-control" name="company_reg" placeholder="" autocomplete="off"
                                   >                        </div>
                        <div class="form-group">
                            <label for="ccompany_postal_address">Utdelningsadress: *</label>
                            <input type="text" id="ccompany_postal_address" class="form-control" name="company_postal_address" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="ccompany_postal_zip">Postnummer: *</label>
                            <input type="text" id="ccompany_postal_zip" class="form-control" name="company_postal_zip" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="ccompany_postal_city">Ort: *</label>
                            <input type="text" id="ccompany_postal_city" class="form-control" name="company_postal_city" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="ccontact_person">Kontaktperson: *</label>
                            <input type="text" id="ccontact_person" class="form-control" name="contact_person" placeholder="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="ccompany_email">E-post:</label>
                            <select name="company_email[]" id="ccompany_email" class="form-control select2" multiple style="width:100%;"></select>
                        </div>

                        <span id="modal_error_msg2" class="text-danger" style="display:none;"></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                    <button type="button" id="company_submit_btn" class="btn btn-primary">Nästa</button>
                </div>
            </div>
        </div>
    </div>

    <div id="list_participants_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xxl" style="margin:0px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Deltagare</h4>
                    <p style="font-size:11px;margin-top:5px;"><strong>Datum: </strong><span id="list_date"></span><br>
                        <strong>Typ av utbildning: </strong><span id="list_customized"></span><br>
                        <strong>Antal bokade platser: </strong><span id="list_seats"></span><span id="list_of_max"></span><br>
                        <strong id="list_sum_text">Summa: </strong><span id="list_sum"></span><span id="list_sum_sign">:-</span><br>
                        <strong>Ort: </strong><span id="list_city"></span>
                    </p>
                    <div class="form-group">
                        <label for="mails_sent">Kallelser skickade:</label>
                        <input id="mails_sent" type="checkbox" name="mails_sent" data-id="" value="1" class="form-control"<?php if ($this->auth == 'user' || $this->auth == 'extended_teacher') { echo ' disabled'; } ?>><br>
                        <label for="certdip_sent">Intyg/Certifikat skickade:</label>
                        <input id="certdip_sent" type="checkbox" name="certdip_sent" data-id="" value="1" class="form-control"<?php if ($this->auth == 'user' || $this->auth == 'extended_teacher') { echo ' disabled'; } ?>>
                    </div>
                </div>
                <div class="modal-body">
                    <table id="participants_list_table" class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                            <tr class="heading">                                
                                <th>Namn:</th>
                                <th>Personnummer:</th>
                                <th>Företag:</th>
                                <th>E-post:</th>
                                <th>Verifierad:</th>
                                <th>Kallelese:</th>
                                <th>Intyg:</th>
                                <th>Certifikat:</th>
                                <th>Fakturerad:</th>
                                <th>Pris:</th>
                                <th>Säljare</th>
                                <th>Alternativ:</th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                    <table id="participants_ghost_list_table" class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                            <tr class="heading">                                
                                <th>Företag:</th>
                                <th>ContactPeople</th>
                                <th>Antal platser:</th>
                                <th>Kallelese:</th>
                                <th>Fakturerad:</th>
                                <th>Pris:</th>
                                <th>Säljare</th>
                                <th>Alternativ:</th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success add_company" id="add_company_modal">Skapa företag <i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-primary add_participant" id="add_participant_modal">Lägg till deltagare <i class="fa fa-user-plus"></i></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                </div>
            </div>
        </div>
    </div>

    <div id="participants_ghost_contact_people_list_modal" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Contact People</h4>
                </div>
                <div class="modal-body">
                    <!--
                    <ul id = "modal_ghosts_contact_people_list">
                    </ul>
                    -->
                    <table class = "table table-striped table-bordered table-hover table-checkable" id = "modal_ghosts_contact_people_list">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>E-Mail Address</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var ajax_url= '<?php echo site_url('course_event/get_course_event_ajax');?>';
        var base_url = '<?php echo base_url(); ?>';
        var query = '<?php echo $query; ?>';
        var auth = '<?php echo $this->auth; ?>';
        var user_id = '<?php echo $this->ion_auth->user()->row()->id; ?>';
    </script>


