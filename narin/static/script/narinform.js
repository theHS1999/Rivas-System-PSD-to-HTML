/* Copyright (C) Rivas System, Inc. All rights reserved. */
(function($){
	window.NarinForm = new function() {
		var forms = {};
		this.add = function(id, controls) {
			forms[id] = controls;
		};
		$(document).ready(function(){
			$('.narinform form').submit(function(e){
				var form = $(this);
				var controls = forms[form.attr('id')];
				var controlName, controlWrapper;
				function defined(v) {
					return typeof v !== 'undefined';
				}
				function blank(s) {
					return !/[^\s]/.test(s);
				}
				function comparedt(dt1, dt2) {
					for (var i = 0; i < dt1.length; i++) {
						if (dt1[i] > dt2[i])
							return 1;
						if (dt1[i] < dt2[i])
							return -1;
					}
					return 0;
				}
				function validate() {
					var control = controls[controlName];
					controlWrapper = $();
					switch (control.type) {
						case 'text':
							var input = form.find('[name="' + controlName + '"]');
							controlWrapper = input.closest('p');
							var val = input.val();
							if (blank(val))
								return !control.required;
							switch (control.validation) {
								case 'email':
									return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val);
								case 'url':
									return /^https?:\/\/[^\s\/]+\.[^\s\/]+(\/.*)?$/i.test(val);
								case 'farsi-word':
									return /^[پضًصٌثٍقفغعهةخحجچشَسُیِبّلاآتـنمکگظطزيركذإدأئءوؤژ ‌]+$/.test(val);
								case 'farsi-text':
									return /^[پ~1۱!2۲@3۳#4۴$5۵%6۶^7۷&8۸*9۹)0۰(\-_=+ضًصٌثٍقف،غ؛ع,هةخ×ح÷ج}چ{شَسُیِبّل<اآتـن«م»ک:گ"ظ\]ط[زيركذإدأئءوؤ.>\/؟ژ\\\s‌]+$/.test(val);
								case 'integer':
									val = Number(val);
									return !isNaN(val) && val % 1 == 0 && (!defined(control.min) || val >= control.min) && (!defined(control.max) || val <= control.max);
								case 'decimal':
									val = Number(val);
									return !isNaN(val) && (!defined(control.min) || (control.min_inc ? (val >= control.min) : (val > control.min))) && (!defined(control.max) || (control.max_inc ? (val <= control.max) : (val < control.max)));
								case 'mobile':
									return /^09[0-9]{9}$/.test(val);
								case 'tel':
									return val.length == 12 && /^0[1-9][0-9]{1,3}-[1-9][0-9]*$/.test(val);
								case 'postal-code':
								case 'ssn':
									return val.length == 10 && /^[0-9]*[1-9][0-9]*$/.test(val);
								case 'pbn':
									return val.length <= 10 && /^[0-9]*[1-9][0-9]*$/.test(val);
								case 'num-code':
									return (!defined(control.digits) || val.length == control.digits) && (control.no_leading_zero ? /^[1-9][0-9]*$/ : /^[0-9]+$/).test(val);
								case 'regexp':
									return new RegExp(control.regexp, control.case_insensitive ? 'i' : '').test(val);
							}
							return true;
						case 'textarea':
							var input = form.find('[name="' + controlName + '"]');
							controlWrapper = input.closest('p');
							var val = input.val();
							if (blank(val))
								return !control.required;
							if (val.length > (defined(control.max_len) ? control.max_len : 65535))
								return false;
							switch (control.validation) {
								case 'farsi-text':
									return /^[پ~1۱!2۲@3۳#4۴$5۵%6۶^7۷&8۸*9۹)0۰(\-_=+ضًصٌثٍقف،غ؛ع,هةخ×ح÷ج}چ{شَسُیِبّل<اآتـن«م»ک:گ"ظ\]ط[زيركذإدأئءوؤ.>\/؟ژ\\\s‌]+$/.test(val);
								case 'regexp':
									return new RegExp(control.regexp, control.case_insensitive ? 'i' : '').test(val);
							}
							return true;
						case 'radio':
							var inputs = form.find('[name="' + controlName + '"]');
							controlWrapper = inputs.closest('div');
							if (!inputs.filter(':checked').length)
								return !control.required;
							return true;
						case 'select':
						case 'select-slave':
							var input = form.find('[name="' + controlName + '"]');
							controlWrapper = input.closest('p');
							if (input.val() === '')
								return !control.required;
							return true;
						case 'checkbox':
							var input = form.find('[name="' + controlName + '"]');
							controlWrapper = input.closest('p');
							return !control.required || input.is(':checked');
						case 'checkbox-array':
							var inputs = form.find('[name="' + controlName + '[]"]');
							controlWrapper = inputs.closest('div');
							var checked = inputs.filter(':checked').length;
							return (!defined(control.min) || checked >= control.min) && (!defined(control.max) || checked <= control.max);
						case 'date':
							var inputy = form.find('[name="' + controlName + '[y]"]');
							controlWrapper = inputy.closest('p');
							controlWrapper.children('.error').remove();
							var y = inputy.val(), m = form.find('[name="' + controlName + '[m]"]').val(), d = form.find('[name="' + controlName + '[d]"]').val();
							if (blank(y) && m === '' && d === '')
								return !control.required;
							var yn = Number(y), mn = Number(m), dn = Number(d);
							if (blank(y) || m === '' || d === '' || isNaN(yn) || yn < 1000 || yn > 9377 || (dn == 31 && mn > 6)) {
								controlWrapper.prepend('<span class="error">تاریخ نادرست است.</span>');
								return false;
							}
							if ((defined(control.from_value) && comparedt([yn, mn, dn], control.from_value) < 0) || (defined(control.to_value) && comparedt([yn, mn, dn], control.to_value)) > 0) {
								controlWrapper.prepend('<span class="error">تاریخ در بازه‌ی مجاز نیست.</span>');
								return false;
							}
							return true;
						case 'time':
							var inputh = form.find('[name="' + controlName + '[h]"]');
							controlWrapper = inputh.closest('p');
							var h = inputh.val(), n = form.find('[name="' + controlName + '[n]"]').val();
							if (h === '' && n === '')
								return !control.required;
							return h !== '' && n !== '';
						case 'datetime':
							var inputy = form.find('[name="' + controlName + '[y]"]');
							controlWrapper = inputy.closest('p');
							controlWrapper.children('.error').remove();
							var y = inputy.val(), m = form.find('[name="' + controlName + '[m]"]').val(), d = form.find('[name="' + controlName + '[d]"]').val(), h = form.find('[name="' + controlName + '[h]"]').val(), n = form.find('[name="' + controlName + '[n]"]').val();
							if (blank(y) && m === '' && d === '' && h === '' && n === '')
								return !control.required;
							var yn = Number(y), mn = Number(m), dn = Number(d), hn = Number(h), nn = Number(n);
							if (blank(y) || m === '' || d === '' || h === '' || n === '' || isNaN(yn) || yn < 1000 || yn > 9377 || (dn == 31 && mn > 6)) {
								controlWrapper.prepend('<span class="error">زمان نادرست است.</span>');
								return false;
							}
							if ((defined(control.from_value) && comparedt([yn, mn, dn, hn, nn], control.from_value) < 0) || (defined(control.to_value) && comparedt([yn, mn, dn, hn, nn], control.to_value)) > 0) {
								controlWrapper.prepend('<span class="error">زمان در بازه‌ی مجاز نیست.</span>');
								return false;
							}
							return true;
						case 'file':
							var input = form.find('[name="' + controlName + '"]');
							controlWrapper = input.closest('p');
							controlWrapper.children('.error').remove();
							var val = input.val();
							if (blank(val))
								return !control.required;
							if (defined(control.valid_exts) && $.inArray(val.substr(val.lastIndexOf('.') + 1), control.valid_exts.split(';')) == -1) {
								controlWrapper.prepend('<span class="error">فرمت فایل مجاز نیست.</span>');
								return false;
							}
							return true;
					}
					return true;
				}
				(function(){
					if (typeof controls !== 'object')
						return;
					var valid = true;
					for (controlName in controls) {
						if (controls.hasOwnProperty(controlName)) {
							if (validate()) {
								controlWrapper.removeClass('invalid');
							}
							else {
								controlWrapper.addClass('invalid');
								valid = false;
							}
						}
					}
					var captcha = form.find('[name="_captcha_"]');
					if (captcha.length) {
						if (blank(captcha.val())) {
							captcha.closest('p').addClass('invalid');
							valid = false;
						}
						else {
							captcha.closest('p').removeClass('invalid');
						}
					}
					valid || e.preventDefault();
				})();
			});
			function checkTextareaMaxlen() {
				var textarea = $(this);
				var maxlen = textarea.data('maxlength');
				var rem = maxlen - textarea.val().length;
				var msgcont = textarea.parent().parent().children('.textarea-maxlen');
				if (rem < 0)
					msgcont.text((-rem) + ' کارکتر بیش از حداکثر ' + maxlen + ' کارکتر استفاده شده').addClass('exceeded');
				else
					msgcont.text(rem + ' کارکتر از حداکثر ' + maxlen + ' کارکتر باقی مانده').removeClass('exceeded');
			}
			$('.narinform form textarea[data-maxlength]').on({change: checkTextareaMaxlen, keydown: checkTextareaMaxlen, keyup: checkTextareaMaxlen}).change();
			$('.narinform form .captcha a[href="#new"]').click(function(e){
				e.preventDefault();
				var img = $(this).siblings('img');
				var src = img.attr('src');
				img.attr('src', src.substr(0, src.lastIndexOf('&r=') + 3) + Math.random());
			});
			$('.narinform .message.success .print button').click(function(){
				$(this).closest('.narinform-refid').printThis();
			});
			$('.narinform form').each(function(){
				var form = $(this);
				(function(){
					var cols = form.data('cols') || 1;
					if (cols === 1) {
						return;
					}
					var colWidth = Math.floor(form.width() / cols);
					var elements = form.children(':not(input,h1,p.buttons)');
					var elementsHeaderAdded = [];
					if (elements[0].tagName !== 'H2') {
						var h = document.createElement('H2');
						$(h).text(form.children('h1').text());
						elementsHeaderAdded.push(h);
					}
					for (i = 0; i < elements.length; i++) {
						elementsHeaderAdded.push(elements[i]);
						$(elements[i]).remove();
					}
					var sections = [];
					var sectionsIndex = -1;
					for (i = 0; i < elementsHeaderAdded.length; i++) {
						if (elementsHeaderAdded[i].tagName === 'H2') {
							sections[++sectionsIndex] = [];
						}
						sections[sectionsIndex].push(elementsHeaderAdded[i]);
					}
					var colsHeight = [];
					for (i = 0; i < cols; i++) {
						colsHeight[i] = 0;
					}
					var mainDiv = $('<div>').addClass('main').insertBefore(form.children('p.buttons'));
					for (i = 0; i < sections.length; i++) {
						var col = i % cols;
						var sectionInnerDiv = $('<div>').addClass('inner');
						var sectionDiv = $('<div>').addClass('section').css({width: colWidth, right: col * colWidth, top: colsHeight[col]});
						sectionDiv.append(sectionInnerDiv);
						for (j = 0; j < sections[i].length; j++) {
							sectionInnerDiv.append(sections[i][j]);
						}
						mainDiv.append(sectionDiv);
						colsHeight[col] += sectionDiv.outerHeight(true);
					}
					var maxColHeight = 0;
					for (i = 0; i < cols; i++) {
						if (colsHeight[i] > maxColHeight) {
							maxColHeight = colsHeight[i];
						}
					}
					mainDiv.css({height: maxColHeight});
				})();
				(function(){
					var slaves = form.find('select[data-master]');
					var masterSlaves = {};
					var slaveOptgroups = {};
					slaves.each(function(){
						var slave = $(this);
						var masterName = slave.data('master');
						if (!masterSlaves.hasOwnProperty(masterName)) {
							masterSlaves[masterName] = [];
						}
						masterSlaves[masterName].push(slave);
						slaveOptgroups[slave.attr('name')] = slave.children('optgroup').remove();
						slave.data('masters', slave.data('masters').split(' '));
					});
					for (var masterName in masterSlaves) {
						if (masterSlaves.hasOwnProperty(masterName)) {
							form.find('select[name="' + masterName + '"]').change(masterChanged).change();
						}
					}
					for (var slaveName in slaveOptgroups) {
						if (slaveOptgroups.hasOwnProperty(slaveName)) {
							slaveOptgroups[slaveName].children('[selected]').removeAttr('selected');
						}
					}
					function masterChanged() {
						var slaves = masterSlaves[$(this).attr('name')];
						for (var i = 0; i < slaves.length; i++) {
							updateSlave(slaves[i]);
						}
					}
					function updateSlave(slave) {
						var masterNames = slave.data('masters');
						var mastersIndex = {};
						var i, m;
						for (i = masterNames.length; i--; ) {
							var masterName = masterNames[i];
							mastersIndex[masterName] = masterIndex(masterName);
						}
						var optgroups = slaveOptgroups[slave.attr('name')];
						slave.children('option[value!=""]').remove();
						for (i = optgroups.length; i--; ) {
							var optgroup = $(optgroups[i]);
							var active = true;
							for (m in mastersIndex) {
								if (mastersIndex.hasOwnProperty(m)) {
									if (Number(optgroup.data('master-' + m)) !== mastersIndex[m]) {
										active = false;
										break;
									}
								}
							}
							if (active) {
								slave.append(optgroup.children().clone()).change();
								break;
							}
						}
					}
					function masterIndex(masterName) {
						var master = form.find('select[name="' + masterName + '"]');
						var masterIndex = master.prop('selectedIndex');
						if (master.children('option:first-child').attr('value') === '') {
							masterIndex--;
						}
						return masterIndex;
					}
				})();
			});
		});
	};
})(jQuery);