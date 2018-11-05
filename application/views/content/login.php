<div class="user-login-5">
	<div class="row bs-reset">
		<div class="col-md-8 hidden-sm hidden-xs bs-reset mt-login-5-bsfix">
			<div class="login-bg" style="background-image:url(<?php echo base_url('assets/pages/img/login/bg1.jpg'); ?>)">
			</div>
		</div>
		<div class="col-md-4 login-container bs-reset mt-login-5-bsfix">
			<img class="login-logo" src="<?php echo base_url('assets/apps/img/logo.png'); ?>" />
			<div class="login-content">
				<h1>Logga in</h1>
				<p>Här kan du logga in i systemet.</p>
				<p><a id="course_login_link" href="#">Klicka här</a> för att starta en utbildning.</p>
					<?php echo form_open(base_url('login'), array("class" => "login-form", "id" => "loginform", "name" => "loginform"));?>
					<div class="alert alert-danger display-hide">
						<button class="close" data-close="alert"></button>
						<span>Ange ditt användarnamn och lösenord.</span>
					</div>
					<?php echo $this->session->flashdata('msg'); ?>
					<div class="row">
						<div class="col-xs-6">
							<input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Användarnamn" name="username" required/> 
						</div>
						<div class="col-xs-6">
							<input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Lösenord" name="password" required/> </div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="rem-password">
								<label class="rememberme mt-checkbox mt-checkbox-outline">
									<input type="checkbox" name="remember" value="1" /> Kom ihåg mig
									<span></span>
								</label>
							</div>
						</div>
						<div class="col-sm-8 text-right">
							<div class="forgot-password">
								<a href="javascript:;" id="forget-password" class="forget-password">Glömt ditt lösenord?</a>
							</div>
							<button class="btn green" type="submit">Logga in</button>
						</div>
					</div>
				</form>

				<form class="forget-form" action="javascript:;" method="post">
					<h3 class="font-green">Glömt ditt lösenord?</h3>
					<p>Kontakta ansvarig för att återställa ditt lösenord.</p>
						<div class="form-actions">
							<button type="button" id="back-btn" class="btn green btn-outline">Tillbaka</button>
						</div>
				<?php echo form_close(); ?>

			</div>
			
			<div class="login-footer">
				<div class="row bs-reset">
					<div class="col-xs-5 bs-reset">
						<ul class="login-social">
							<li>
								<a href="javascript:;">
									<i class="icon-social-facebook"></i>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<i class="icon-social-twitter"></i>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<i class="icon-social-dribbble"></i>
								</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-7 bs-reset">
						<div class="login-copyright text-right">
							<p>Copyright &copy; Svensk Uppdragsutbildning <?php echo date('Y'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>