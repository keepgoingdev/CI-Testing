<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

	<link rel="stylesheet" href="<?php echo base_url('assets/pages/faq/css/reset.css'); ?>">
	<!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo base_url('assets/pages/faq/css/style.css'); ?>">
	<!-- Resource style -->
	<script src="<?php echo base_url('assets/pages/faq/js/modernizr.js'); ?>"></script>
	<!-- Modernizr -->
	<title><?php echo $title_part1 ?> | <?php echo $title_part2 ?></title>
</head>

<body>
	<section class="cd-faq">
		<ul class="cd-faq-categories">
			<li>
				<a class="selected" href="#info">Info</a>
			</li>
			<li>
				<a href="#utbildningsdag">Utbildningsdag</a>
			</li>
			<li>
				<a href="#utbildning">Bokning av utbildningar</a>
			</li>
			<li>
				<a href="#hotell">Hotell och resor</a>
			</li>
			<li>
				<a href="#kontaktpersoner">Kontaktpersoner</a>
			</li>
		</ul>
		<!-- cd-faq-categories -->

		<div class="cd-faq-items">
			<ul id="info" class="cd-faq-group">
				<li class="cd-faq-title">
					<h2>Info</h2>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Inloggningsuppgifter</a>
					<div class="cd-faq-content">
						<p>Ni får inloggning till deltagarlistan. Här kan ni se information om era utbildningar. T.ex. adress, antal deltagare,
							om och när materialet har skickats, kurskod, slut- och starttid.
						</p>
						<br>
						<p>På utbildningsdagen registrerar deltagarna sig på deltagarlistan. En del utbildare skickar runt en platta som deltagarna
							använder och en del låter deltagarna komma fram och registrera sig på datorn.</p>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Översikt</a>
					<div class="cd-faq-content">
						<p>När ni loggat in kommer ni till en översikt över alla våra aktiva utbildningar.</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/oversikt.png'); ?>" alt="Översikt">
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Inforuta</a>
					<div class="cd-faq-content">
						<p>Om man trycker på en utbildning så kommer det upp en inforuta.</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/inforuta.png'); ?>" alt="Inforuta">
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Färgkodning</a>
					<div class="cd-faq-content">
						<p>Från början är utbildningarna gula. De blir efterhand grönmarkerade, första delen grön betyder att lokal är bokad,
							andra delen att material är skickat, tredje delen att kallelse är skickad och när den sista biten är grön är intyg
							skickade.
						</p>
						<br>
						<p>De röda utbildningarna betyder att de är inställda.</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/fargkodning.png'); ?>" alt="Färgkodning">
					</div>
					<!-- cd-faq-content -->
				</li>
			</ul>
			<!-- cd-faq-group -->

			<ul id="utbildningsdag" class="cd-faq-group">
				<li class="cd-faq-title">
					<h2>Utbildningsdag</h2>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Starta utbildning, inte inloggad</a>
					<div class="cd-faq-content">
						<p>Tryck på "Klicka här" för att starta en utbildning</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/starta_ej_inloggad.png'); ?>" alt="Ej inloggad">
						<br>
						<br>
						<hr>
						<p>Klistra in kurskod och tryck "Logga in".</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/starta_utbildning.png'); ?>" alt="Starta utbildning">
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Starta utbildning, inloggad</a>
					<div class="cd-faq-content">
						<p>Via inforutan trycker du på knappen "Starta utbildning".</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/starta_inloggad.png'); ?>" alt="Starta utbildning som inloggad användare">
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Registrera användare</a>
					<div class="cd-faq-content">
						<p>Deltagaren skriver in sitt personnummer. Deltagaren kommer till en sida där man fyller i namn med mera.
							<br>Finns man redan i systemet så finns redan informationen.</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/registrera_deltagare.png'); ?>" alt="Registrera deltagare">
						<br>
						<br>
						<hr>
						<p>Deltagaren väljer från vilket företag hen kommer ifrån. Det är endast de företag som är anmälda som finns i listan
							över företag.</p>
						<br>
						<img src="<?php echo base_url('assets/pages/faq/img/registrera_deltagare_info.png'); ?>" alt="Info registrerade deltagare">
						<br>
						<br>
						<hr>
						<p>Glöm inte att deltagarens telefonnummer är obligatoriskt.</p>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Problem vid registrering</a>
					<div class="cd-faq-content">
						<p>Skulle man under utbildningsdagen stöta på problem vid registrering så är det bara att höra av sig till IT-avdelningen
							eller Admin. Det går även bra att maila in information om deltagarna i efterhand. Det som behövs då är namn, personnummer,
							företag och telefonnummer.</p>
					</div>
					<!-- cd-faq-content -->
				</li>
			</ul>
			<!-- cd-faq-group -->

			<ul id="utbildning" class="cd-faq-group">
				<li class="cd-faq-title">
					<h2>Bokning av utbildningar</h2>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Rutiner vid bokning</a>
					<div class="cd-faq-content">
						<p>Säljaren kontaktar alltid utbildaren först för att stämma av tid, plats och om utbildaren behöver förhandskontakta
							kunden och om utbildaren har möjlighet att genomföra utbildningen.</p>
						<br>
						<p>Säljarna kan även prelimiminärboka en utbildning. Hör man som utbildare inte av säljaren inom 14 dagar kan man som
							utbildare stryka utbildningen från sin egen kalender om man inte gjort någon annan överenskommelse med säljaren.</p>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Inställda utbildningar</a>
					<div class="cd-faq-content">
						<p>När en utbildning ställs in går det alltid ut ett mail till utbildaren från Admin.</p>
					</div>
					<!-- cd-faq-content -->
				</li>
			</ul>
			<!-- cd-faq-group -->

			<ul id="hotell" class="cd-faq-group">
				<li class="cd-faq-title">
					<h2>Hotell och resor</h2>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Bokningar</a>
					<div class="cd-faq-content">
						<p>
							Man bokar resor och hotell själv men skulle det någon gång behövas så är det bara att höra av sig till Veronica på Admin.
							Man brukar kunna hitta skapliga hotell för under 1 200:- så vi brukar försöka hålla oss där under.</p>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Kostnad och arvode</a>
					<div class="cd-faq-content">
						<p>
							Kostnader för hotell och resor kan faktureras tillsammans med arvodet. Vi har avtal med hotellkedjorna First hotell och Ligula.
							Lättast är det att ringa hotellen och boka och då uppge Nya Svensk Uppdragsutbildning. Avtalet ger 10% på logi.
						</p>
					</div>
					<!-- cd-faq-content -->
				</li>
			</ul>
			<!-- cd-faq-group -->

			<ul id="kontaktpersoner" class="cd-faq-group">
				<li class="cd-faq-title">
					<h2>Kontaktpersoner</h2>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Allmänna frågor</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Växel</td>
								<td>
									<a href="tel:0451706900">0451-70 69 00</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Frågor om deltagarlistan</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Jim Friberg</td>
								<td>
									<a href="tel:0768905818">0768-90 58 18</a>
								</td>
							</tr>
							<tr>
								<td>Veronica Ekman</td>
								<td>
									<a href="tel:0451706906">0451-70 69 06</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Ekonomi och faktureringsfrågor</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Rosmari Ekman</td>
								<td>
									<a href="tel:0451706900">0451-70 69 00</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Konsultavtal etc.</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Kristoffer Lundström</td>
								<td>
									<a href="tel:0761753337">0761-75 33 37</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Uppdatering av utbildningsmaterial</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Jim Friberg</td>
								<td>
									<a href="tel:0768905818">0768-90 58 18</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Information om utbildningstillfällen</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Veronica Ekman</td>
								<td>
									<a href="tel:0451706906">0451-70 69 06</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Registrering av deltagare</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Jim Friberg</td>
								<td>
									<a href="tel:0768905818">0768-90 58 18</a>
								</td>
							</tr>
							<tr>
								<td>Veronica Ekman</td>
								<td>
									<a href="tel:0451706906">0451-70 69 06</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Intyg</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Veronica Ekman</td>
								<td>
									<a href="tel:0451706906">0451-70 69 06</a>
								</td>
							</tr>
							<tr>
								<td>Christer Bodén</td>
								<td>
									<a href="tel:0451706903">0451-70 69 03</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Heta Arbeten</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Veronica Ekman</td>
								<td>
									<a href="tel:0451706906">0451-70 69 06</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
				<li>
					<a class="cd-faq-trigger" href="#0">Hotell och resor</a>
					<div class="cd-faq-content">
						<table style="width:100%">
							<tr>
								<td>Veronica Ekman</td>
								<td>
									<a href="tel:0451706906">0451-70 69 06</a>
								</td>
							</tr>
						</table>
					</div>
					<!-- cd-faq-content -->
				</li>
			</ul>
			<!-- cd-faq-group -->
		</div>
		<!-- cd-faq-items -->
		<a href="#0" class="cd-close-panel">Close</a>
	</section>
	<!-- cd-faq -->

	<script src="<?php echo base_url('assets/pages/faq/js/jquery-2.1.1.js'); ?>"></script>
	<script src="<?php echo base_url('assets/pages/faq/js/jquery.mobile.custom.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/pages/faq/js/main.js'); ?>"></script>
	<!-- Resource jQuery -->
</body>

</html>