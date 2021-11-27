function closeCardAlert(nome) {
	$(".card-alert-" + nome + "").fadeOut("slow");
}

function findAncestor(element, cls) {
	while ((element = element.parent()) && !element.hasClass(cls));
	return element;
}

function insertErrorsOnForm(p_form, erros) {
	let fields = Object.getOwnPropertyNames(erros);
	var form = $("form[name='" + p_form + "']");
	if (form.length > 0) {
		fields.forEach(function (c) {
			var element = form.find("[name='" + c + "']");
			if (element.length > 0)
				insertErrorsOnInput(element, erros[c]);
		});
	}
	$('div.form-group > input.underlined, div.form-group > select.underlined').focus(function () {
		removeSuccessMessage($(this));
		removeErrorMessage($(this));
	});
}

function inserErrorOnForm(p_form, element, error) {
	var form = $("form[name='" + p_form + "']");
	if (form.length > 0) {
		var element = form.find("[name='" + element + "']");
		if (element.length > 0)
			insertErrorOnInput(element, error);
	}
	$('div.form-group > input.underlined, div.form-group > select.underlined').focus(function () {
		removeSuccessMessage($(this));
		removeErrorMessage($(this));
	});
}

function insertSuccessOnInput(p_element, msg) {
	if (p_element.length > 0) {
		if (!p_element.hasClass('underlined'))
			p_element.addClass('underlined');
		var ancestor = findAncestor(p_element, 'form-group');
		if (!ancestor.hasClass('has-success'))
			ancestor.addClass('has-success');
		ancestor.find('span.has-success').remove();
		if (Array.isArray(msg)) {
			msg.forEach(function (item) {
				p_element.parent().append('<span class="has-success">' + item + '</span>');
			});
		} else {
			p_element.parent().append('<span class="has-success">' + msg + '</span>');
		}
		$('div.form-group > input.underlined, div.form-group > select.underlined').focus(function () {
			removeSuccessMessage($(this));
			removeErrorMessage($(this));
		});
	}
}

function insertErrorOnInput(p_element, erro) {
	if (p_element.length > 0) {
		if (!p_element.hasClass('underlined'))
			p_element.addClass('underlined');
		var ancestor = findAncestor(p_element, 'form-group');
		if (!ancestor.hasClass('has-error'))
			ancestor.addClass('has-error');
		ancestor.find('span.has-error').remove();
		if (Array.isArray(erro)) {
			erro.forEach(function (item) {
				p_element.parent().append('<span class="has-error">' + item + '</span>');
			});
		} else {
			p_element.parent().append('<span class="has-error">' + erro + '</span>');
		}
		$('div.form-group > input.underlined, div.form-group > select.underlined').focus(function () {
			removeSuccessMessage($(this));
			removeErrorMessage($(this));
		});
	}
}

function insertErrorsOnInput(p_element, erros) {
	if (p_element.length > 0) {
		if (!p_element.hasClass('underlined'))
			p_element.addClass('underlined');
		var ancestor = findAncestor(p_element, 'form-group');
		if (!ancestor.hasClass('has-error'))
			ancestor.addClass('has-error');
		ancestor.find('span.has-error').remove();
		if (Array.isArray(erros)) {
			erros.forEach(function (item) {
				p_element.parent().append('<span class="has-error">' + item + '</span>');
			});
		} else {
			p_element.parent().append('<span class="has-error">' + erros + '</span>');
		}
	}
}

function removeSuccessMessage(p_element) {
	if (p_element.length > 0) {
		p_element.removeClass('underlined');
		var ancestor = findAncestor(p_element, 'form-group');
		ancestor.find('span.has-success').remove();
		ancestor.removeClass('has-success');
	}
}

function removeErrorMessage(p_element) {
	if (p_element.length > 0) {
		p_element.removeClass('underlined');
		var ancestor = findAncestor(p_element, 'form-group');
		ancestor.find('span.has-error').remove();
		ancestor.removeClass('has-error');
	}
}

$('seletordali').on('click', function () {
	$(this).toggleClass('classe')
});

// scroll to top
var scrollToTopCorrec = function () {
	document.getElementById('app').scrollIntoView();
};
