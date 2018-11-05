        <p class="copyright"><?php echo date('Y'); ?> ©
			<a href="http://www.svenskuppdragsutbildning.com" title="Besök Svensk Uppdragsutbildnings hemsida" target="_blank">Svensk uppdragsutbildning.</a>
		</p>
		<a href="#index" class="go2top">
			<i class="icon-arrow-up"></i>
		</a>

        <div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="faqModalLabel">FAQ</h4>
                    </div>
                    <div class="modal-body">        
                        <iframe src="<?php echo site_url('faq'); ?>" class="faqIframe"></iframe> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                    </div>
                </div>
            </div>
        </div><!-- ./faqModal -->

        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="reportModalLabel">Rapporter</h4>
                    </div>
                    <div class="modal-body">        
                        <div class="form-group">
                            <label for="report_from">Datum (från): *</label>
                            <input id="report_from" type="text" class="form-control" name="report_from" value="" autocomplete="off" required>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="report_to">Datum (till): *</label>
                            <input id="report_to" type="text" class="form-control" name="report_to" value="" autocomplete="off" required>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="report_type">Typ av rapport: *</label>
                            <select id="report_type" name="report_type" class="form-control">
                                <option value="course">Standard (kategorisera efter kurs)</option>
                                <option value="city">Ort (kategorisera efter ort)</option>
                                <option value="county">Län (kategorisera efter län)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="report_city">Ort:</label>
                            <select id="report_city" name="report_city" class="form-control">
                                <option value="-1">Ort</option>
                                <?php
                                    foreach($report_cities as $rcities)
                                    {
                                        if (!empty($rcities->city))
                                        {
                                            echo '<option value="'.$rcities->city.'">'.$rcities->city.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="report_county">Län:</label>
                            <select id="report_county" name="report_county" class="form-control">
                                <option value="-1">Län</option>
                                <?php
                                    foreach($report_counties as $rcounties)
                                    {
                                        if (!empty($rcounties->county))
                                        {
                                            echo '<option value="'.$rcounties->county.'">'.$rcounties->county.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <?php 
                            if ($this->auth == 'super_admin' || $this->auth == 'admin')
                            {
                        ?>
                        <div class="form-group">
                            <label for="report_economy">Ekonomi:</label>
                            <input id="report_economy" type="checkbox" name="report_economy" value="1" class="form-control">
                            <span class="text-danger"></span>
                        </div>
                        <?php 
                            }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="generate_report" class="btn btn-success">Generera rapport</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                    </div>
                </div>
            </div>
        </div><!-- ./reportModal -->
		
	</div><!-- ./container-fluid -->

</div><!-- ./wrapper -->

		<!-- jQuery -->
		<?php echo script_tag('assets/global/plugins/jquery.min.js'); ?>
        <!-- Bootstrap -->
		<?php echo script_tag('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>
        <!-- Cookies -->
		<?php echo script_tag('assets/global/plugins/js.cookie.min.js'); ?>
        <!-- Bootstrap Hover Dropdown -->
		<?php echo script_tag('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>
        <!-- Slimscroll -->
		<?php echo script_tag('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>
        <!-- BlockUI -->
		<?php echo script_tag('assets/global/plugins/jquery.blockui.min.js'); ?>
        <!-- Uniform -->
		<?php echo script_tag('assets/global/plugins/uniform/jquery.uniform.min.js'); ?>
		
        <!-- Page Specific -->
		<?php
			if(isset($page_scripts))
			{
                if (is_array($page_scripts))
                {
                    foreach($page_scripts as $script)
                    {
                        echo script_tag($script);
                    }
                }
			}
            if (isset($gmaps))
            {
                echo '<!-- Google Maps -->', PHP_EOL;
                echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDc1a4oBiCiY5k23tKVSXjkfmIDs3ECuM&libraries=places&region=se&callback=initAutocomplete" async defer></script>', PHP_EOL;
            }
		?>

        <!-- Theme Specific -->
        <?php echo script_tag('assets/global/scripts/app.min.js'); ?>
        <?php echo script_tag('assets/global/scripts/vue.js'); ?>
        <?php echo script_tag('assets/layouts/layout5/scripts/layout.min.js'); ?>

    </body>

</html>