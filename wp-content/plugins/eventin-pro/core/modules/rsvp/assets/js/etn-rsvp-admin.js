'use strict';

jQuery(document).ready(function ($) {

	/*
	* Select element
	*/
	var rsvp_invitation_form = $(".rsvp_invitation_submit");
	var rsvp_invitation_btn = $(".etn-btn-send-invitation");
	var rsvp_invitation_message = $(".invitation-loader");

	var obj = {
		/*
		* Re format data
		*/
		serializeObject(serializedArray) {
			var serializedObj = {};

			serializedArray.forEach(function (i) {
				var inputArray = [];
				if (i.name.indexOf("[]") >=0){
					inputArray.push(i.value);
					var input_name = i.name.replace('[]','');
					serializedObj[input_name] = inputArray;
				}else{
					serializedObj[i.name] = i.value;
				}
			});

			return serializedObj;
		},

		/*
		* Submit data
		*/
		submit_invitation_rsvp_form(e) {
			e.preventDefault();

			// receive form data
			var values = obj.serializeObject($(this).serializeArray());

			$.ajax({
				method: "POST",
				url: localized_rsvp_admin_data?.ajax_url || '',
				data: { action: "send_invitation_email", fields: JSON.stringify(values) },
				beforeSend: function () {
					rsvp_invitation_btn.addClass("etn-button-loading");
					rsvp_invitation_message.html("");
				},
				success: function (response) {
					rsvp_invitation_btn.removeClass("etn-button-loading");
					rsvp_invitation_message.html(response.data.message);
				}
			})

		}
	};

	/**
	 * Rsvp invitation form submit from backend
	 */
	rsvp_invitation_form.submit(obj.submit_invitation_rsvp_form);

});
