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
                    <?php
                        if ($readonly)
                        {
                            echo '<li class="active">Visa '.$course->course_name.'</li>';
                        }
                        else
                        {
                            echo '<li class="active">Redigera '.$course->course_name.'</li>';
                        }
                    ?>
                </ol>
            </div>

            <div class="page-content-container">
                <div class="page-content-row">
                    <div class="page-sidebar">
                        <nav class="navbar">
                            <h3><i class="fa fa-tasks"></i> Redigera utbildning</h3>

                            <ul class="nav navbar-nav margin-bottom-35">
                                <li>
                                    <a href="<?php echo site_url('course'); ?>">
                                        <i class="fa fa-list-alt"></i> Alla utbildningar
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('course/new_course'); ?>">
                                        <i class="fa fa-plus"></i> Skapa utbildning
                                    </a>
                                </li>
                                <?php
                                    if ($readonly)
                                    {
                                        echo '<li class="active">
                                            <a href="'.site_url('course/edit_course/'.$course->id).'/true">
                                                <i class="fa fa-eye"></i> Visa utbildning
                                            </a>
                                        </li>';

                                        echo '<li>
                                            <a href="'.site_url('course/edit_course/'.$course->id).'">
                                                <i class="fa fa-pencil"></i> Redigera utbildning
                                            </a>
                                        </li>';
                                    }
                                    else
                                    {
                                        echo '<li>
                                            <a href="'.site_url('course/edit_course/'.$course->id).'/true">
                                                <i class="fa fa-eye"></i> Visa utbildning
                                            </a>
                                        </li>';

                                        echo '<li class="active">
                                            <a href="'.site_url('course/edit_course/'.$course->id).'">
                                                <i class="fa fa-pencil"></i> Redigera utbildning
                                            </a>
                                        </li>';
                                    }
                                ?>
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

                        <br>

                        <div>
                            <?php
                                $api_prefix = $course->id;
                                $api = site_url('api/get_courses/html/newer/1/'.$api_prefix);
                            ?>
                            <b>API:</b><br>
                            <textarea style="width:220px;height:100px;"><?php echo $api; ?></textarea>
                            <code>Läge: html / json</code><br>
                            <code>Urval: all / newer</code><br>
                            <code>Behörighet: 1 = SUU, 2 = Assemblin, 3 = Stena</code><br>
                            <code>Utbildning: <?php echo $api_prefix; ?> = Denna utbildning</code>
                        </div>
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
                                                    echo '<i class="fa fa-eye font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Visa Utbildning</span>';
                                                }
                                                else
                                                {
                                                    echo '<i class="fa fa-pencil font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Redigera Utbildning</span>';
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
                                            echo form_open(base_url('course/edit_course/'.$course->id), array("id" => "editcourse", "name" => "editcourse"));
                                        ?>
                                        <div class="form-body">

                                            <div class="form-group">
                                                <label for="name">Namn: *</label>
                                                <input type="text" id="name" class="form-control" name="course_name" placeholder="Namn på utbildning, t.ex. Arbete på väg Nivå 1" value="<?php echo set_value('course_name', $course->course_name); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_name'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Beskrivning: * </label>
                                                <input id="description" type="text" class="form-control" name="course_description" placeholder="Kort beskrivande text om utbildningen" value="<?php echo set_value('course_description', $course->course_description); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_description'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_from">Tid(ordinarie kursstart): *</label>
                                                <input id="course_time_from" type="text" class="form-control" name="course_time_from" value="<?php echo set_value('course_time_from', substr($course->course_time_from, 0, 5)); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_time_from'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="course_time_end">Tid(ordinarie kursslut): *</label>
                                                <input id="course_time_end" type="text" class="form-control" name="course_time_end" value="<?php echo set_value('course_time_end', substr($course->course_time_end, 0, 5)); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_time_end'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="time">Tid: *</label>
                                                <input id="time" type="text" class="form-control" name="course_time" placeholder="Hur lång tid varar utbildningen? 2 timmar, 1 dag" value="<?php echo set_value('course_time', $course->course_time); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_time'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="external_description">Extern beskrivning:</label>
                                                <textarea id="external_description" class="form-control" name="course_external_description"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>><?php echo set_value('course_external_description', $course->course_external_description); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('course_external_description'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="maximum_participants">Max antal deltagare: *</label>
                                                <input id="maximum_participants" type="text" class="form-control" name="maximum_participants" placeholder="Max antal deltagare" value="<?php echo set_value('maximum_participants', $course->maximum_participants); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('maximum_participants'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="price">Pris: *</label>
                                                <input id="price" type="text" class="form-control" name="course_external_price" placeholder="Pris / Riktpris för utbildningstillfällen under denna utbildning" value="<?php echo set_value('course_external_price', $course->course_external_price); ?>" autocomplete="off" required<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_external_price'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="price_assemblin">Pris (Assemblin):</label>
                                                <input id="price_assemblin" type="text" class="form-control" name="course_external_price_assemblin" placeholder="Specifikt pris för Assemblin" value="<?php echo set_value('course_external_price_assemblin', $course->price_assemblin); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_external_price_assemblin'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="price_stena">Pris (Stena):</label>
                                                <input id="price_stena" type="text" class="form-control" name="course_external_price_stena" placeholder="Specifikt pris för Stena" value="<?php echo set_value('course_external_price_stena', $course->price_stena); ?>" autocomplete="off"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                <span class="text-danger"><?php echo form_error('course_external_price_stena'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="cert_template">Certifikat:</label>
                                                <select id="cert_template" class="form-control select2_type" name="cert_template"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <option value="0"<?php if ($course->cert_template == '0') { echo ' selected';} ?>>Inaktiverat</option>
                                                    <option value="adr13"<?php if ($course->cert_template == 'adr13') { echo ' selected';} ?> data-icon="credit-card-alt">ADR 1.3</option>
                                                    <option value="ams"<?php if ($course->cert_template == 'ams') { echo ' selected';} ?> data-icon="credit-card-alt">AMS</option>
                                                    <option value="apv12"<?php if ($course->cert_template == 'apv12') { echo ' selected';} ?> data-icon="credit-card-alt">APV 1+2</option>
                                                    <option value="apv123a"<?php if ($course->cert_template == 'apv123a') { echo ' selected';} ?> data-icon="credit-card-alt">APV 1+2+3A</option>
                                                    <option value="apv123b"<?php if ($course->cert_template == 'apv123b') { echo ' selected';} ?> data-icon="credit-card-alt">APV 1+2+3B</option>
                                                    <option value="apv3ab"<?php if ($course->cert_template == 'apv3ab') { echo ' selected';} ?> data-icon="credit-card-alt">APV 3A+3B</option>
                                                    <option value="apv3a"<?php if ($course->cert_template == 'apv3a') { echo ' selected';} ?> data-icon="credit-card-alt">APV 3A</option>
                                                    <option value="apv3b"<?php if ($course->cert_template == 'apv3b') { echo ' selected';} ?> data-icon="credit-card-alt">APV 3B</option>
                                                    <option value="esa14"<?php if ($course->cert_template == 'esa14') { echo ' selected';} ?> data-icon="credit-card-alt">ESA 14</option>
                                                    <option value="esa14industri"<?php if ($course->cert_template == 'esa14industri') { echo ' selected';} ?> data-icon="credit-card-alt">ESA 14 Industri & installation</option>
                                                    <option value="esa14tilltrade"<?php if ($course->cert_template == 'esa14tilltrade') { echo ' selected';} ?> data-icon="credit-card-alt">ESA Instruerad person</option>
                                                    <option value="esavattenvagar"<?php if ($course->cert_template == 'esavattenvagar') { echo ' selected';} ?> data-icon="credit-card-alt">ESA Vattenvägar</option>
                                                    <option value="esaroj"<?php if ($course->cert_template == 'esaroj') { echo ' selected';} ?> data-icon="credit-card-alt">ESA Röj</option>
                                                    <option value="elsakerhetbegrelinstall"<?php if ($course->cert_template == 'elsakerhetbegrelinstall') { echo ' selected';} ?> data-icon="credit-card-alt">Elsäkerhets för begränsade elinstallationsarbeten</option>
                                                    <option value="fallskydd"<?php if ($course->cert_template == 'fallskydd') { echo ' selected';} ?> data-icon="credit-card-alt">Fallskydd</option>
                                                    <option value="hjullastare"<?php if ($course->cert_template == 'hjullastare') { echo ' selected';} ?> data-icon="credit-card-alt">Hjullastare</option>
                                                    <option value="kj4115"<?php if ($course->cert_template == 'kj4115') { echo ' selected';} ?> data-icon="credit-card-alt">KJ41:15 Kabelförläggning Utförande</option>
                                                    <option value="lift"<?php if ($course->cert_template == 'lift') { echo ' selected';} ?> data-icon="credit-card-alt">Lift</option>
                                                    <option value="lift3a"<?php if ($course->cert_template == 'lift3a') { echo ' selected';} ?> data-icon="credit-card-alt">Lift 3A</option>
                                                    <option value="liftlur"<?php if ($course->cert_template == 'liftlur') { echo ' selected';} ?> data-icon="credit-card-alt">Lift (LUR)</option>
                                                    <option value="liftlurtorbjornalla"<?php if ($course->cert_template == 'liftlurtorbjornalla') { echo ' selected';} ?> data-icon="credit-card-alt">Lift Torbjörn (LUR) Alla</option>
                                                    <option value="liftfallskyddlur"<?php if ($course->cert_template == 'liftfallskyddlur') { echo ' selected';} ?> data-icon="credit-card-alt">Lift Fallskydd (LUR)</option>
                                                    <option value="stallningallman"<?php if ($course->cert_template == 'stallningallman') { echo ' selected';} ?> data-icon="credit-card-alt">Ställning Allmän</option>
                                                    <option value="stallningsarskild"<?php if ($course->cert_template == 'stallningsarskild') { echo ' selected';} ?> data-icon="credit-card-alt">Ställning Särskild</option>
                                                    <option value="stallningsarvader"<?php if ($course->cert_template == 'stallningsarvader') { echo ' selected';} ?> data-icon="credit-card-alt">Ställning Särskild + Väderskydd</option>
                                                    <option value="sakralyft"<?php if ($course->cert_template == 'sakralyft') { echo ' selected';} ?> data-icon="credit-card-alt">Säkra Lyft</option>
                                                    <option value="travers"<?php if ($course->cert_template == 'travers') { echo ' selected';} ?> data-icon="credit-card-alt">Travers</option>
                                                    <option value="truck"<?php if ($course->cert_template == 'truck') { echo ' selected';} ?> data-icon="credit-card-alt">Truck</option>
                                                    <option value="trucka2a4"<?php if ($course->cert_template == 'trucka2a4') { echo ' selected';} ?> data-icon="credit-card-alt">Truck A2/A4</option>
                                                    <option value="trucka2a4b1"<?php if ($course->cert_template == 'trucka2a4b1') { echo ' selected';} ?> data-icon="credit-card-alt">Truck A2/A4/B1</option>
                                                    <option value="truckb1"<?php if ($course->cert_template == 'truckb1') { echo ' selected';} ?> data-icon="credit-card-alt">Truck B1</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('types'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="diploma_template">Intyg:</label>
                                                <select id="diploma_template" class="form-control select2_type" name="diploma_template"<?php if ($readonly) { echo ' disabled="disabled"'; } ?>>
                                                    <option value="0"<?php if ($course->diploma_template == '0') { echo ' selected';} ?>>Inaktiverat</option>
                                                    <option value="ab04abt06"<?php if ($course->diploma_template == 'ab04abt06') { echo ' selected';} ?> data-icon="file-text">AB 04 och ABT 06</option>
                                                    <option value="abs09"<?php if ($course->diploma_template == 'abs09') { echo ' selected';} ?> data-icon="file-text">ABS 09</option>
                                                    <option value="allmanstallning"<?php if ($course->diploma_template == 'allmanstallning') { echo ' selected';} ?> data-icon="file-text">Allmän Ställning</option>
                                                    <option value="amaanlaggning"<?php if ($course->diploma_template == 'amaanlaggning') { echo ' selected';} ?> data-icon="file-text">AMA Anläggning 17</option>
                                                    <option value="meranlaggning"<?php if ($course->diploma_template == 'meranlaggning') { echo ' selected';} ?> data-icon="file-text">MER Anläggning 17</option>
                                                    <option value="ams"<?php if ($course->diploma_template == 'ams') { echo ' selected';} ?> data-icon="file-text">AMS</option>
                                                    <option value="apv12"<?php if ($course->diploma_template == 'apv12') { echo ' selected';} ?> data-icon="file-text">APV 1+2</option>
                                                    <option value="apv123a"<?php if ($course->diploma_template == 'apv123a') { echo ' selected';} ?> data-icon="file-text">APV 1+2+3A</option>
                                                    <option value="apv123b"<?php if ($course->diploma_template == 'apv123b') { echo ' selected';} ?> data-icon="file-text">APV 1+2+3B</option>
                                                    <option value="apv3a"<?php if ($course->diploma_template == 'apv3a') { echo ' selected';} ?> data-icon="file-text">APV 3A</option>
                                                    <option value="apv3b"<?php if ($course->diploma_template == 'apv3b') { echo ' selected';} ?> data-icon="file-text">APV 3B</option>
                                                    <option value="arbetsmiljoansvar"<?php if ($course->diploma_template == 'arbetsmiljoansvar') { echo ' selected';} ?> data-icon="file-text">Arbetsmiljö och Ansvar</option>
                                                    <option value="atex"<?php if ($course->diploma_template == 'atex') { echo ' selected';} ?> data-icon="file-text">Atex</option>
                                                    <option value="bamansvar"<?php if ($course->diploma_template == 'bamansvar') { echo ' selected';} ?> data-icon="file-text">BAM -Ansvarsutbildning</option>
                                                    <option value="baspu"<?php if ($course->diploma_template == 'baspu') { echo ' selected';} ?> data-icon="file-text">BAS P-U</option>
                                                    <option value="baspugosta"<?php if ($course->diploma_template == 'baspugosta') { echo ' selected';} ?> data-icon="file-text">BAS P-U Gösta</option>
                                                    <option value="digitalfardskrivare"<?php if ($course->diploma_template == 'digitalfardskrivare') { echo ' selected';} ?> data-icon="file-text">Digital färdskrivare</option>
                                                    <option value="elansvar"<?php if ($course->diploma_template == 'elansvar') { echo ' selected';} ?> data-icon="file-text">Elansvarsfrågor</option>
                                                    <option value="elinstskotsel"<?php if ($course->diploma_template == 'elinstskotsel') { echo ' selected';} ?> data-icon="file-text">Elinsts. + Skötsel</option>
                                                    <option value="nyaelinstregler"<?php if ($course->diploma_template == 'nyaelinstregler') { echo ' selected';} ?> data-icon="file-text">Nya Elinstallationsreglerna</option>
                                                    <option value="elinstregler"<?php if ($course->diploma_template == 'elinstregler') { echo ' selected';} ?> data-icon="file-text">Elinstallationsreglerna</option>
                                                    <option value="elinstreglerbravida"<?php if ($course->diploma_template == 'elinstreglerbravida') { echo ' selected';} ?> data-icon="file-text">Elinstallationsreglerna (Bravida)</option>
                                                    <option value="elsakerhetdriftp"<?php if ($course->diploma_template == 'elsakerhetdriftp') { echo ' selected';} ?> data-icon="file-text">Elsäkerhetsutbildning för driftpersonal</option>
                                                    <option value="entrjuridik2"<?php if ($course->diploma_template == 'entrjuridik2') { echo ' selected';} ?> data-icon="file-text">Entreprenadsjuridik II</option>
                                                    <option value="esa14"<?php if ($course->diploma_template == 'esa14') { echo ' selected';} ?> data-icon="file-text">ESA 14</option>
                                                    <option value="esaindustri"<?php if ($course->diploma_template == 'esaindustri') { echo ' selected';} ?> data-icon="file-text">ESA Industri &amp; installation</option>
                                                    <option value="esa14tilltrade"<?php if ($course->diploma_template == 'esa14tilltrade') { echo ' selected';} ?> data-icon="file-text">ESA Instruerad person</option>
                                                    <option value="esavattenvagar"<?php if ($course->diploma_template == 'esavattenvagar') { echo ' selected';} ?> data-icon="file-text">ESA Vattenvägar</option>
                                                    <option value="esaroj"<?php if ($course->diploma_template == 'esaroj') { echo ' selected';} ?> data-icon="file-text">ESA Röj</option>
                                                    <option value="elsakerhetbegrelinstall"<?php if ($course->diploma_template == 'elsakerhetbegrelinstall') { echo ' selected';} ?> data-icon="file-text">Elsäkerhets för begränsade elinstallationsarbeten</option>
                                                    <option value="fallskydd"<?php if ($course->diploma_template == 'fallskydd') { echo ' selected';} ?> data-icon="file-text">Fallskydd</option>
                                                    <option value="forstaforband"<?php if ($course->diploma_template == 'forstaforband') { echo ' selected';} ?> data-icon="file-text">Första Förband inriktning sårskador</option>
                                                    <option value="fliv7"<?php if ($course->diploma_template == 'fliv7') { echo ' selected';} ?> data-icon="file-text">FöretagsLyftet i Väst (7,5 tim)</option>
                                                    <option value="hlrforsta"<?php if ($course->diploma_template == 'hlrforsta') { echo ' selected';} ?> data-icon="file-text">HLR med första hjälpen</option>
                                                    <option value="hlrelskada"<?php if ($course->diploma_template == 'hlrelskada') { echo ' selected';} ?> data-icon="file-text">HLR med El-Skada</option>
                                                    <option value="hlrhjartstartare"<?php if ($course->diploma_template == 'hlrhjartstartare') { echo ' selected';} ?> data-icon="file-text">HLR med hjärtstartare</option>
                                                    <option value="hlrhjart"<?php if ($course->diploma_template == 'hlrhjart') { echo ' selected';} ?> data-icon="file-text">HLR med hjärtstartare och första hjälpen</option>
                                                    <option value="hlr"<?php if ($course->diploma_template == 'hlr') { echo ' selected';} ?> data-icon="file-text">HLR inkl. brandskydd</option>
                                                    <option value="hemocuebasic"<?php if ($course->diploma_template == 'hemocuebasic') { echo ' selected';} ?> data-icon="file-text">HemoCue Grund</option>
                                                    <option value="hemocue"<?php if ($course->diploma_template == 'hemocue') { echo ' selected';} ?> data-icon="file-text">HemoCue</option>
                                                    <option value="instrueradpersskoselel"<?php if ($course->diploma_template == 'instrueradpersskoselel') { echo ' selected';} ?> data-icon="file-text">Instruerad personal och Skötsel av elanläggningar</option>
                                                    <option value="hardplast"<?php if ($course->diploma_template == 'hardplast') { echo ' selected';} ?> data-icon="file-text">Härdplast</option>
                                                    <option value="kfid"<?php if ($course->diploma_template == 'kfid') { echo ' selected';} ?> data-icon="file-text">KFID</option>
                                                    <option value="kfidbravida"<?php if ($course->diploma_template == 'kfidbravida') { echo ' selected';} ?> data-icon="file-text">KFID (Bravida)</option>
                                                    <option value="kj4115"<?php if ($course->diploma_template == 'kj4115') { echo ' selected';} ?> data-icon="file-text">KJ41:15 Kabelförläggning Utförande</option>
                                                    <option value="lastsakring"<?php if ($course->diploma_template == 'lastsakring') { echo ' selected';} ?> data-icon="file-text">Lastsäkring</option>
                                                    <option value="nbrf"<?php if ($course->diploma_template == 'nbrf') { echo ' selected';} ?> data-icon="file-text">Nya Behörighetsreformen</option>
                                                    <option value="olycksfall"<?php if ($course->diploma_template == 'olycksfall') { echo ' selected';} ?> data-icon="file-text">Olycksfall</option>
                                                    <option value="eltekendag"<?php if ($course->diploma_template == 'eltekendag') { echo ' selected';} ?> data-icon="file-text">Praktisk Eltek endagars</option>
                                                    <option value="eltekfastighetsskotare"<?php if ($course->diploma_template == 'eltekfastighetsskotare') { echo ' selected';} ?> data-icon="file-text">Praktisk Eltek för fastighetsskötare</option>
                                                    <option value="projektledning"<?php if ($course->diploma_template == 'projektledning') { echo ' selected';} ?> data-icon="file-text">Projektledning</option>
                                                    <option value="pblbbr"<?php if ($course->diploma_template == 'pblbbr') { echo ' selected';} ?> data-icon="file-text">PBL &amp; BBR</option>
                                                    <option value="eltekva"<?php if ($course->diploma_template == 'eltekva') { echo ' selected';} ?> data-icon="file-text">Praktisk Eltek för mek &amp; VA</option>
                                                    <option value="sip"<?php if ($course->diploma_template == 'sip') { echo ' selected';} ?> data-icon="file-text">SIP</option>
                                                    <option value="skotselelanl"<?php if ($course->diploma_template == 'skotselelanl') { echo ' selected';} ?> data-icon="file-text">Skötsel elanl.</option>
                                                    <option value="skotselelanlbravida"<?php if ($course->diploma_template == 'skotselelanlbravida') { echo ' selected';} ?> data-icon="file-text">Skötsel elanl. (Bravida)</option>
                                                    <option value="stallningsarskild"<?php if ($course->diploma_template == 'stallningsarskild') { echo ' selected';} ?> data-icon="file-text">Ställning Särskild</option>
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
                                                    if ($readonly)
                                                    {
                                                        $opt = 'id="validity" class="form-control select2" disabled="disabled"';
                                                    }
                                                    else
                                                    {
                                                        $opt = 'id="validity" class="form-control select2';
                                                    }
                                                    echo form_dropdown('validity', $options, set_value('validity', $course->validity), $opt);
                                                ?>
                                                <span class="text-danger"><?php echo form_error('validity'); ?></span>
                                            </div>

                                            <?php
                                                $apipermissions = explode(',', $course->apipermissions);
                                            ?>

                                            <div class="form-group">
                                                <label>API Rättigheter (visa hos):</label>
                                                <div class="checkbox">
                                                    <label><input name="apipermissions[]" type="checkbox" value="1"<?php if(in_array('1', $apipermissions)) { echo ' checked';} if ($readonly) { echo ' disabled="disabled"'; } ?>> Svensk Uppdragsutbildning</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="apipermissions[]" type="checkbox" value="2"<?php if(in_array('2', $apipermissions)) { echo ' checked';} if ($readonly) { echo ' disabled="disabled"'; } ?>> Assemblin</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="apipermissions[]" type="checkbox" value="3"<?php if(in_array('3', $apipermissions)) { echo ' checked';} if ($readonly) { echo ' disabled="disabled"'; } ?>> Stena</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email_template">E-postmall för kallelser:</label>
                                                <textarea id="email_template" name="email_template" class="form-control"><?php echo set_value('email_template', $course->email_template); ?></textarea>
                                            </div>

                                            <?php
                                                // if create time is set and valid
                                                if (isset($course->create_time) && !empty($course->create_time) && $course->create_time != '0000-00-00 00:00:00')
                                                {
                                                    if (isset($course->created_by) && !empty($course->created_by) && is_numeric($course->created_by) && $course->created_by > 0)
                                                    {
                                                        echo '<pre><i>Skapad: '.$course->create_time.'</i> av '.$this->ion_auth->user($course->created_by)->row()->first_name.' '.$this->ion_auth->user($course->created_by)->row()->last_name.'<br>';
                                                    }
                                                    else
                                                    {
                                                        echo '<pre><i>Skapad: '.$course->create_time.'</i><br>';
                                                    }
                                                }
                                                else
                                                {
                                                    echo '<pre>';
                                                }

                                                // if edit time is set and valid
                                                if (isset($course->edit_time) && !empty($course->edit_time) && $course->edit_time != '0000-00-00 00:00:00')
                                                {
                                                    if (isset($course->edited_by) && !empty($course->edited_by) && is_numeric($course->edited_by) && $course->edited_by > 0)
                                                    {
                                                        echo '<i>Redigerad: '.$course->edit_time.'</i> av '.$this->ion_auth->user($course->edited_by)->row()->first_name.' '.$this->ion_auth->user($course->edited_by)->row()->last_name;
                                                    }
                                                    else
                                                    {
                                                        echo '<i>Redigerad: '.$course->edit_time.'</i>';
                                                    }

                                                    echo '</pre>';
                                                }
                                                else
                                                {
                                                    echo '</pre>';
                                                }
                                            ?>

                                        </div>

                                            <div class="form-actions">
                                                <a href="<?php echo site_url('course'); ?>" class="btn red">Avbryt</a>
                                                <?php
                                                    if (!$readonly)
                                                    {
                                                        echo '<button type="submit" class="btn blue">Spara</button>';
                                                    }
                                                ?>
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
