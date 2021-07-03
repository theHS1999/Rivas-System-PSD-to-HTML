$(document).ready(function() {

	$("#topLevelEtitiesManagement ul").hide();
	$("#centerEtitiesManagement ul").hide();
	$("#hygieneUnitEtitiesManagement ul").hide();
	$("#unitEtitiesManagement ul").hide();

	$("#topLevelEtitiesManagement p").click(function() {
		$("#topLevelEtitiesManagement ul").show(500);
		$("#centerEtitiesManagement ul").hide(500);
		$("#hygieneUnitEtitiesManagement ul").hide(500);
		$("#unitEtitiesManagement ul").hide(500);
	});

	$("#centerEtitiesManagement p").click(function() {
		$("#centerEtitiesManagement ul").show(500);
		$("#topLevelEtitiesManagement ul").hide(500);
		$("#hygieneUnitEtitiesManagement ul").hide(500);
		$("#unitEtitiesManagement ul").hide(500);
	});

	$("#hygieneUnitEtitiesManagement p").click(function() {
		$("#hygieneUnitEtitiesManagement ul").show(500);
		$("#topLevelEtitiesManagement ul").hide(500);
		$("#centerEtitiesManagement ul").hide(500);
		$("#unitEtitiesManagement ul").hide(500);
	});

	$("#unitEtitiesManagement p").click(function() {
		$("#unitEtitiesManagement ul").show(500);
		$("#topLevelEtitiesManagement ul").hide(500);
		$("#centerEtitiesManagement ul").hide(500);
		$("#hygieneUnitEtitiesManagement ul").hide(500);
	});

	$("#estijari").hide();
	$("#input_rent_cost").hide();
	if($("#input_rent_cost").hasClass('required'))
		$("#input_rent_cost").removeClass('required');
	$("#melki").show();
	$("#input_building_worth").show();
	$("#date_picker_label").show();
	$("#date_picker_div").show();

	$("#input_ownership_type_0").click(function() {//owned
		$("#estijari").hide();
		$("#input_rent_cost").hide();
		if($("#input_rent_cost").hasClass('required'))
			$("#input_rent_cost").removeClass('required');
		if(!$("#input_building_worth").hasClass('required'))
			$("#input_building_worth").addClass('required');
		if(!$("#date_picker_div").hasClass('required'))
			$("#date_picker_div").addClass('required');
		if(!$("#date_picker_label").hasClass('required'))
			$("#date_picker_label").addClass('required');
		$("#melki").show();
		$("#input_building_worth").show();
		$("#date_picker_label").show();
		$("#date_picker_div").show();
	});

	$("#input_ownership_type_1").click(function() {//rent
		$("#estijari").show();
		$("#input_rent_cost").show();
		$("#melki").hide();
		$("#input_building_worth").hide();
		$("#date_picker_label").hide();
		$("#date_picker_div").hide();
		if(!$("#input_rent_cost").hasClass('required'))
			$("#input_rent_cost").addClass('required');
		if($("#input_building_worth").hasClass('required'))
			$("#input_building_worth").removeClass('required');
		if($("#date_picker_div").hasClass('required'))
			$("#date_picker_div").removeClass('required');
		if($("#date_picker_label").hasClass('required'))
			$("#date_picker_label").removeClass('required');
	});

	$("#input_ownership_type_2").click(function() {
		$("#estijari").hide();
		$("#input_rent_cost").hide();
		$("#melki").hide();
		$("#input_building_worth").hide();
		$("#date_picker_label").hide();
		$("#date_picker_div").hide();
		if($("#input_rent_cost").hasClass('required'))
			$("#input_rent_cost").removeClass('required');
		if($("#input_building_worth").hasClass('required'))
			$("#input_building_worth").removeClass('required');
		if($("#date_picker_div").hasClass('required'))
			$("#date_picker_div").removeClass('required');
		if($("#date_picker_label").hasClass('required'))
			$("#date_picker_label").removeClass('required');
	});

	$("#input_vehicle_ownership_type_0").click(function() {
		$("#estijari").hide();
		$("#input_rent_cost").hide();
		if($("#input_rent_cost").hasClass('required'))
			$("#input_rent_cost").removeClass('required');
		if(!$("#input_vehicle_worth").hasClass('required'))
			$("#input_vehicle_worth").addClass('required');
		if(!$("#date_picker_div").hasClass('required'))
			$("#date_picker_div").addClass('required');
		if(!$("#date_picker_label").hasClass('required'))
			$("#date_picker_label").addClass('required');
		$("#melki").show();
		$("#input_vehicle_worth").show();
		$("#date_picker_label").show();
		$("#date_picker_div").show();
	});

	$("#input_vehicle_ownership_type_1").click(function() {
		$("#estijari").show();
		$("#input_rent_cost").show();
		$("#melki").hide();
		$("#input_vehicle_worth").hide();
		$("#date_picker_label").hide();
		$("#date_picker_div").hide();
		if(!$("#input_rent_cost").hasClass('required'))
			$("#input_rent_cost").addClass('required');
		if($("#input_vehicle_worth").hasClass('required'))
			$("#input_vehicle_worth").removeClass('required');
		if($("#date_picker_div").hasClass('required'))
			$("#date_picker_div").removeClass('required');
		if($("#date_picker_label").hasClass('required'))
			$("#date_picker_label").removeClass('required');
	});

	$("#build_year").change(function() {
		var nowYear = getPersianYear();
		var buildYear = this.value;
		if((nowYear - buildYear) > 15) {
			$("#input_building_worth").hide();
			$("#melki").hide();
			if($("#input_building_worth").hasClass('required'))
				$("#input_building_worth").removeClass('required');
		} else {
			if(!$("#input_building_worth").hasClass('required'))
				$("#input_building_worth").addClass('required');
			$("#melki").show();
			$("#input_building_worth").show();
		}

	});

	$("#buy_year").change(function() {
		var nowYear = getPersianYear();
		var buyYear = this.value;
		if((nowYear - buyYear) > 10) {
			$("#input_vehicle_worth").hide();
			$("#melki").hide();
			if($("#input_vehicle_worth").hasClass('required'))
				$("#input_vehicle_worth").removeClass('required');
		} else {
			if(!$("#input_vehicle_worth").hasClass('required'))
				$("#input_vehicle_worth").addClass('required');
			$("#melki").show();
			$("#input_vehicle_worth").show();
		}

	});

	$("#slideForm").hide();
	$("#slideLink").click(function() {
		$("#slideForm").fadeToggle(500);
		return false;
	});

	$("#slideForm1").hide();
	$("#slideLink1").click(function() {
		$("#slideForm1").fadeToggle(500);
		return false;
	});

	$('#tabs').tabs();

	$('#reportDescription').hide();
	$('#unitDescription').hide();
	$('#servicesChart').hide();

	$('#exportFieldset').hide();
	$('#export').change(function() {
		$("#exportFieldset").fadeToggle(500);
		if(!document.reportsForm.export.checked) {
			document.reportsForm.exportFileType[0].checked = false;
			document.reportsForm.exportFileType[1].checked = false;
			document.reportsForm.exportFileType[2].checked = false;
		}
		else
			document.reportsForm.exportFileType[0].checked = true;
	});
	
	$('#export5Fieldset').hide();
	$('#export5').change(function() {
		$("#export5Fieldset").fadeToggle(500);
		if(!document.reportsForm.export5.checked) {
			document.reportsForm.export5FileType[0].checked = false;
			document.reportsForm.export5FileType[1].checked = false;
			document.reportsForm.export5FileType[2].checked = false;
		}
		else
			document.reportsForm.export5FileType[0].checked = true;
	});

	$('select[name="accessLevel"]').change(function() {
		changeACL($('select[name="accessLevel"] option:selected').val());
	});
	
	$('#stateManagerACLDiv').hide();
	$('#townManagerACLDiv').hide();
	$('#centerManagerACLDiv').hide();
	$('#unitManagerACLDiv').hide();
	$('#hygieneUnitManagerACLDiv').hide();

	$(document).ajaxStart(function() {
		$('.loading').show();
		$('#mngDiv1').css('opacity', '0.3');
	});

	$(document).ajaxSuccess(function() {
		$('.loading').hide();
		$('#mngDiv1').css('opacity', '1');
	})

	var ajaxPost1 = $.post("../statisticalPlotter/AsynchronousProccess.php", {
		'centerId' : '25',
		'unitType' : $("input[name='input_service_type']").value,
	});
	ajaxPost1.done(function(data) {
		$('#unitId0').empty().append(data);
		var ajaxPost2 = $.post('./ea-sh-ajax.php', {
			'unitIdForServiceSelection' : $('#unitId0 option:selected').val(),
		});
		ajaxPost2.done(function(data) {$('#input_service_id0').empty().append(data);
			$('#numOfUnitService').load('./ea-sh-ajax.php', {'unitId4CurrentYearServices':
					$('#unitId0 option:selected').val()
			});
		});
	});

	$('input[name="input_service_type"]').change(function() {
		if(this.value === "0") {

		} else {

		}
	});

});
//END READY

function changeACL(acl) {
	var htmlOut = '';
	switch(acl) {
		case 'stateManager':
			$('#stateManagerACLDiv').show();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="stateId">نام استان:</label>';
			htmlOut += '<select name="stateId" id="stateId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += '$(\'#stateId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "state"});';
			htmlOut += '</script>';
			break;
		case 'townManager':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').show();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += '$(\'#townId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += '</script>';
			break;
		case 'centerManager':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').show();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="centerId" class="required">نام مرکز:</label>';
			htmlOut += '<select name="centerId" id="centerId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});});';
			htmlOut += '$(\'#townId\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});});';
			htmlOut += '</script>';
			break;
		case 'unitManager':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').show();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="centerId" class="required">نام مرکز:</label>';
			htmlOut += '<select name="centerId" id="centerId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="unitId" class="required">نام واحد:</label>';
			htmlOut += '<select name="unitId" id="unitId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#centerId\').change(function() {$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';

			htmlOut += '</script>';
			break;
		case 'hygieneUnitManager':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').show();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed"></select><br />';
			htmlOut += '<label for="centerId" class="required">نام مرکز:</label>';
			htmlOut += '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
			htmlOut += '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
			htmlOut += '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
			htmlOut += '</script>';
			break;
		case 'stateAuthor':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="stateId">نام استان:</label>';
			htmlOut += '<select name="stateId" id="stateId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += '$(\'#stateId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "state"});';
			htmlOut += '</script>';
			break;
		case 'townAuthor':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += '$(\'#townId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += '</script>';
			break;
		case 'centerAuthor':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="centerId" class="required">نام مرکز:</label>';
			htmlOut += '<select name="centerId" id="centerId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});});';
			htmlOut += '$(\'#townId\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});});';
			htmlOut += '</script>';
			break;
		case 'unitAuthor':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="centerId" class="required">نام مرکز:</label>';
			htmlOut += '<select name="centerId" id="centerId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="unitId" class="required">نام واحد:</label>';
			htmlOut += '<select name="unitId" id="unitId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#centerId\').change(function() {$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
			htmlOut += '</script>';
			break;
		case 'hygieneUnitAuthor':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="centerId" class="required">نام مرکز:</label>';
			htmlOut += '<select name="centerId" id="centerId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
			htmlOut += '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
			htmlOut += '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
			htmlOut += '</script>';
			break;
		case 'multiAuthor':
			$('#stateManagerACLDiv').hide();
			$('#townManagerACLDiv').hide();
			$('#centerManagerACLDiv').hide();
			$('#unitManagerACLDiv').hide();
			$('#hygieneUnitManagerACLDiv').hide();
			htmlOut += '<label for="townId" class="required">نام شهرستان:</label>';
			htmlOut += '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="centerId" class="required">نام مراکز:</label>';
			htmlOut += '<select name="centerId[]" id="centerId" multiple="multiple" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<label for="hygieneUnitId" class="required">نام خانه های بهداشت:</label>';
			htmlOut += '<select name="hygieneUnitId[]" id="hygieneUnitId" multiple="multiple" class="validate first_opt_not_allowed">';
			htmlOut += '</select><br />';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townId\').empty().append(data);$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townIdForHygieneUnitSelection\' : $(\'#townId option:selected\').val()});});';
			htmlOut += '$(\'#townId\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townIdForHygieneUnitSelection\' : $(\'#townId option:selected\').val()});});';
			htmlOut += '</script>';
			break;
	}

	$('#acl_items').html(htmlOut);
	$('form.narin').find('label:not(input[type="checkbox"]+label,input[type="radio"]+label,.labelbr)').autoWidth();
}

function editACL() {
	var htmlOut = '';
	htmlOut += '<label for="accessLevel" class="required">سطح دسترسی:</label>';
	htmlOut += '<select name="accessLevel" id="accessLevel">';
	htmlOut += '<optGroup label="مدیران">';
	htmlOut += '<option value="stateManager">مدیر استان</option>';
	htmlOut += '<option value="townManager">مدیر شهرستان</option>';
	htmlOut += '<option value="centerManager">مدیر مرکز</option>';
	htmlOut += '<option value="unitManager">مدیر واحد</option>';
	htmlOut += '<option value="hygieneUnitManager">مدیر خانه بهداشت</option>';
	htmlOut += '</optGroup>';
	htmlOut += '<option value="" disabled="">-------------------------------------</option>';
	htmlOut += '<optGroup label="نویسندگان">';
	htmlOut += '<option value="stateAuthor">نویسنده استان</option>';
	htmlOut += '<option value="townAuthor">نویسنده شهرستان</option>';
	htmlOut += '<option value="centerAuthor">نویسنده مرکز</option>';
	htmlOut += '<option value="unitAuthor">نویسنده واحد</option>';
	htmlOut += '<option value="hygieneUnitAuthor">نویسنده خانه بهداشت</option>';
	//htmlOut += '<option value="multiAuthor">نویسنده چندمنظوره</option>';
	htmlOut += '</optGroup>';
	htmlOut += '</select><br />';

	$('#acl_cat').html(htmlOut);
	$('form.narin').find('label:not(input[type="checkbox"]+label,input[type="radio"]+label,.labelbr)').autoWidth();
	$('select[name="accessLevel"]').change(function() {
		changeACL($('select[name="accessLevel"] option:selected').val());
	});
	$('#accessLevel').trigger('change');
}

function editPasswd() {
	var htmlOut = '';
	htmlOut += '<label for="passwd" class="required">رمز عبور جدید:</label>';
	htmlOut += '<input type="password" name="passwd" id="passwd" class="validate required minlength" />';
	htmlOut += '<span class="description">حداقل 8 کارکتر</span><br />';
	htmlOut += '<label for="passwdconfirm" class="required">تکرار رمز عبور جدید:</label>';
	htmlOut += '<input type="password" name="passwdconfirm" id="passwdconfirm" class="validate required match" />';
	htmlOut += '<script type="text/javascript">$(\'#passwdconfirm\').data(\'match\',\'passwd\');$(\'#passwd\').data(\'minlength\',8);</script><br />';

	$('#newPasswd').html(htmlOut);
	$('form.narin').find('label:not(input[type="checkbox"]+label,input[type="radio"]+label,.labelbr)').autoWidth();
}