/* Copyright (C) Rivas System, Inc. All rights reserved. */
(function($){
	window.NarinFormCreator = new function(){
	
		var settings =
		{
			animDuration: 200,
			postUrl: 'formcreatordemo_ajax.php',
			postParamNames: {formSource: 'form_source', action: 'action'},
			postResponse: function(response){
				var ci = response.indexOf(':');
				var status = response.substr(0, ci);
				var text = response.substr(ci + 1);
				if (status === 'success') {
					return {success: true, message: 'عملیات با موفقیت انجام شد. <a href="formdemo.php?form=' + text + '" target="_blank">[فرم]</a> <a href="dataviewerdemo.php?form=' + text + '" target="_blank">[اطلاعات]</a> <a href="formcreatordemo.php?form=' + text + '" target="_blank">[ویرایش]</a>'};
				}
				return {success: false, message: 'عملیات با خطا مواجه شد: <pre dir="ltr">' + text + '</pre>'};
			}
		};
		
		var NarinFormCreator = this;
		
		NarinFormCreator.clear = function() {
			var main = $('.narinformcreator .main');
			main.find('.form [name]').each(function(){
				var input = $(this);
				if (input.attr('type') === 'checkbox') {
					input.prop('checked', false);
				}
				else {
					input.val('');
				}
			});
			main.find('.controls-added').empty();
			main.find('.has-params').change();
		};
		
		NarinFormCreator.load = function(formSource) {
			$(document).ready(function(){
				NarinFormCreator.clear();
				var main = $('.narinformcreator .main');
				var proto = $('.narinformcreator .proto');
				var controls = main.find('.controls-added');
				loadFieldset(main.find('.form'), formSource);
				for (var i = 0; i < formSource.controls.length; i++) {
					var controlSource = formSource.controls[i];
					var controlProto = proto.find('.control[data-type="' + controlSource.type + '"]');
					var control = controlProto.clone(true).appendTo(controls);
					var options = control.find('.options ul').empty();
					loadFieldset(control, controlSource);
					if (options.length) {
						loadOptions(options, controlProto.find('.options li:first-child'), controlSource.options);
					}
					var optionSets = control.find('.option-sets');
					if (optionSets.length) {
						var optionsProto = proto.find('.control[data-type=select] .options').clone(true);
						var optionsProtoH = optionsProto.find('h4');
						$('<h5>').insertAfter(optionsProtoH);
						optionsProtoH.remove();
						var optionProto = optionsProto.find('li:first');
						optionsProto.find('ul').empty();
						for (var j = 0; j < controlSource.option_sets.length; j++) {
							var optionSetSource = controlSource.option_sets[j];
							var optionSet = optionsProto.clone(true).appendTo(optionSets);
							optionSet.data('masters-index', optionSetSource.masters_index);
							optionSet.find('h5').text(optionSetSource.masters_label);
							loadOptions(optionSet.find('ul'), optionProto, optionSetSource.options);
						}
					}
				}
				main.find('.has-params').change();
			});
			function loadOptions(parent, proto, source) {
				for (var i = 0; i < source.length; i++) {
					var optionSource = source[i];
					var option = proto.clone(true).appendTo(parent);
					loadFieldset(option, optionSource);
					if (optionSource.def) {
						option.addClass('default');
					}
				}
			}
			function loadFieldset(fieldset, source) {
				fieldset.find('[name]').each(function(){
					var input = $(this);
					var name = input.attr('name');
					var di = name.indexOf('.');
					if (di == -1) {
						val = source.hasOwnProperty(name) ? source[name] : '';
						if (name === 'max_file_size' && val !== '') {
							if (val % 1073741824 == 0) {
								val /= 1073741824;
								unit = 'gb';
							}
							else if (val % 1048576 == 0) {
								val /= 1048576;
								unit = 'mb';
							}
							else if (val % 1024 == 0) {
								val /= 1024;
								unit = 'kb';
							}
							else {
								unit = 'b';
							}
							input.siblings('.unit').val(unit);
						}
					}
					else {
						var k1 = name.substr(0, di);
						var k2 = name.substr(di + 1);
						val = source.hasOwnProperty(k1) && source[k1].hasOwnProperty(k2) ? source[k1][k2] : '';
					}
					if (input.attr('type') === 'checkbox') {
						input.prop('checked', val);
					}
					else {
						input.val(val);
					}
				});
			}
		};
		
		NarinFormCreator.validate = function() {
			var main = $('.narinformcreator .main');
			var validated = true;
			var validationInputs;
			return (function(){
				main.find('.invalid, .conflict').removeClass('invalid conflict');
				validationInputs = main.find('[name]:visible');
				validateInputs('.required', function(v){return /[^\s]/.test(v);});
				validateInputs('.form [name=name], .form [name=prerequisite]', function(v){return v === '' || /^[a-z][a-z0-9_]*$/i.test(v);});
				validateInputs('.controls-added [name=name]', function(v){return v === '' || /^[a-z][a-z0-9_-]*$/i.test(v);});
				validateInputs('[name=cols]', function(v){return v === '' || (isInt(v = Number(v)) && v > 0 && v <= 4);});
				validateInputs('[name=redir_url]', function(v){return v === '' || /^https?:\/\/[^\s\/]+\.[^\s\/]+(\/.*)?$/i.test(v);});
				validateInputs('[name=regexp]', function(v){try{RegExp(v);} catch(e){return false;} return true;});
				validateInputs('.control[data-type=text] [name=max_len], .control[data-type=text] [name=digits]', function(v){return v === '' || (isInt(v = Number(v)) && v > 0 && v <= 255);});
				validateInputs('.control[data-type=textarea] [name=max_len]', function(v){return v === '' || (isInt(v = Number(v)) && v > 0 && v <= 16777215);});
				validateInputs('.control[data-type=text] .params[data-value=integer] [name=min], .control[data-type=text] .params[data-value=integer] [name=max]', function(v){return v === '' || (isInt(v = Number(v)) && v >= -2147483648 && v <= 2147483647);});
				validateInputs('.control[data-type=text] .params[data-value=decimal] [name=min], .control[data-type=text] .params[data-value=decimal] [name=max]', function(v){return v === '' || !isNaN(Number(v));});
				validateInputs('.control[data-type=checkbox-array] [name=min]', function(v){return v === '' || (isInt(v = Number(v)) && v >= 0 && v <= 2147483647);});
				validateInputs('.control[data-type=checkbox-array] [name=max]', function(v){return v === '' || (isInt(v = Number(v)) && v >= 2 && v <= 2147483647);});
				validateInputs('.control[data-type=file] [name=max_file_size]', function(v){return v === '' || (isInt(v = Number(v)) && v > 0 && v <= 2147483647);});
				validateInputs('.control[data-type=file] [name=valid_exts]', function(v){return v === '' || /^([a-z0-9]+;)*[a-z0-9]+$/i.test(v);});
				(function(){
					var selects = {};
					main.find('.control[data-type^=select] [name=name]:not(.invalid *, .conflict *)').each(function(){
						var $this = $(this);
						var name = $this.val();
						if (name !== '') {
							selects[name] = $this.closest('.control');
						}
					});
					main.find('.control[data-type=select-slave] [name=master_name]').each(function(){
						var $this = $(this);
						var masterName = $this.val();
						if (!(selects.hasOwnProperty(masterName))) {
							$this.closest('p').addClass('invalid');
							validated = false;
							return;
						}
						var masterControl = selects[masterName];
						var mastersIndex = [];
						if (masterControl.data('type') === 'select') {
							masterControl.find('.options li').each(function(index){
								var data = {};
								data[masterName] = index;
								mastersIndex.push(data);
							});
						}
						else {
							masterControl.find('.options').each(function(){
								var options = $(this);
								var masterData = options.data('masters-index');
								options.find('li').each(function(index){
									var data = {};
									data[masterName] = index;
									$.extend(data, masterData);
									mastersIndex.push(data);
								});
							});
						}
						var control = $this.closest('.control');
						var currentOptions = control.find('.options');
						if (currentOptions.length !== mastersIndex.length) {
							control.find('.select-slave-options').addClass('invalid');
							validated = false;
							return;
						}
						for (var i = mastersIndex.length; i--; ) {
							if (!dataEquals(mastersIndex[i], $(currentOptions[i]).data('masters-index'))) {
								control.find('.select-slave-options').addClass('invalid');
								validated = false;
								return;
							}
						}
					});
					function dataEquals(a, b) {
						var k;
						for (k in a) {
							if (a.hasOwnProperty(k) && (!b.hasOwnProperty(k) || a[k] !== b[k])) {
								return false;
							}
						}
						for (k in b) {
							if (b.hasOwnProperty(k) && (!a.hasOwnProperty(k) || a[k] !== b[k])) {
								return false;
							}
						}
						return true;
					}
				})();
				main.find('.date:visible, .datetime:visible').each(function(){
					var $this = $(this);
					var y = $this.find('.y').val(), m = $this.find('.m').val(), d = $this.find('.d').val(), h = $this.find('.h').val(), n = $this.find('.n').val();
					var t = $this.hasClass('datetime');
					var v;
					if (y === '' && m === '' && d === '' && (!t || (h === '' && n === ''))) {
						v = !$this.hasClass('required');
					}
					else if (y === '' || m === '' || d === '' || (t && (h === '' || n === ''))) {
						v = false;
					}
					else {
						y = Number(y); m = Number(m); d = Number(d);
						v = !(isNaN(y) || y < 1000 || y > 9377 || (d == 31 && m > 6));
					}
					if (!v) {
						$this.closest('p').addClass('invalid');
						validated = false;
					}
				});
				main.find('.time:visible').each(function(){
					var $this = $(this);
					var h = $this.find('.h').val(), n = $this.find('.n').val();
					var v;
					if (h === '' && n === '') {
						v = !$this.hasClass('required');
					}
					else {
						v = !(h === '' || n === '');
					}
					if (!v) {
						$this.closest('p').addClass('invalid');
						validated = false;
					}
				});
				main.find('.control[data-type=text] .params[data-value=integer]:visible, .control[data-type=text] .params[data-value=decimal]:visible, .control[data-type=checkbox-array]').each(function(){
					var $this = $(this);
					var min = $this.find('[name=min]:not(.invalid *)');
					var max = $this.find('[name=max]:not(.invalid *)');
					if (min.length && max.length && min.val() !== '' && max.val() !== '' && ($this.data('type') === 'checkbox-array' ? Number(min.val()) > Number(max.val()) : Number(min.val()) >= Number(max.val()))) {
						min.closest('p').addClass('invalid');
						max.closest('p').addClass('invalid');
						validated = false;
					}
				});
				main.find('.control[data-type=date] .params[data-value=range]:visible, .control[data-type=datetime] .params[data-value=range]:visible').each(function(){
					var $this = $(this);
					var from = $this.find('.opt-spec-params[data-input=from] .params[data-value=value]:visible .multi-inp:not(.invalid *)');
					var to = $this.find('.opt-spec-params[data-input=to] .params[data-value=value]:visible .multi-inp:not(.invalid *)');
					if (!(from.length && to.length)) {
						return;
					}
					var t = to.hasClass('datetime');
					if (compareDatetime([Number(from.find('.y').val()), Number(from.find('.m').val()), Number(from.find('.d').val()), t ? Number(from.find('.h').val()) : 0, t ? Number(from.find('.n').val()) : 0], [Number(to.find('.y').val()), Number(to.find('.m').val()), Number(to.find('.d').val()), t ? Number(to.find('.h').val()) : 0, t ? Number(to.find('.n').val()) : 0]) >= 0) {
						from.closest('p').addClass('invalid');
						to.closest('p').addClass('invalid');
						validated = false;
					}
				});
				checkDuplicateValues(main.find('.controls-added [name=name]:not(.invalid *)'));
				main.find('.options:not(.option-sets .options)').each(function(){
					var $this = $(this);
					checkDuplicateValues($this.find('[name=label]:not(.invalid *)'));
					checkDuplicateValues($this.find('[name=value]:not(.invalid *)'));
				});
				main.find('.option-sets').each(function(){
					var $this = $(this);
					checkDuplicateValues($this.find('[name=value]:not(.invalid *)'));
					$this.find('.options').each(function(){
						checkDuplicateValues($(this).find('[name=label]:not(.invalid *)'));
					});
				});
				if (main.find('.control:not([data-type=heading], [data-type=paragraph])').length == 0) {
					main.find('.controls h2').addClass('invalid');
					validated = false;
				}
				return validated;
			})();
			function validateInputs(selector, validateFn) {
				validationInputs.filter(selector).each(function(){
					var input = $(this);
					if (!(validateFn(input.val()))) {
						input.closest('p').addClass('invalid');
						validated = false;
					}
				});
			}
			function checkDuplicateValues(inputs) {
				for (var i = 0; i < inputs.length; i++) {
					for (var j = i + 1; j < inputs.length; j++) {
						if (inputs[i].value !== '' && inputs[i].value === inputs[j].value) {
							$(inputs[i]).closest('p').addClass('conflict');
							$(inputs[j]).closest('p').addClass('conflict');
							validated = false;
						}
					}
				}
			}
			function isInt(n) {
				return n % 1 === 0;
			}
			function compareDatetime(a, b) {
				for (var i = 0; i < 5; i++) {
					if (a[i] > b[i]) {
						return 1;
					}
					if (a[i] < b[i]) {
						return -1;
					}
				}
				return 0;
			}
		};
		
		NarinFormCreator.getFormSource = function() {
			return (function(){
				var main = $('.narinformcreator .main');
				var source = fieldsetSource(main.find('.form'));
				source.controls = [];
				main.find('.control').each(function(){
					var control = $(this);
					var controlSource = fieldsetSource(control);
					controlSource.type = control.data('type');
					source.controls.push(controlSource);
				});
				return source;
			})();
			function fieldsetSource(fieldset) {
				var source = inputsSource(fieldset.find('[name]:visible:not(.options *)'));
				var optionSets = fieldset.find('.option-sets .options');
				if (optionSets.length) {
					source.option_sets = [];
					optionSets.each(function(){
						var optionSet = $(this);
						var optionSetSource = {masters_index: optionSet.data('masters-index'), masters_label: optionSet.find('h5').text()};
						optionSetSource.options = optionsSource(optionSet.find('li'));
						source.option_sets.push(optionSetSource);
					});
				}
				else {
					var options = fieldset.find('.options li');
					if (options.length) {
						source.options = optionsSource(options);
					}
				}
				return source;
			}
			function optionsSource(options) {
				var source = [];
				options.each(function(){
					var option = $(this);
					var optionSource = inputsSource(option.find('[name]'));
					if (option.hasClass('default')) {
						optionSource.def = 1;
					}
					source.push(optionSource);
				});
				return source;
			}
			function inputsSource(inputs) {
				var source = {};
				inputs.each(function(){
					var input = $(this);
					var val = input.attr('type') === 'checkbox' ? (input.prop('checked') ? 1 : '') : (input.val() !== '' && input.hasClass('number') ? Number(input.val()) : input.val());
					if (val !== '') {
						var name = input.attr('name');
						var di = name.indexOf('.');
						if (di == -1) {
							if (name === 'max_file_size') {
								val *= {'b': 1, 'kb': 1024, 'mb': 1048576, 'gb': 1073741824}[input.siblings('.unit').val()];
							}
							source[name] = val;
						}
						else {
							var key = name.substr(di + 1);
							name = name.substr(0, di);
							if (typeof source[name] !== 'object') {
								source[name] = {};
							}
							source[name][key] = val;
						}
					}
				});
				return source;
			}
		};
		
		$(document).ready(function(){
			var main = $('.narinformcreator .main');
			var proto = $('.narinformcreator .proto');
			main.find('.add-control-list a').click(function(e){
				e.preventDefault();
				var control = proto.find('.control[data-type="' + $(this).attr('href').substr(1) + '"]').clone(true).hide().appendTo(main.find('.controls-added')).show(settings.animDuration);
				control.find('[name]:first').focus();
			});
			(function(){
				var addControlDropDown = main.find('.add-control-list').clone().removeClass('add-control-list').addClass('drop-down').hide().appendTo(main);
				var currentControl;
				addControlDropDown.find('a').click(function(e){
					e.preventDefault();
					var control = proto.find('.control[data-type="' + $(this).attr('href').substr(1) + '"]').clone(true).hide().insertAfter(currentControl).show(settings.animDuration);
					control.find('[name]:first').focus();
				});
				proto.find('a[href="#add-control"]').click(function(e, enterKeyPressed){
					e.preventDefault();
					var $this = $(this);
					currentControl = $this.closest('.control');
					if (enterKeyPressed) {
						var offset = $this.offset();
						addControlDropDown.show().offset({left: offset.left, top: offset.top + $this.height()});
						addControlDropDown.find('li:first-child a').focus();
					}
					else {
						addControlDropDown.show().offset({left: e.pageX, top: e.pageY});
					}
				}).keydown(function(e){
					if (e.keyCode == 13) {
						e.preventDefault();
						$(this).trigger('click', true);
					}
				});
				$('html').click(function(e){
					if ($(e.target).attr('href') !== '#add-control') {
						addControlDropDown.hide();
					}
				});
			})();
			proto.find('a[href="#add-option"]').click(function(e){
				e.preventDefault();
				var $this = $(this);
				var type = $this.closest('.control').data('type');
				if (type === 'select-slave') {
					type = 'select';
				}
				var option = proto.find('.control[data-type="' + type + '"] .options li:first-child').clone(true).hide().insertAfter($this.closest('li')).show(settings.animDuration);
				option.find('[name]:first').focus();
			});
			proto.find('a[href="#default"]').click(function(e){
				e.preventDefault();
				var item = $(this).closest('li');
				if (item.closest('.control').data('type') === 'radio') {
					item.siblings().removeClass('default');
				}
				item.toggleClass('default');
			});
			(function(){
				proto.find('a[href="#remove-control"]').click(function(e){
					e.preventDefault();
					remove($(this).closest('.control'));
				});
				proto.find('a[href="#remove-option"]').click(function(e){
					e.preventDefault();
					var item = $(this).closest('li');
					if (item.siblings().length < (item.closest('.option-sets').length ? 1 : 2)) {
						return;
					}
					remove(item);
				});
				proto.find('a[href="#up-control"]').click(function(e){
					e.preventDefault();
					up($(this).closest('.control'));
				});
				proto.find('a[href="#up-option"]').click(function(e){
					e.preventDefault();
					up($(this).closest('li'));
				});
				proto.find('a[href="#down-control"]').click(function(e){
					e.preventDefault();
					down($(this).closest('.control'));
				});
				proto.find('a[href="#down-option"]').click(function(e){
					e.preventDefault();
					down($(this).closest('li'));
				});
				function remove(item) {
					item.hide(settings.animDuration, function(){
						item.remove();
					});
				}
				function up(item) {
					if (item.is(':first-child')) {
						return;
					}
					var prev = item.prev();
					item.insertBefore(prev.css({top: -item.outerHeight(true)})).css({top: prev.outerHeight(true)});
					item.animate({top: 0}, settings.animDuration);
					prev.animate({top: 0}, settings.animDuration);
				}
				function down(item) {
					if (item.is(':last-child')) {
						return;
					}
					var next = item.next();
					item.insertAfter(next.css({top: item.outerHeight(true)})).css({top: -next.outerHeight(true)});
					item.animate({top: 0}, settings.animDuration);
					next.animate({top: 0}, settings.animDuration);
				}
			})();
			$('.narinformcreator .has-params').change(function(){
				var input = $(this);
				var params = input.closest('.fieldset').find('.opt-spec-params[data-input="' + input.attr('name') + '"]');
				params.children('.params').hide(settings.animDuration);
				params.children('.params[data-value~="' + (input.attr('type') === 'checkbox' ? (input.prop('checked') ? '1' : '') : input.val()) + '"]').stop().show(settings.animDuration);
			}).change();
			proto.find('a[href="#predef-file-format"]').click(function(e){
				e.preventDefault();
				var $this = $(this);
				var control = $this.closest('.control');
				control.find('[name=valid_exts]').val($this.data('exts'));
				control.find('[name=description]').val($this.text());
			});

			proto.find('a[href="#refresh-options"]').click(function(e){
				e.preventDefault();
				var $this = $(this);
				var control = $this.closest('.control');
				var optionSets = control.find('.option-sets');
				var masterName = control.find('[name=master_name]').val();
				if (!/[^\s]/.test(masterName)) {
					optionSets.empty();
					return;
				}
				var optionsProto = proto.find('.control[data-type=select] .options').clone(true);
				var optionsProtoH = optionsProto.find('h4');
				$('<h5>').insertAfter(optionsProtoH);
				optionsProtoH.remove();
				var masterControl = null;
				var selNameInps = main.find('.control[data-type^=select] [name=name]:not(.options *)');
				for (var i = 0; i < selNameInps.length; i++) {
					var inp = selNameInps.eq(i);
					var name = inp.val();
					if (name === masterName) {
						masterControl = inp.closest('.control');
						masterControl.name = name;
						masterControl.label = masterControl.find('[name=label]:first').val();
						break;
					}
				}
				optionSets.empty();
				if (!masterControl) {
					return;
				}
				if (masterControl.data('type') === 'select') {
					masterControl.find('.options li').each(function(index){
						var newOptions = optionsProto.clone(true);
						newOptions.find('h5').text(masterControl.label + ': ' + $(this).find('[name=label]').val());
						var data = {};
						data[masterControl.name] = index;
						newOptions.data('masters-index', data);
						optionSets.append(newOptions);
					});
				}
				else {
					masterControl.find('.options').each(function(){
						var options = $(this);
						var masterData = options.data('masters-index');
						var masterLabel = options.find('h5').text();
						options.find('li').each(function(index){
							var newOptions = optionsProto.clone(true);
							newOptions.find('h5').text(masterLabel + ' - ' + masterControl.label + ': ' + $(this).find('[name=label]').val());
							var data = {};
							data[masterControl.name] = index;
							$.extend(data, masterData);
							newOptions.data('masters-index', data);
							optionSets.append(newOptions);
						});
					});
				}
				optionSets.find('[name]:first').focus();
			});
			$('.narinformcreator .submit .update').click(function(e){
				e.preventDefault();
				$('form.narinformcreator').trigger('submit', 'update');
			});
			$('form.narinformcreator').submit(function(e, action){
				e.preventDefault();
				var $this = $(this);
				var msgcont = main.find('.submit p');
				if (NarinFormCreator.validate()) {
					$this.attr('disabled', 'disabled');
					msgcont.html('لطفاً صبر کنید...').removeClass('success error').addClass('wait');
					var data = {};
					data[settings.postParamNames.formSource] = JSON.stringify(NarinFormCreator.getFormSource());
					data[settings.postParamNames.action] = action || 'create';
					$.post(settings.postUrl, data, function(response){
						var result = settings.postResponse(response);
						msgcont.html(result.message).removeClass('wait success error').addClass(result.success ? 'success' : 'error');
					}).fail(function(){
						msgcont.html('عملیات با مشکل مواجه شد. لطفاً دوباره تلاش کنید.').removeClass('wait success').addClass('error');
					}).always(function(){
						$this.removeAttr('disabled');
					});
				}
				else {
					msgcont.html('فرم دارای مشکلاتی است. لطفاً موارد مشخص‌شده را اصلاح کنید.').removeClass('wait success').addClass('error');
				}
			});
		});
	};
})(jQuery);