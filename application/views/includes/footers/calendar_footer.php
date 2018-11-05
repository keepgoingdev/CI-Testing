
		<!-- jQuery -->
		<?php echo script_tag('assets/global/plugins/jquery.min.js'); ?>
        <!-- Bootstrap -->
		<?php echo script_tag('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>
        <!-- Cookies -->
		<?php echo script_tag('assets/global/plugins/js.cookie.min.js'); ?>
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
		?>        

    </body>

</html>