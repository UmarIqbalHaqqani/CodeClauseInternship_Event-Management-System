jQuery(document).ready(function ($) {
	"use strict";

	var stripe_obj = {
		// submit ajax form for attendee
		submit_attendee() {
			if ( etn_pro_public_object == null ) {
				return;
			}

			if(etn_pro_public_object.attendee_registration_option){
				var button_class = '.attendee_submit';
				var form_class = 'form.attende_form';
			} else {
				var button_class = '.etn-add-to-cart-block';
				var form_class = 'form.etn-event-form-parent';
			}

			$(button_class).on('click', async function(e){
				e.preventDefault();
				var $this = $(this);
					var name_arr = [];
					$.each(attendee_data,function( index , value ) {
						var match = value.name.match(/\[\]/);
						if ( match !== null && match.length > 0 && typeof match[0] !=="undefined"  ) {
							var new_value = value.name.replace(/\[\]/g,'');
							name_arr.push(new_value);
						}
					});

					$.each(name_arr,function( index , value ) {
						var input_name = $("input[name='"+ value +"[]'");
						if ( input_name.length > 0 ) {
							if ( input_name.attr('type') == "checkbox" || input_name.attr('type') == "radio" ) {
									var new_value = [];
									input_name.each(function() {
										if (this.checked) {
											new_value.push(this.value)
										}
									});

							} else {
								var new_value = input_name.map(function(){return $(this).val();}).get()
							}
							attendee_data.push({name:value,value:new_value});
						}
					});

				var attendee_data = $(form_class).serializeArray();
				var formData = new FormData($('#purchase_ticket_form')[0]);
				formData.append('action', 'insert_attendee_stripe');
				

				var name_arr = [];
				$.each(attendee_data,function( index , value ) {
					var match = value.name.match(/\[\]/);
					if ( match !== null && match.length > 0 && typeof match[0] !=="undefined"  ) {
						var new_value = value.name.replace(/\[\]/g,'');
						name_arr.push(new_value);
					}
				});

				$.each(name_arr,function( index , value ) {
					var input_name = $("input[name='"+ value +"[]'");
					if ( input_name.length > 0 ) {
						var new_value = input_name.map(function(){return $(this).val();}).get();
						attendee_data.push({name:value,value:new_value});
					}
				});

				formData.append('attendee_data', attendee_data);

				var post_object = {
					url: etn_pro_public_object.ajax_url,
					type: "POST",
					data: {
						action: 'insert_attendee_stripe',
						attendee_data: attendee_data,
					},
					beforeSend: function() {
						// setting a timeout
						$this.addClass('etn-button-loading');
						$this.css('cursor', 'wait');
					},
					success: function (response) {
						if (response !== null && response.data.success == 1
						&&  typeof response.data.data !=="undefined"  ) {
								$this.removeClass('etn-button-loading');
						}
					},
				};

				if ( ! etn_pro_public_object.is_enable_attendee_registration ) {
					post_object['data'] = formData;
					post_object['processData'] = false;
					post_object['contentType'] = false;
				}
				
				const result = await $.ajax( post_object );

				let order_id =  result.data.data.order_id
				let invoice = result.data.data.check_id
				let event_id = result.data.data.event_id

				const payment_options = {
					amount: result.data.data.etn_total_price,
					currency: result.data.data.currency_code,
					orderId: order_id,
					returnUrl: localized_stripe_data.redirect_url + '?order_id=' + order_id + '&invoice=' + invoice + '&event_id=' + event_id,
					invoice: invoice,
					publishableKey: localized_stripe_data.stripe_publishable_key
				}

				StripePayment.initialize(payment_options);
			});

		},

		/**
		 * Processing stripe action
		 *
		 * @param ''
		 * @returns {boolean}
		 */
		stripe_actions(res) {
			var num_decimals = res.num_decimals;
			var amount = '';
			if(num_decimals == 0){
				amount = (Number(res.etn_total_price));
			} else {
				amount = (Number(res.etn_total_price) * 100);
			}
			var handler = StripeCheckout.configure({
				key: res.keys,
				image: res.image_url,
				locale: 'auto',
				token: function (token) {
					if(!token.id) {
						$(".attendee-title").after("<div class='etn-stripe-error'>Token Id is invalid</div>");
						$(".etn-stripe-error").delay(2000).fadeOut('slow');
						$('html, body').animate({
							scrollTop: $(".attendee-title").offset().top
						}, 2000);

						return;
					}

					res.stripe_token = token.id; 

					let order_id = res.order_id;
					let invoice  = res.check_id;
					let currency_code = res.currency_code;

					let data_post = {
						action: 'stripe_payment_transaction',
						token: token.id,
						order_id: order_id,
						event_id: res.event_id,
						sandbox: res.sandbox,
						security: localized_stripe_data.stripe_payment_nonce,
						currency_code: currency_code,
					};

					jQuery.ajax({
						type: 'post',
						data: data_post,
						url: localized_stripe_data.ajax_url,
						beforeSend: function (xhr) {
							$('.single-etn, .etn-attendee-registration-page').addClass('etn-attendee-registration-loading')
						},
						success: function (res) {
							let msg_text 	= localized_stripe_data.common_err_msg;
							let res_data    = res.data;
							let res_content = res_data.content;
							let msg         = res_data.messages[0];

							if(msg != undefined) {
								msg_text 	= msg;
							}
							if(res.success && res_data.status_code == 1) {
								window.location.href = localized_stripe_data.redirect_url + '?order_id=' + order_id + '&invoice=' + invoice;
							} else {
								alert(msg_text);
								window.location.reload();
							}
						},
						error: function (res) {
							alert(localized_stripe_data.common_err_msg);
							window.location.reload();
						}
					});
				}
			});

			handler.open({
				name: String(res.event_name),
				amount: amount,
				currency: res.currency_code
			});


			window.addEventListener('popstate', function () {
				handler.close();
			});

		}

	}

	stripe_obj.submit_attendee();

});
