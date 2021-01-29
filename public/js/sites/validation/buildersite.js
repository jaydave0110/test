$(document).ready(function() {
	$(document).on('submit', 'form#frmAdminBuilderSite', function() {

		/*if ($("select[name='company_id']").val() == '') {
			nextstep(1);
			alert('Please select site belongs to which company.');
			$("select[name='company_id']").focus();
			return false;
		}	*/
		
		if ($("input[name='site_name']").val() == '') {
			nextstep(1);
			alert('Please enter site name to proceed.');
			$("input[name='site_name']").focus();
			return false;
		}

		if ($("select[name='price_status']").val() == '') {
			nextstep(1);
			alert('Please select how price will be display for site properties.');
			$("select[name='price_status']").focus();
			return false;
		}

		

		if ($("select[name='state_id']").val() == '') {
			nextstep(1);
			alert('Please select site belongs to which state.');
			$("select[name='state_id']").focus();
			return false;
		}

		if ($("select[name='city_id']").val() == '') {
			nextstep(1);
			alert('Please select site belongs to which city.');
			$("select[name='city_id']").focus();
			return false;
		}

		if ($("select[name='area_id']").val() == '') {
			nextstep(1);
			alert('Please select site belongs to which area.');
			$("select[name='area_id']").focus();
			return false;
		}
		
		if ($("input[name='address']").val() == '') {
			nextstep(1);
			alert('Please enter site address or you can search in map it will be filled automatic');
			$("input[name='address']").focus();
			return false;
		}

		if ($("input[name='latitude']").val() == '') {
			nextstep(1);
			alert('Please enter site latitude or you can search in map it will be filled automatic.');
			$("input[name='latitude']").focus();
			return false;
		}

		if ($("input[name='longitude']").val() == '') {
			nextstep(1);
			alert('Please enter site longitude or you can search in map it will be filled automatic.');
			$("input[name='longitude']").focus();
			return false;
		}

		if ($(".propertyImageCard").length == 0) {
			if ($("input[name='temp_images[]']").length == 0) {
				nextstep(1);
				alert('Please select atleast one property image to upload.');
				return false;
			}
		}

		if (!$("input[name='loan_approval']").is(':checked')) {
			nextstep(2);
			alert('Please select loan approval status.');
			return false;
		}

		if (!$("input[name='possession_status']").is(':checked')) {
			nextstep(3);
			alert('Please select site possession status.');
			return false;
		}

		console.log($("input[name='possession_status']").val());
		// if ($("input[name='possession_status']").val() != '' && $("input[name='possession_status']").val() != 1 ) {
		// 	if ($("select[name='possession_month']").val() == '' || $("select[name='possession_year']").val() == '') {
		// 		nextstep(3);
		// 		alert('Please provide possession month and year details.');
		// 		$("select[name='possession_month']").focus();
		// 		return false;
		// 	}
		// }

		if ($("select[name='sample_house']").val() == '') {
			nextstep(3);
			alert('Please select sample house status.');
			$("select[name='sample_house']").focus();
			return false;
		}

		if ($("select[name='sample_house']").val() == '2') {
			if ($("select[name='sample_house_month']").val() == '' || $("select[name='sample_house_year']").val() == '') {
				nextstep(3);
				alert('Please provide details when sample house will be available.');
				$("select[name='sample_house_month']").focus();
				return false;
			}
		}

		if (!$("input[name='water_supply']").is(':checked')) {
			nextstep(5);
			alert('Please select water supply availability.');
			return false;
		}

		if (!$("input[name='power_backup']").is(':checked')) {
			nextstep(5);
			alert('Please select power backup availability.');
			return false;
		}

	});

})