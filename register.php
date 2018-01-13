<?php
	require_once 'parts.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php printIncludes('Registracija') ?>
</head>
<body>
	<?php includeNavigation() ?>

	<div class="container main-container">
		<?php includeQuoteHeader() ?>

		<div class="row register-tab">
			<div class="col-lg-6 register-motto">
				<div class="register-motto-content text-center">
					<h3>Od studenata za studente</h3>
					<img src="img/students.png" alt="students">
				</div>
			</div>
			<div class="col-lg-6 registration-form-contaienr">
				<h2 class="text-center">Pravljenje naloga</h2>
				<div class="form-result-box"></div>
				<form>
					<input type="hidden" name="formType" value="registrationForm">
					<div class="form-group">
						<label for="">Korisničko ime:</label>
						<input type="text" name="" id="" class="form-control">
						<small class="form-text text-muted">Korisničko ime je vid autentikacije na sajtu.</small>
					</div>
					<div class="form-group">
						<label for="">Email:</label>
						<input type="text" name="" id="" class="form-control">
						<small class="form-text text-muted">U nastavku registracije će Vam na ovaj email stici link za aktivaciju naloga. On takođe igra važnu ulogu u slučaju da zaboravite lozinku.</small>
					</div>
					<div class="form-group">
						<label for="">Lozinka:</label>
						<input type="text" name="" id="" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Ponovite lozinku:</label>
						<input type="text" name="" id="" class="form-control">
						<small class="form-text text-muted">Radi sigurnosti je potrebno da unesete lozinku još jednom.</small>
					</div>
					<input type="submit" class="btn btn-primary float-right" value="Registruj se">
				</form>
			</div>
		</div>
		<?php includeFooter() ?>
	</div>

	<?php includeScripts() ?>
	<script>
		$("form").submit(function(event) {
			event.preventDefault();
			var form = $(this);
			var data = form.serialize();
			var messageBox = $(".form-result-box");
			var output = "";
			$.ajax({
				url: 'formHandler.php',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(result) {
					console.log(result);
					try {
						if(result.length === 0) {
							// POSLATI KORISNIKA NA NEKU STRANICU KOJA MU KAZE DA SE USPESNO REGISTROVAO
						}
						else
							output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.join("<br>") + "</div>";
					}
					catch(err) {
						output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa servevom, molimo pokušajte kasnije!</div>";
					}
				},
				error: function() {
					output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa servevom, molimo pokušajte kasnije!</div>";
				},
				complete: function() {
					messageBox.html(output);
					$('html').animate({scrollTop:0}, 500);
			    $('body').animate({scrollTop:0}, 500);
				}
			});
		});
	</script>
</body>
</html>
