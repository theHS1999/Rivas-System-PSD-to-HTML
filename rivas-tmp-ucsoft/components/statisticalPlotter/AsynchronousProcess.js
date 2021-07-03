/**
 * @author Behnam Salili
 */

$(document).ready(function() {

	$('.loading').hide();

	$(document).ajaxStart(function() {
		$('#loadingRep').show();
		$('#reportFirstType').css('opacity', '0.3');
		$('#reportSecondType').css('opacity', '0.3');
		$('#reportThirdType').css('opacity', '0.3');
		$('#reportForthType').css('opacity', '0.3');
		$('#reportFifthType').css('opacity', '0.3');
	});

	$(document).ajaxSuccess(function() {
		$('#loadingRep').hide();
		$('#reportFirstType').css('opacity', '1');
		$('#reportSecondType').css('opacity', '1');
		$('#reportThirdType').css('opacity', '1');
		$('#reportForthType').css('opacity', '1');
		$('#reportFifthType').css('opacity', '1');
	});

	$('#reportFirstType').hide();
	$('#reportSecondType').hide();
	$('#reportThirdType').hide();
	$('#reportForthType').hide();
	$('#reportFifthType').hide();

	$('input:radio[name=report_type]').change(function() {
		var reportType = $('input:radio[name=report_type]:checked').val();
		switch(reportType) {
			case '1':
				//view
				$('#reportFirstType').fadeIn(500);
				$('#reportSecondType').hide();
				$('#reportThirdType').hide();
				$('#reportForthType').hide();
				$('#reportFifthType').hide();
				
				//validations
				if(!$('#reportFirstType .date_picker').hasClass('required'))
					$('#reportFirstType .date_picker').addClass('required');
				if(!$('#reportFirstType #location_type1').hasClass('validate'))
					$('#reportFirstType #location_type1').addClass('validate');
				if($('#reportSecondType .date_picker').hasClass('required'))
					$('#reportSecondType .date_picker').removeClass('required');
				if($('#reportSecondType #location_type2').hasClass('validate'))
					$('#reportSecondType #location_type2').removeClass('validate');
				if($('#reportThirdType .date_picker').hasClass('required'))
					$('#reportThirdType .date_picker').removeClass('required');
				if($('#reportForthType .date_picker').hasClass('required'))
					$('#reportForthType .date_picker').removeClass('required');
				if($('#reportFifthType .date_picker').hasClass('required'))
					$('#reportFifthType .date_picker').removeClass('required');
				break;
				
			case '2':
				//view
				$('#reportFirstType').hide();
				$('#reportSecondType').fadeIn(500);
				$('#reportThirdType').hide();
				$('#reportForthType').hide();
				$('#reportFifthType').hide();
				
				//validations
				if($('#reportFirstType .date_picker').hasClass('required'))
					$('#reportFirstType .date_picker').removeClass('required');
				if($('#reportFirstType #location_type1').hasClass('validate'))
					$('#reportFirstType #location_type1').removeClass('validate');
				if(!$('#reportSecondType .date_picker').hasClass('required'))
					$('#reportSecondType .date_picker').addClass('required');
				if(!$('#reportSecondType #location_type2').hasClass('validate'))
					$('#reportSecondType #location_type2').addClass('validate');
				if($('#reportThirdType .date_picker').hasClass('required'))
					$('#reportThirdType .date_picker').removeClass('required');
				if($('#reportForthType .date_picker').hasClass('required'))
					$('#reportForthType .date_picker').removeClass('required');
				if($('#reportFifthType .date_picker').hasClass('required'))
					$('#reportFifthType .date_picker').removeClass('required');
				break;
				
			case '3':
				//view
				$('#reportFirstType').hide();
				$('#reportSecondType').hide();
				$('#reportThirdType').fadeIn(500);
				$('#reportForthType').hide();
				$('#reportFifthType').hide();
				
				//validations
				if($('#reportFirstType .date_picker').hasClass('required'))
					$('#reportFirstType .date_picker').removeClass('required');
				if($('#reportFirstType #location_type1').hasClass('validate'))
					$('#reportFirstType #location_type1').removeClass('validate');
				if($('#reportSecondType .date_picker').hasClass('required'))
					$('#reportSecondType .date_picker').removeClass('required');
				if($('#reportSecondType #location_type2').hasClass('validate'))
					$('#reportSecondType #location_type2').removeClass('validate');
				if(!$('#reportThirdType .date_picker').hasClass('required'))
					$('#reportThirdType .date_picker').addClass('required');
				if($('#reportForthType .date_picker').hasClass('required'))
					$('#reportForthType .date_picker').removeClass('required');
				if($('#reportFifthType .date_picker').hasClass('required'))
					$('#reportFifthType .date_picker').removeClass('required');
				break;
				
			case '4':
				//view
				$('#reportFirstType').hide();
				$('#reportSecondType').hide();
				$('#reportThirdType').hide();
				$('#reportForthType').fadeIn(500);
				$('#reportFifthType').hide();
				
				//validations
				if($('#reportFirstType .date_picker').hasClass('required'))
					$('#reportFirstType .date_picker').removeClass('required');
				if($('#reportFirstType #location_type1').hasClass('validate'))
					$('#reportFirstType #location_type1').removeClass('validate');
				if($('#reportSecondType .date_picker').hasClass('required'))
					$('#reportSecondType .date_picker').removeClass('required');
				if($('#reportSecondType #location_type2').hasClass('validate'))
					$('#reportSecondType #location_type2').removeClass('validate');
				if($('#reportThirdType .date_picker').hasClass('required'))
					$('#reportThirdType .date_picker').removeClass('required');
				if(!$('#reportForthType .date_picker').hasClass('required'))
					$('#reportForthType .date_picker').addClass('required');
				if($('#reportFifthType .date_picker').hasClass('required'))
					$('#reportFifthType .date_picker').removeClass('required');
				break;
				
			case '5':
				//view
				$('#reportFirstType').hide();
				$('#reportSecondType').hide();
				$('#reportThirdType').hide();
				$('#reportForthType').hide();
				$('#reportFifthType').fadeIn(500);
				
				//validations
				if($('#reportFirstType .date_picker').hasClass('required'))
					$('#reportFirstType .date_picker').removeClass('required');
				if($('#reportFirstType #location_type1').hasClass('validate'))
					$('#reportFirstType #location_type1').removeClass('validate');
				if($('#reportSecondType .date_picker').hasClass('required'))
					$('#reportSecondType .date_picker').removeClass('required');
				if($('#reportSecondType #location_type2').hasClass('validate'))
					$('#reportSecondType #location_type2').removeClass('validate');
				if($('#reportThirdType .date_picker').hasClass('required'))
					$('#reportThirdType .date_picker').removeClass('required');
				if($('#reportForthType .date_picker').hasClass('required'))
					$('#reportForthType .date_picker').removeClass('required');
				if(!$('#reportFifthType .date_picker').hasClass('required'))
					$('#reportFifthType .date_picker').addClass('required');
				break;
		}
	});
	
	$('input:radio[name=chart_source_type]').change(function() {
		//clear it!
		$('#townSelectionContainer').remove();
		$('#centerSelectionContainer').remove();
		$('#unitSelectionContainer').remove();
		$('#hygieneUnitSelectionContainer').remove();
		var chartSourceType = $('input:radio[name=chart_source_type]:checked').val();
		var htmlOut = '';
		switch(chartSourceType) {
			case '1':	// All units in a center
				htmlOut += '<div id="centerSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب مرکز</legend>';
				htmlOut += '<span id="centerSelectionContent"><select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerNameForCenter" id="centerNameForCenter" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForCenter\').empty().append(data);$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '$(\'#townNameForCenter\').change(function() {$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '</script></div>';
				break;
			case '2':	// All centers in a town
				htmlOut += '<div id="townSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب شهرستان</legend>';
				htmlOut += '<span id="townSelectionContent"><select name="townNameForTown" id="townNameForTown" class="validate first_opt_not_allowed"></select></span></fieldset>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += '$(\'#townNameForTown\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += '</script></div>';
				break;
			case '3':	// All hygiene units in a center
				htmlOut += '<div id="centerSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب مرکز</legend>';
				htmlOut += '<span id="centerSelectionContent"><select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerNameForCenter" id="centerNameForCenter" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForCenter\').empty().append(data);$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '$(\'#townNameForCenter\').change(function() {$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '</script></div>';
				break;
			case '4':	// All unit items
				htmlOut += '<div id="unitSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب واحد</legend>';
				htmlOut += '<span id="unitSelectionContent"><select name="townNameForUnit" id="townNameForUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerNameForUnit" id="centerNameForUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="unitName" id="unitName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {' + '$(\'#townNameForUnit\').empty().append(data);' + 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});' + 'ajaxPost1.done(function(data) {' + '$(\'#centerNameForUnit\').empty().append(data);' + '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit option:selected\').val()});' + '});' + '});';
				htmlOut += '$(\'#townNameForUnit\').change(function() {' + 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});' + 'ajaxPost1.done(function(data) {' + '$(\'#centerNameForUnit\').empty().append(data);' + '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit option:selected\').val()});' + '});' + '});';
				htmlOut += '$(\'#centerNameForUnit\').change(function() {' + '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit option:selected\').val()});' + '});';
				htmlOut += '</script></div>';
				break;
			case '5':	// All center items
				htmlOut += '<div id="centerSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب مرکز</legend>';
				htmlOut += '<span id="centerSelectionContent"><select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerNameForCenter" id="centerNameForCenter" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForCenter\').empty().append(data);$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '$(\'#townNameForCenter\').change(function() {$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '</script></div>';
				break;
			case '6':	// All hygiene unit items
				htmlOut += '<div id="hygieneUnitSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب خانه بهداشت</legend>';
				htmlOut += '<span id="hygieneUnitSelectionContent"><select name="townNameForHygieneUnit" id="townNameForHygieneUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerIdForHygieneUnit" id="centerIdForHygieneUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="hygieneUnitName" id="hygieneUnitName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForHygieneUnit\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerIdForHygieneUnit\').empty().append(data);$(\'#hygieneUnitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit option:selected\').val()});});});';
				htmlOut += '$(\'#townNameForHygieneUnit\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerIdForHygieneUnit\').empty().append(data);$(\'#hygieneUnitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit option:selected\').val()});});});';
				htmlOut += '$(\'#centerIdForHygieneUnit\').change(function() {$(\'#hygieneUnitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit option:selected\').val()});});';
				htmlOut += '</script></div>';
				break;
		}
		
		$('#reportThirdType').append(htmlOut);
	});
	
	$('input:radio[name=TotalCostChart]').change(function() {
		//clear it!
		$('#unitSelectionContainer').remove();
		var chartSourceType = $('input:radio[name=TotalCostChart]:checked').val();
		var htmlOut = '';
		switch(chartSourceType) {
			case '1':	// Center selector
				exit;
				break;
			case '2':	// Unit selector
				htmlOut += '<div id="unitSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب واحد</legend>';
				htmlOut += '<span id="unitSelectionContent">';
				htmlOut += '<select name="unitName" id="unitName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'distinctUnitNames\' : "1"});';
				htmlOut += '</script></div>';
				break;
			case '3':	// Unit selector
				htmlOut += '<div id="unitSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب خدمت</legend>';
				htmlOut += '<span id="unitSelectionContent">';
				htmlOut += '<select name="serviceName" id="serviceName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += '$(\'#serviceName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'distinctServiceNames\' : "1"});';
				htmlOut += '</script></div>';
				break;
		}
		
		$('#reportForthType').append(htmlOut);
	});
	
	$('input:radio[name=incomes]').change(function() {
		//clear it!
		$('#unitSelectionContainer').remove();
		var chartSourceType = $('input:radio[name=incomes]:checked').val();
		var htmlOut = '';
		switch(chartSourceType) {
			case '1':
				break;
			case '2':	// Unit selector
				htmlOut += '<div id="unitSelectionContainer"><p style="font-weight: bold;">گام سوم:</p><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>انتخاب واحد</legend>';
				htmlOut += '<span id="unitSelectionContent">';
				htmlOut += '<select name="unitName" id="unitName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '</fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'distinctUnitNames\' : "1"});';
				htmlOut += '</script></div>';
				break;
		}
		
		$('#reportFifthType').append(htmlOut);
	});

});//end document.ready

/*------------------------------------------------------------------------------------------------------------------------------------*/
/*														FUNCTIONS																	*/

var townIndex = 0;
var centerIndex = 0;
var hygieneUnitIndex = 0;
var unitIndex = 0;

function addLocation(type) {
	var htmlOut = '';
	switch(type) {
		case 'town':
			htmlOut += '<div id="townSelectionContainer_' + townIndex + '"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>شهرستان | <a onclick="$(\'#townSelectionContainer_' + townIndex + '\').remove();return false;" href="#">حذف</a> | <a onclick="$(\'#townSelectionContent_' + townIndex + '\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
			htmlOut += '<span id="townSelectionContent_' + townIndex + '"><select name="townNameForTown_' + townIndex + '" id="townNameForTown_' + townIndex + '" class="validate first_opt_not_allowed"></select></span></fieldset>';
			htmlOut += '<script type="text/javascript">';
			htmlOut += '$(\'#townNameForTown_' + townIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += '</script></div>';
			townIndex++;
			break;
		case 'center':
			htmlOut += '<div id="centerSelectionContainer_' + centerIndex + '"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>مرکز | <a onclick="$(\'#centerSelectionContainer_' + centerIndex + '\').remove();return false;" href="#">حذف</a> | <a onclick="$(\'#centerSelectionContent_' + centerIndex + '\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
			htmlOut += '<span id="centerSelectionContent_' + centerIndex + '"><select name="townNameForCenter_' + centerIndex + '" id="townNameForCenter_' + centerIndex + '" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
			htmlOut += '<select name="centerNameForCenter_' + centerIndex + '" id="centerNameForCenter_' + centerIndex + '" class="validate first_opt_not_allowed"></select><br />';
			htmlOut += '<br /><fieldset style="width: 300px;"><legend>نوع هزینه‌های مرکز</legend>';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_0" value="drugs" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_0">داروها</label><br />';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_6" value="consumings" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_6">تجهیزات مصرفی</label><br />';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_1" value="nonConsumings" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_1">تجهیزات غیرمصرفی</label><br />';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_2" value="buildings" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_2">هزینه‌های ساختمان‌ها</label><br />';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_3" value="vehicles" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_3">هزینه‌های وسایل نقلیه</label><br />';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_4" value="general" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_4">هزینه‌های عمومی</label><br />';
			htmlOut += '<input type="checkbox" name="centerChargeTypes_' + centerIndex + '[]" id="centerChargeTypes_' + centerIndex + '_5" value="totalUnits" checked="checked" />';
			htmlOut += '<label for="centerChargeTypes_' + centerIndex + '_5">هزینه‌های واحدهای زیرمجموعه‌ی این مرکز</label>';
			htmlOut += '</fieldset></fieldset></span>';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForCenter_' + centerIndex + '\').empty().append(data);$(\'#centerNameForCenter_' + centerIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter_' + centerIndex + ' option:selected\').val()});});';
			htmlOut += '$(\'#townNameForCenter_' + centerIndex + '\').change(function() {$(\'#centerNameForCenter_' + centerIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter_' + centerIndex + ' option:selected\').val()});});';
			htmlOut += '</script></div>';
			centerIndex++;
			break;
		case 'hygieneUnit':
			htmlOut += '<div id="hygieneUnitSelectionContainer_' + hygieneUnitIndex + '"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>خانه بهداشت | <a onclick="$(\'#hygieneUnitSelectionContainer_' + hygieneUnitIndex + '\').remove();return false;" href="#">حذف</a> | <a onclick="$(\'#hygieneUnitSelectionContent_' + hygieneUnitIndex + '\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
			htmlOut += '<span id="hygieneUnitSelectionContent_' + hygieneUnitIndex + '"><select name="townNameForHygieneUnit_' + hygieneUnitIndex + '" id="townNameForHygieneUnit_' + hygieneUnitIndex + '" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
			htmlOut += '<select name="centerIdForHygieneUnit_' + hygieneUnitIndex + '" id="centerIdForHygieneUnit_' + hygieneUnitIndex + '" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
			htmlOut += '<select name="hygieneUnitName_' + hygieneUnitIndex + '" id="hygieneUnitName_' + hygieneUnitIndex + '" class="validate first_opt_not_allowed"></select><br />';
			htmlOut += '<br /><fieldset style="width: 200px;"><legend>نوع هزینه‌های خانه‌بهداشت</legend>';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_0" value="drugs" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_0">داروها</label><br />';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_6" value="drugs" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_6">تجهیزات مصرفی</label><br />';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_1" value="nonConsumings" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_1">تجهیزات غیرمصرفی</label><br />';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_2" value="buildings" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_2">هزینه‌های ساختمان‌ها</label><br />';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_3" value="vehicles" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_3">هزینه‌های وسایل نقلیه</label><br />';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_4" value="general" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_4">هزینه‌های عمومی</label><br />';
			htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes_' + hygieneUnitIndex + '[]" id="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_5" value="personnel" checked="checked" />';
			htmlOut += '<label for="hygieneUnitChargeTypes_' + hygieneUnitIndex + '_5">هزینه‌های پرسنل (بهورزان)</label>';
			htmlOut += '</fieldset></fieldset></span>';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForHygieneUnit_' + hygieneUnitIndex + '\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit_' + hygieneUnitIndex + ' option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerIdForHygieneUnit_' + hygieneUnitIndex + '\').empty().append(data);$(\'#hygieneUnitName_' + hygieneUnitIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit_' + hygieneUnitIndex + ' option:selected\').val()});});});';
			htmlOut += '$(\'#townNameForHygieneUnit_' + hygieneUnitIndex + '\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit_' + hygieneUnitIndex + ' option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerIdForHygieneUnit_' + hygieneUnitIndex + '\').empty().append(data);$(\'#hygieneUnitName_' + hygieneUnitIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit_' + hygieneUnitIndex + ' option:selected\').val()});});});';
			htmlOut += '$(\'#centerIdForHygieneUnit_' + hygieneUnitIndex + '\').change(function() {$(\'#hygieneUnitName_' + hygieneUnitIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit_' + hygieneUnitIndex + ' option:selected\').val()});});';
			htmlOut += '</script></div>';
			hygieneUnitIndex++;
			break;
		case 'unit':
			htmlOut += '<div id="unitSelectionContainer_' + unitIndex + '"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>واحد | <a onclick="$(\'#unitSelectionContainer_' + unitIndex + '\').remove();return false;" href="#">حذف</a> | <a onclick="$(\'#unitSelectionContent_' + unitIndex + '\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
			htmlOut += '<span id="unitSelectionContent_' + unitIndex + '"><select name="townNameForUnit_' + unitIndex + '" id="townNameForUnit_' + unitIndex + '" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
			htmlOut += '<select name="centerNameForUnit_' + unitIndex + '" id="centerNameForUnit_' + unitIndex + '" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
			htmlOut += '<select name="unitName_' + unitIndex + '" id="unitName_' + unitIndex + '" class="validate first_opt_not_allowed"></select><br />';
			htmlOut += '<br /><fieldset style="width: 200px;"><legend>نوع هزینه های واحد</legend>';
			htmlOut += '<input type="checkbox" name="unitChargeTypes_' + unitIndex + '[]" id="unitChargeTypes_' + unitIndex + '_0" value="personnel" checked="checked" disabled="disabled"/>';
			htmlOut += '<label for="unitChargeTypes_' + unitIndex + '_0">هزینه‌های پرسنل</label>';
			htmlOut += '</fieldset></fieldset></span>';
			htmlOut += '<script type="text/javascript">';
			htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
			htmlOut += 'ajaxPost.done(function(data) {' + '$(\'#townNameForUnit_' + unitIndex + '\').empty().append(data);' + 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit_' + unitIndex + ' option:selected\').val()});' + 'ajaxPost1.done(function(data) {' + '$(\'#centerNameForUnit_' + unitIndex + '\').empty().append(data);' + '$(\'#unitName_' + unitIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit_' + unitIndex + ' option:selected\').val()});' + '});' + '});';
			htmlOut += '$(\'#townNameForUnit_' + unitIndex + '\').change(function() {' + 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit_' + unitIndex + ' option:selected\').val()});' + 'ajaxPost1.done(function(data) {' + '$(\'#centerNameForUnit_' + unitIndex + '\').empty().append(data);' + '$(\'#unitName_' + unitIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit_' + unitIndex + ' option:selected\').val()});' + '});' + '});';
			htmlOut += '$(\'#centerNameForUnit_' + unitIndex + '\').change(function() {' + '$(\'#unitName_' + unitIndex + '\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit_' + unitIndex + ' option:selected\').val()});' + '});';
			htmlOut += '</script></div>';
			unitIndex++;
			break;
	}

	$('#reportFirstType').append(htmlOut);
}//END addLocation FUNCTION

var added = false;
function addLocationOnce(type) {
	var htmlOut = '';
	if(!added) {
		switch(type) {
			case 'town':
				htmlOut += '<div id="townSelectionContainer"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>شهرستان | <a onclick="$(\'#townSelectionContainer\').remove();added=false;return false;" href="#">حذف</a> | <a onclick="$(\'#townSelectionContent\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
				htmlOut += '<span id="townSelectionContent"><select name="townNameForTown" id="townNameForTown" class="validate first_opt_not_allowed"></select></span></fieldset>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += '$(\'#townNameForTown\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += '</script></div>';
				added = true;
				break;
			case 'center':
				htmlOut += '<div id="centerSelectionContainer"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>مرکز | <a onclick="$(\'#centerSelectionContainer\').remove();added=false;return false;" href="#">حذف</a> | <a onclick="$(\'#centerSelectionContent\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
				htmlOut += '<span id="centerSelectionContent"><select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed"></select>';
				htmlOut += '<select name="centerNameForCenter" id="centerNameForCenter" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '<br /><fieldset style="width: 300px;"><legend>نوع هزینه‌های مرکز</legend>';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_0" value="drugs" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_0">داروها</label><br />';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_6" value="consumings" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_6">تجهیزات مصرفی</label><br />';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_1" value="nonConsumings" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_1">تجهیزات غیرمصرفی</label><br />';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_2" value="buildings" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_2">هزینه‌های ساختمان‌ها</label><br />';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_3" value="vehicles" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_3">هزینه‌های وسایل نقلیه</label><br />';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_4" value="general" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_4">هزینه‌های عمومی</label><br />';
				htmlOut += '<input type="checkbox" name="centerChargeTypes[]" id="centerChargeTypes_5" value="totalUnits" checked="checked" />';
				htmlOut += '<label for="centerChargeTypes_5">هزینه‌های واحدهای زیرمجموعه‌ی این مرکز</label>';
				htmlOut += '</fieldset></fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForCenter\').empty().append(data);$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '$(\'#townNameForCenter\').change(function() {$(\'#centerNameForCenter\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
				htmlOut += '</script></div>';
				added = true;
				break;
			case 'hygieneUnit':
				htmlOut += '<div id="hygieneUnitSelectionContainer"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>خانه بهداشت | <a onclick="$(\'#hygieneUnitSelectionContainer\').remove();added=false;return false;" href="#">حذف</a> | <a onclick="$(\'#hygieneUnitSelectionContent\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
				htmlOut += '<span id="hygieneUnitSelectionContent"><select name="townNameForHygieneUnit" id="townNameForHygieneUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerIdForHygieneUnit" id="centerIdForHygieneUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="hygieneUnitName" id="hygieneUnitName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '<br /><fieldset style="width: 200px;"><legend>نوع هزینه‌های خانه بهداشت</legend>';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_0" value="drugs" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_0">داروها</label><br />';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_6" value="consumings" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_6">تجهیزات مصرفی</label><br />';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_1" value="nonConsumings" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_1">تجهیزات غیرمصرفی</label><br />';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_2" value="buildings" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_2">هزینه‌های ساختمان‌ها</label><br />';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_3" value="vehicles" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_3">هزینه‌های وسایل نقلیه</label><br />';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_4" value="general" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_4">هزینه‌های عمومی</label><br />';
				htmlOut += '<input type="checkbox" name="hygieneUnitChargeTypes[]" id="hygieneUnitChargeTypes_5" value="personnel" checked="checked" />';
				htmlOut += '<label for="hygieneUnitChargeTypes_5">هزینه‌های پرسنل (بهورزان)</label>';
				htmlOut += '</fieldset></fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {$(\'#townNameForHygieneUnit\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerIdForHygieneUnit\').empty().append(data);$(\'#hygieneUnitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit option:selected\').val()});});});';
				htmlOut += '$(\'#townNameForHygieneUnit\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerIdForHygieneUnit\').empty().append(data);$(\'#hygieneUnitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#townNameForHygieneUnit option:selected\').val()});});});';
				htmlOut += '$(\'#centerIdForHygieneUnit\').change(function() {$(\'#hygieneUnitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerIdForHygieneUnit option:selected\').val()});});';
				htmlOut += '</script></div>';
				added = true;
				break;
			case 'unit':
				htmlOut += '<div id="unitSelectionContainer"><fieldset style="width: 520px; margin: 0 auto 10px auto;"><legend>واحد | <a onclick="$(\'#unitSelectionContainer\').remove();added=false;return false;" href="#">حذف</a> | <a onclick="$(\'#unitSelectionContent\').fadeToggle(500);return false;" href="#">تغییر اندازه</a></legend>';
				htmlOut += '<span id="unitSelectionContent"><select name="townNameForUnit" id="townNameForUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="centerNameForUnit" id="centerNameForUnit" class="validate first_opt_not_allowed"></select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
				htmlOut += '<select name="unitName" id="unitName" class="validate first_opt_not_allowed"></select><br />';
				htmlOut += '<br /><fieldset style="width: 200px;"><legend>نوع هزینه‌های واحد</legend>';
				htmlOut += '<input type="checkbox" name="unitChargeTypes[]" id="unitChargeTypes_0" value="personnel" checked="checked" disabled="disabled"/>';
				htmlOut += '<label for="unitChargeTypes_0">هزینه‌های پرسنل</label>';
				htmlOut += '</fieldset></fieldset></span>';
				htmlOut += '<script type="text/javascript">';
				htmlOut += 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'reportingSource\' : "town"});';
				htmlOut += 'ajaxPost.done(function(data) {' + '$(\'#townNameForUnit\').empty().append(data);' + 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});' + 'ajaxPost1.done(function(data) {' + '$(\'#centerNameForUnit\').empty().append(data);' + '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit option:selected\').val()});' + '});' + '});';
				htmlOut += '$(\'#townNameForUnit\').change(function() {' + 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});' + 'ajaxPost1.done(function(data) {' + '$(\'#centerNameForUnit\').empty().append(data);' + '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit option:selected\').val()});' + '});' + '});';
				htmlOut += '$(\'#centerNameForUnit\').change(function() {' + '$(\'#unitName\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerNameForUnit option:selected\').val()});' + '});';
				htmlOut += '</script></div>';
				added = true;
				break;
		}
		$('#reportSecondType').append(htmlOut);
	}
	else
		alert('برای گزارش گیری نوع دوم فقط می توانید یک مکان انتخاب کنید. در صورت تمایل می توانید با حذف مکان فعلی، مکان دیگری را انتخاب نمایید.');
}//END addLocationOnce FUNCTION