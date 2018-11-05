<!DOCTYPE html>
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
        <meta content="Novastream Hosting & Web Solutions AB" name="author" />
        <meta content="" name="description" />
		<meta content="" name="robots" />
		
        <!-- Google fonts -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<!-- Normalize -->
		<?php echo link_tag('assets/global/plugins/normalize/normalize.min.css'); ?>
        <!-- FontAwesome -->
		<?php echo link_tag('assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>
        <!-- Simple Line Icons -->
		<?php echo link_tag('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>
        <!-- Bootstrap -->
		<?php echo link_tag('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>
        <!-- Uniform -->
		<?php echo link_tag('assets/global/plugins/uniform/css/uniform.default.min.css'); ?>
        <!-- Bootstrap Switch -->
		<?php echo link_tag('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>
        <!-- Select2 -->
		<?php echo link_tag('assets/global/plugins/select2/css/select2.min.css'); ?>
        <!-- Select2 for Bootstrap -->
		<?php echo link_tag('assets/global/plugins/select2/css/select2-bootstrap.min.css'); ?>
        <!-- Components -->
		<?php echo link_tag('assets/global/css/components.min.css'); ?>
        <!-- Plugins -->
		<?php echo link_tag('assets/global/css/plugins.min.css'); ?>        
        <!-- Page specific -->
		<?php echo link_tag('assets/pages/css/login-5.min.css'); ?>
		<!-- Favicon -->
        <!--[if IE]><link rel="shortcut icon" href="<?php echo base_url('assets/apps/img/favicon.ico'); ?>"><![endif]-->
        <link rel="icon" href="<?php echo base_url('assets/apps/img/favicon.png'); ?>">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/apps/img/favicon.png'); ?>">
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
	
	<body class="login">