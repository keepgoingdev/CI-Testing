		<!-- jQuery -->
		<?php echo script_tag('assets/global/plugins/jquery.min.js'); ?>
		<!-- Bootstrap -->
		<?php echo script_tag('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>
		<!-- Select2 -->
		<?php echo script_tag('assets/global/plugins/select2/js/select2.min.js'); ?>
        <?php echo script_tag('assets/global/plugins/select2/js/i18n/sv.js'); ?>
		<!-- Page specific -->
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
        ?>
    </body>
</html>