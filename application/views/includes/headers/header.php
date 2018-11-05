<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="sv">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title_part1 ?> | <?php echo $title_part2 ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Labbmiljö" name="author" />
        <meta content="" name="description" />
		<meta content="" name="robots" />
		
		<!-- Google fonts -->
        <link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<!-- Normalize -->
		<?php echo link_tag('assets/global/plugins/normalize/normalize.min.css'); ?>
		<!-- Font Awesome -->
		<?php echo link_tag('assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>
        <!-- Simple Line Icons -->
		<?php echo link_tag('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>
        <!-- Bootstrap -->
		<?php echo link_tag('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>
        <!-- Uniform -->
		<?php echo link_tag('assets/global/plugins/uniform/css/uniform.default.min.css'); ?>
        <!-- Components -->		
		<?php echo link_tag('assets/global/css/components.min.css'); ?>
        <!-- Plugins -->		
		<?php echo link_tag('assets/global/css/plugins.min.css'); ?>
        <!-- Layout -->
		<?php echo link_tag('assets/layouts/layout5/css/layout.min.css'); ?>
        <!-- Theme override -->
		<?php echo link_tag('assets/global/css/override.min.css'); ?>        
        <!-- Favicon -->
        <!--[if IE]><link rel="shortcut icon" href="<?php echo base_url('assets/apps/img/favicon.ico'); ?>"><![endif]-->
        <link rel="icon" href="<?php echo base_url('assets/apps/img/favicon.png'); ?>">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/apps/img/favicon.png'); ?>">

        <!--Custom StyleSheet-->
        <?php echo link_tag('assets/global/css/vueCustom.css'); ?>
        
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
		<![endif]-->
        
        <!-- Google Analytics -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-99338070-1', 'auto');
          ga('send', 'pageview');

        </script>
        
	</head>