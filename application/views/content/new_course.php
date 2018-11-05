    <div class="container-fluid">
        <div class="page-content">
            <div class="breadcrumbs">
                <h1>Utbildningar</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>">Översikt</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('course'); ?>">Utbildningar</a>
                    </li>
                    <li class="active">Skapa utbildning</li>
                </ol>
            </div>

            <div class="page-content-container">
                <div class="page-content-row">
                    <div class="page-sidebar">
                        <nav class="navbar">
                            <h3><i class="fa fa-tasks"></i> Alternativ</h3>

                            <ul class="nav navbar-nav margin-bottom-35">
                                <li>
                                    <a href="<?php echo site_url('course'); ?>">
                                        <i class="fa fa-list-alt"></i> Alla utbildningar
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="<?php echo site_url('course/new_course'); ?>">
                                        <i class="fa fa-plus"></i> Skapa utbildning
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
                                            <i class="fa fa-plus font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Ny Utbildning</span>
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
                                            echo form_open(base_url('course/new_course'), array("id" => "newcourse", "name" => "newcourse"));
                                        ?>
                                        <div class="form-body">

                                            <div class="form-group">
                                                <label for="name">Namn: * </label>
                                                <input type="text" id="name" class="form-control" name="course_name" placeholder="Namn på utbildning, t.ex. Arbete på väg Nivå 1" value="<?php echo set_value('course_name'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_name'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Beskrivning: * </label>
                                                <input id="description" type="text" class="form-control" name="course_description" placeholder="Kort beskrivande text om utbildningen" value="<?php echo set_value('course_description'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_description'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_from">Tid(ordinarie kursstart): *</label>
                                                <input id="course_time_from" type="text" class="form-control" name="course_time_from" value="<?php echo set_value('course_time_from'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_date'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_end">Tid(ordinarie kursslut): *</label>
                                                <input id="course_time_end" type="text" class="form-control" name="course_time_end" value="<?php echo set_value('course_time_end'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_time_end'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="time">Tid: *</label>
                                                <input id="time" type="text" class="form-control" name="course_time" placeholder="Hur lång tid varar utbildningen? 2 timmar, 1 dag" value="<?php echo set_value('course_time'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_time'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="external_description">Extern beskrivning:</label>
                                                <textarea id="external_description" class="form-control" name="course_external_description"><?php echo set_value('course_external_description'); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('course_external_description'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="maximum_participants">Max antal deltagare: *</label>
                                                <input id="maximum_participants" type="text" class="form-control" name="maximum_participants" placeholder="Max antal deltagare" value="<?php echo set_value('maximum_participants'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('maximum_participants'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="price">Pris: *</label>
                                                <input id="price" type="text" class="form-control" name="course_external_price" placeholder="Pris / Riktpris för utbildningstillfällen under denna utbildning" value="<?php echo set_value('course_external_price'); ?>" autocomplete="off" required>
                                                <span class="text-danger"><?php echo form_error('course_external_price'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="price_assemblin">Pris (Assemblin):</label>
                                                <input id="price_assemblin" type="text" class="form-control" name="course_external_price_assemblin" placeholder="Specifikt pris för Assemblin" value="<?php echo set_value('course_external_price_assemblin'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('course_external_price_assemblin'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="price_stena">Pris (Stena):</label>
                                                <input id="price_stena" type="text" class="form-control" name="course_external_price_stena" placeholder="Specifikt pris för Stena" value="<?php echo set_value('course_external_price_stena'); ?>" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('course_external_price_stena'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="cert_template">Certifikat:</label>
                                                <select id="cert_template" class="form-control select2_type" name="cert_template">
                                                    <option value="0">Inaktiverat</option>
                                                    <option value="adr13" data-icon="credit-card-alt">ADR 1.3</option>
                                                    <option value="ams" data-icon="credit-card-alt">AMS</option>
                                                    <option value="apv12" data-icon="credit-card-alt">APV 1+2</option>
                                                    <option value="apv123a" data-icon="credit-card-alt">APV1+2+3A</option>
                                                    <option value="apv123b" data-icon="credit-card-alt">APV1+2+3B</option>
                                                    <option value="apv3ab" data-icon="credit-card-alt">APV 3A+3B</option>
                                                    <option value="apv3a" data-icon="credit-card-alt">APV 3A</option>
                                                    <option value="apv3b" data-icon="credit-card-alt">APV 3B</option>
                                                    <option value="esa14" data-icon="credit-card-alt">ESA 14</option>
                                                    <option value="esa14industri" data-icon="credit-card-alt">ESA 14 Industri & installation</option>
                                                    <option value="esa14tilltrade" data-icon="credit-card-alt">ESA Instruerad person</option>
                                                    <option value="esavattenvagar" data-icon="credit-card-alt">ESA Vattenvägar</option>
                                                    <option value="esaroj" data-icon="credit-card-alt">ESA Röj</option>
                                                    <option value="elsakerhetbegrelinstall" data-icon="credit-card-alt">Elsäkerhets för begränsade elinstallationsarbeten</option>
                                                    <option value="fallskydd" data-icon="credit-card-alt">Fallskydd</option>
                                                    <option value="hjullastare" data-icon="credit-card-alt">Hjullastare</option>
                                                    <option value="kj4115" data-icon="credit-card-alt">KJ41:15 Kabelförläggning Utförande</option>
                                                    <option value="lift" data-icon="credit-card-alt">Lift</option>
                                                    <option value="lift3a" data-icon="credit-card-alt">Lift 3A</option>
                                                    <option value="liftlur" data-icon="credit-card-alt">Lift (LUR)</option>
                                                    <option value="liftlurtorbjornalla" data-icon="credit-card-alt">Lift Torbjörn (LUR) Alla</option>
                                                    <option value="liftfallskyddlur" data-icon="credit-card-alt">Lift Fallskydd (LUR)</option>
                                                    <option value="stallningallman" data-icon="credit-card-alt">Ställning Allmän</option>
                                                    <option value="stallningsarskild" data-icon="credit-card-alt">Ställning Särskild</option>
                                                    <option value="stallningsarvader" data-icon="credit-card-alt">Ställning Särskild + Väderskydd</option>
                                                    <option value="sakralyft" data-icon="credit-card-alt">Säkra Lyft</option>
                                                    <option value="travers" data-icon="credit-card-alt">Travers</option>
                                                    <option value="truck" data-icon="credit-card-alt">Truck</option>
                                                    <option value="trucka2a4" data-icon="credit-card-alt">Truck A2/A4</option>
                                                    <option value="trucka2a4b1" data-icon="credit-card-alt">Truck A2/A4/B1</option>
                                                    <option value="truckb1" data-icon="credit-card-alt">Truck B1</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('types'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="diploma_template">Intyg:</label>
                                                <select id="diploma_template" class="form-control select2_type" name="diploma_template">
                                                    <option value="0">Inaktiverat</option>
                                                    <option value="ab04abt06" data-icon="file-text">AB 04 och ABT 06</option>
                                                    <option value="abs09" data-icon="file-text">ABS 09</option>
                                                    <option value="allmanstallning" data-icon="file-text">Allmän Ställning</option>
                                                    <option value="amaanlaggning" data-icon="file-text">AMA Anläggning 17</option>
                                                    <option value="meranlaggning" data-icon="file-text">MER Anläggning 17</option>
                                                    <option value="ams" data-icon="file-text">AMS</option>
                                                    <option value="apv12" data-icon="file-text">APV 1+2</option>
                                                    <option value="apv123a" data-icon="file-text">APV1+2+3A</option>
                                                    <option value="apv123b" data-icon="file-text">APV1+2+3B</option>
                                                    <option value="apv3a" data-icon="file-text">APV 3A</option>
                                                    <option value="apv3b" data-icon="file-text">APV 3B</option>
                                                    <option value="arbetsmiljoansvar" data-icon="file-text">Arbetsmiljö och Ansvar</option>
                                                    <option value="atex" data-icon="file-text">Atex</option>
                                                    <option value="bamansvar" data-icon="file-text">BAM -Ansvarsutbildning</option>
                                                    <option value="baspu" data-icon="file-text">BAS P-U</option>
                                                    <option value="baspugosta" data-icon="file-text">BAS P-U Gösta</option>
                                                    <option value="digitalfardskrivare" data-icon="file-text">Digital färdskrivare</option>
                                                    <option value="elansvar" data-icon="file-text">Elansvarsfrågor</option>
                                                    <option value="elinstskotsel" data-icon="file-text">Elinsts. + Skötsel</option>
                                                    <option value="nyaelinstregler" data-icon="file-text">Nya Elinstallationsreglerna</option>
                                                    <option value="elinstregler" data-icon="file-text">Elinstallationsreglerna</option>
                                                    <option value="elinstreglerbravida" data-icon="file-text">Elinstallationsreglerna (Bravida)</option>
                                                    <option value="elsakerhetdriftp" data-icon="file-text">Elsäkerhetsutbildning för driftpersonal</option>
                                                    <option value="entrjuridik2" data-icon="file-text">Entreprenadsjuridik II</option>
                                                    <option value="esa14" data-icon="file-text">ESA 14</option>
                                                    <option value="esaindustri" data-icon="file-text">ESA Industri &amp; installation</option>
                                                    <option value="esa14tilltrade" data-icon="file-text">ESA Instruerad person</option>
                                                    <option value="esavattenvagar" data-icon="file-text">ESA Vattenvägar</option>
                                                    <option value="esaroj" data-icon="file-text">ESA Röj</option>
                                                    <option value="elsakerhetbegrelinstall" data-icon="file-text">Elsäkerhets för begränsade elinstallationsarbeten</option>
                                                    <option value="fallskydd" data-icon="file-text">Fallskydd</option>
                                                    <option value="forstaforband" data-icon="file-text">Första Förband inrikting sårskador</option>
                                                    <option value="fliv7" data-icon="file-text">FöretagsLyftet i Väst (7,5 tim)</option>
                                                    <option value="hlrforsta" data-icon="file-text">HLR med första hjälpen</option>
                                                    <option value="hlrelskada" data-icon="file-text">HLR med El-Skada</option>
                                                    <option value="hlrhjartstartare" data-icon="file-text">HLR med hjärtstartare</option>
                                                    <option value="hlrhjart" data-icon="file-text">HLR med hjärtstartare och första hjälpen</option>
                                                    <option value="hlr" data-icon="file-text">HLR inkl. brandskydd</option>
                                                    <option value="hemocuebasic" data-icon="file-text">HemoCue Grund</option>
                                                    <option value="hemocue" data-icon="file-text">HemoCue</option>
                                                    <option value="instrueradpersskoselel" data-icon="file-text">Instruerad personal och Skötsel av elanläggningar</option>
                                                    <option value="hardplast" data-icon="file-text">Härdplast</option>
                                                    <option value="kfid" data-icon="file-text">KFID</option>
                                                    <option value="kfidbravida" data-icon="file-text">KFID (Bravida)</option>
                                                    <option value="kj4115" data-icon="file-text">KJ41:15 Kabelförläggning Utförande</option>
                                                    <option value="lastsakring" data-icon="file-text">Lastsäkring</option>
                                                    <option value="nbrf" data-icon="file-text">Nya Behörighetsreformen</option>
                                                    <option value="olycksfall" data-icon="file-text">Olycksfall</option>
                                                    <option value="eltekendag" data-icon="file-text">Praktisk Eltek endagars</option>
                                                    <option value="projektledning" data-icon="file-text">projektledning</option>
                                                    <option value="pblbbr" data-icon="file-text">PBL &amp; BBR</option>
                                                    <option value="eltekfastighetsskotare" data-icon="file-text">Praktisk Eltek för fastighetsskötare</option>
                                                    <option value="eltekva" data-icon="file-text">Praktisk Eltek för mek &amp; VA</option>
                                                    <option value="sip" data-icon="file-text">SIP</option>
                                                    <option value="skotselelanl" data-icon="file-text">Skötsel elanl.</option>
                                                    <option value="skotselelanlbravida" data-icon="file-text">Skötsel elanl. (Bravida)</option>
                                                    <option value="stallningsarskild" data-icon="file-text">Ställning Särskild</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('types'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="validity">Giltighetstid:</label>
                                                <?php
                                                    $options = array(
                                                        '0' => 'Inaktiverad',
                                                        '1' => '1 år',
                                                        '2' => '2 år',
                                                        '3' => '3 år',
                                                        '4' => '4 år',
                                                        '5' => '5 år',
                                                        '6' => '6 år',
                                                        '7' => '7 år',
                                                        '8' => '8 år',
                                                        '9' => '9 år',
                                                        '10' => '10 år',
                                                    );
                                                    echo form_dropdown('validity', $options, set_value('validity'), 'id="validity" class="form-control select2"');
                                                ?>
                                                <span class="text-danger"><?php echo form_error('personalnumber'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label>API Rättigheter (visa hos):</label>
                                                <div class="checkbox">
                                                    <label><input name="apipermissions[]" type="checkbox" value="1" checked> Svensk Uppdragsutbildning</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="apipermissions[]" type="checkbox" value="2"> Assemblin</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="apipermissions[]" type="checkbox" value="3"> Stena</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email_template">E-postmall för kallelser:</label>
                                                <textarea id="email_template" name="email_template" class="form-control"><?php echo set_value('email_template', $email_template); ?></textarea>
                                            </div>

                                            <div class="form-actions">
                                                <a href="<?php echo site_url('course'); ?>" class="btn red">Avbryt</a>
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
