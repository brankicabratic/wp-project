$('input:not([type="submit"])').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});

$('a[href*="#"]').on('click', function (e) {
	e.preventDefault();

  console.log($($(this).attr('href')).offset().top - 60);

	$('html, body').animate({
		scrollTop: $($(this).attr('href')).offset().top - 60
	}, 500, 'linear');
});
