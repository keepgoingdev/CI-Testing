<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
	
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title_part1; ?> | <?php echo $title_part2; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
		<meta content="" name="robots" />
        
		<!-- Google fonts -->
        <link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />        
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <!-- Normalize -->
		<?php echo link_tag('assets/global/plugins/normalize/normalize.min.css'); ?>
        <!-- FontAwesome -->
		<?php echo link_tag('assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>
        <!-- Bootstrap -->
		<?php echo link_tag('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>        
        <!-- Page Specific -->
		<?php echo link_tag('assets/pages/css/frontend.min.css'); ?>
		<!-- Fullscreen modal -->
		<?php echo link_tag('assets/pages/css/bs-fullscreen-modal.min.css'); ?>
        <!-- Favicon -->
        <!--[if IE]><link rel="shortcut icon" href="<?php echo base_url('assets/apps/img/favicon.ico'); ?>"><![endif]-->
        <link rel="icon" href="<?php echo base_url('assets/apps/img/favicon.png'); ?>">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/apps/img/favicon.png'); ?>">
        
        <?php
            if (isset($page_styles))
            {
                if (is_array($page_styles))
                {
                    foreach ($page_styles as $style)
                    {
                        echo link_tag($style);
                    }
                }
            }
        ?>
        
		<!-- IE10 ViewPort Fix -->
		<?php echo script_tag('assets/global/plugins/ie10viewportfix.js'); ?>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<?php echo script_tag('assets/global/plugins/html5shiv.js'); ?>
			<?php echo script_tag('assets/global/plugins/respond.min.js'); ?>
			<?php echo script_tag('assets/global/plugins/excanvas.min.js'); ?>
		<![endif]-->
	</head>
    <body>
        
        <div class="api_wrapper">
            
            <div class="row">

                <div class="col-md-12">
                    <div class="table-responsive">

                        <table id="api_course_event_table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>Kurs</td>
                                    <td>Kurstillfälle</td>
                                    <td>Startdatum</td>
                                    <td>Platser kvar</td>
                                    <td>Pris exkl. moms</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>

                </div>

            </div>
            
        </div>
        
    <!-- Codeigniter to jQuery -->
    <script>
        var base_url = '<?php echo base_url(); ?>';
        var apiKey = <?php echo $permission?>;
        
        <?php
            if (isset($cid))
            {
                if (!empty($cid))
                {
        ?>
                    var ajax_url = '<?php echo site_url('api/api_get_courses/'.$filter.'/'.$permission.'/'.$cid); ?>';
        <?php
                }
                else
                {
        ?>
                    var ajax_url = '<?php echo site_url('api/api_get_courses/'.$filter.'/'.$permission); ?>';
        <?php
                }
            }
        ?>        
    </script>
    
    <!-- jQuery -->
	<?php echo script_tag('assets/global/plugins/jquery.min.js'); ?>
	<!-- Bootstrap -->
	<?php echo script_tag('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>
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