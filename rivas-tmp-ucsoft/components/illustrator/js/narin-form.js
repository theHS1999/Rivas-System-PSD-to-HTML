var Narin = {
validateForm:
	function() {
		var valid = true;
		$(this).find('.validate').each(function() {
			if (!Narin.validateField($(this)))
				valid = false;
		});
		$(this).find('.date_picker').each(function() {
			if (!Narin.validateDate($(this)))
				valid = false;
		});
		return valid;
	}
,
validateField:
	function(jqField) {
		if ($.trim(jqField.val()) === '' && !jqField.hasClass('match')) {
			if (jqField.hasClass('required')) {
				jqField.addClass('invalid');
				return false;
			}
			else {
				jqField.removeClass('invalid');
				return true;
			}
		}
		var valid = true;
		for (var v in Narin.validations) {
			if (jqField.hasClass(v) && !Narin.validations[v].call(this, jqField)) {
				jqField.addClass('invalid');
				jqField.next('.description').show();
				valid = false;
			}
		}
		if (valid) {
			jqField.removeClass('invalid');
		}
		return valid;
	}
,
validateDate:
	function(jqDatePicker) {
		var d = parseInt(jqDatePicker.children('.date_d').val(), 10);
		var m = parseInt(jqDatePicker.children('.date_m').val(), 10);
		var y = jqDatePicker.children('.date_y').val();
		if (!jqDatePicker.hasClass('required') && d === 0 && m === 0 && $.trim(y) === '') {
			jqDatePicker.removeClass('invalid');
			return true;
		}
		if (d === 0 || m === 0 || !(/^[1-9]\d{3}$/).test(y) || (y = parseInt(y, 10)) > 9377 || (m > 6 && d === 31) || (d === 30 && m === 12 && (((((((y - 474) % 2820) + 474) + 38) * 682) % 2816) < 682))) {
			if (!jqDatePicker.hasClass('required'))
				return true;
			jqDatePicker.addClass('invalid');
			return false;
		}
		jqDatePicker.removeClass('invalid');
		return true;
	}
,
validations:
	{
		email: function(jqf){return (/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/).test(jqf.val());},
		integer: function(jqf){var v = jqf.val(), n = parseInt(v, 10); return (/^\d+$/).test(v) && (typeof (mn = jqf.data('num_min')) === 'undefined' || (jqf.data('num_min_inc') ? n >= mn : n > mn)) && (typeof (mx = jqf.data('num_max')) === 'undefined' || (jqf.data('num_max_inc') ? n <= mx : n < mx));},
		decimal: function(jqf){var v = jqf.val(), n = parseFloat(v); return (/^\d*\.?\d*$/).test(v) && v != '' && v != '.' && (typeof (mn = jqf.data('num_min')) === 'undefined' || (jqf.data('num_min_inc') ? n >= mn : n > mn)) && (typeof (mx = jqf.data('num_max')) === 'undefined' || (jqf.data('num_max_inc') ? n <= mx : n < mx));},
		farsi_word: function(jqf){return (/^[\u200C ضصثقفغعهخحجچپشسیيبلاتنمکكگظطزرذدئوآةيژؤإأءۀًٌٍَُِّ]+$/).test(jqf.val());},
		farsi_text: function(jqf){return (/^[-\u200C\d\s۰۱۲۳۴۵۶۷۸۹÷=.\/×!@#$%^&*)(_+،؛,\\}{|ـ«»:"<>؟ضصثقفغعهخحجچپشسیيبلاتنمکكگظطزرذدئوآةيژؤإأءۀًٌٍَُِّ\[\]]+$/).test(jqf.val());},
		minlength: function(jqf){return jqf.val().length >= jqf.data('minlength');},
		maxlength: function(jqf){return jqf.val().length <= jqf.data('maxlength');},
		match: function(jqf){return jqf.val() === $('#' + jqf.data('match')).val();},
		first_opt_not_allowed: function(jqf){return jqf.prop('selectedIndex') != 0;},
		mobile: function(jqf){return (/^0[1-9]\d{9}$/).test(jqf.val());},
		tel: function(jqf){return (/^0[1-9]\d{1,3}-[1-9]\d{6,7}$/).test(jqf.val());},
		ssn: function(jqf){return (/^\d{10}$/).test(jqf.val());},
		postnum: function(jqf){return (/^\d{10}$/).test(jqf.val());},
		fixedlen_num: function(jqf){var d = jqf.data('digits'), re = jqf.data('first_digit_not_zero') ? '^[1-9]\\d{' + (d - 1) + '}$' : '^\\d{' + d + '}$'; return (new RegExp(re)).test(jqf.val());},
		regexp: function(jqf){return (new RegExp(jqf.data('regexp'))).test(jqf.val());}
	}
,
checkTextareaMaxlength:
	function() {
		var maxlen = $(this).data('maxlength');
		var jqMaxlength = $(this).next('.textarea_maxlength');
		var remchars = maxlen - this.value.length;
		if (remchars >= 0) {
			jqMaxlength.text(remchars + ' کارکتر از حداکثر ' + maxlen + ' کارکتر باقی مانده').removeClass('textarea_maxlength_overflow');
		}
		else {
			jqMaxlength.text((-remchars) + ' کارکتر بیش از حداکثر ' + maxlen + ' کارکتر وارد شده').addClass('textarea_maxlength_overflow');
		}
		return true;
	}
};

$.fn.autoWidth = function (options) {
	var settings = {limitWidth: false};
	if (options)
		$.extend(settings, options);
	var maxWidth = 0;
	this.css('display', 'inline');
	this.each(function () {
		if ($(this).width() > maxWidth) {
			if (settings.limitWidth && maxWidth >= settings.limitWidth)
				maxWidth = settings.limitWidth;
			else
				maxWidth = $(this).width();
		}
	});
	this.css({display: 'inline-block', width: maxWidth + 5});
};

$(document).ready(function() {
	var form = $('form.narin');
	form.submit(Narin.validateForm);
	form.find('textarea.maxlength').keypress(Narin.checkTextareaMaxlength).keydown(Narin.checkTextareaMaxlength).keyup(Narin.checkTextareaMaxlength).blur(Narin.checkTextareaMaxlength).triggerHandler('blur');
	form.find('label:not(input[type="checkbox"]+label,input[type="radio"]+label,.labelbr)').autoWidth();
});