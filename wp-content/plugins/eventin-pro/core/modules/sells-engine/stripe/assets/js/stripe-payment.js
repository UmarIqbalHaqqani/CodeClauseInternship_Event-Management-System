// Fetches a payment intent and captures the client secret
jQuery(document).ready(function ($) {
    /**
     * Stripe Payment Object
     *
     * @var {Object}
     */
    StripePayment = {
        elements: '',
        emailAddress: '',
        returnUrl: '',
        amount: 0.0,
        currency: 'USD',
        invoice: '',
        orderId: 0,
        stripe: null,

        /**
         * Initialize stripe payment
         *
         * @param   {Object}  payment  Payment data object
         *
         * @return  void
         */
        async initialize(payment) {
            const { returnUrl, amount, currency, orderId, publishableKey } = payment;

            if ( ! publishableKey ) {
                alert('Please configure your stripe with valid publishable key');
                return false;
            }

            StripePayment.stripe = Stripe(publishableKey);
            StripePayment.returnUrl = returnUrl
            StripePayment.amount = amount
            StripePayment.currency = currency
            StripePayment.orderId = orderId

            // Get client secret;
            const result = await $.ajax({
                url: etn_pro_public_object.ajax_url,
                type: "POST",
                data: {
                    action: 'etn_payment_intent',
                    amount: StripePayment.amount,
                    currency: StripePayment.currency,
                },
            });

            const clientSecret = result.data.client_secret;

            if ( ! clientSecret ) {
                alert('you must pass a clientSecret');
                return;
            }
            
            $('#etn-stripe-wrap').show();
            $('#payment-form').on('submit', StripePayment.handleSubmit);
            $('#etn-stripe-close').on('click', StripePayment.closeModal);

            StripePayment.elements = StripePayment.stripe.elements({ clientSecret });


            const linkAuthenticationElement = StripePayment.elements.create("linkAuthentication");
            linkAuthenticationElement.mount("#link-authentication-element");

            const paymentElementOptions = {
                layout: "tabs",
            };

            const paymentElement = StripePayment.elements.create("payment", paymentElementOptions);
            paymentElement.mount("#payment-element");
        },

        /**
         * Payment handling
         *
         * @param   {Object}  e  [e description]
         *
         * @return  {void}     [return description]
         */
        async handleSubmit(e) {
            e.preventDefault();
            StripePayment.setLoading(true);

            const { error } = await StripePayment.stripe.confirmPayment({
                elements: StripePayment.elements,
                confirmParams: {
                    // Make sure to change this to your payment completion page
                    return_url: StripePayment.returnUrl,
                    receipt_email: StripePayment.emailAddress,
                },
            });

            if (error.type === "card_error" || error.type === "validation_error") {
                StripePayment.showMessage(error.message);
            } else {
                StripePayment.showMessage("An unexpected error occurred.");
            }

            StripePayment.setLoading(false);
        },

        /**
         * Check payment intent status
         *
         * @return  {void}
         */
        async checkStatus() {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if (!clientSecret) {
                return;
            }

            const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

            switch (paymentIntent.status) {
                case "succeeded":
                    StripePayment.showMessage("Payment succeeded!");
                    break;
                case "processing":
                    StripePayment.showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    StripePayment.showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    StripePayment.showMessage("Something went wrong.");
                    break;
            }
        },

        showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageContainer.textContent = "";
            }, 4000);
        },

        setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        },

        closeModal(e) {
            $('#etn-stripe-wrap').hide();
        }
    }

    window.StripePayment = StripePayment
});
