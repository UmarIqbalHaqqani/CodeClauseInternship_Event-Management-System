"use strict";

jQuery(document).ready(function ($) {
  const previousButton = document.querySelector("#rsvp-previous-btn");
  const nextButton = document.querySelector("#rsvp-next-btn");
  const submitButton = document.querySelector("#rsvp-submit-btn");
  const tabTargets = document.querySelectorAll(".rsvp-tab-item");
  const tabPanels = document.querySelectorAll(".rsvp-tabpanel");

  const validEmail =
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  const isEmpty = (str) => !!parseInt(str);
  let currentStep = 0;

  const numberOfAttendeeWrapper = document.querySelector(
    ".number-of-attendee-wrapper"
  );
  const notGoingReason = document.querySelector(".not-going-reason-wrapper");

  jQuery('input:radio[name="etn_rsvp_value"]').on("change", function (e) {
    if (e.currentTarget.value === "not_going") {
      nextButton.classList.add("hidden");
      numberOfAttendeeWrapper.classList.add("hidden");
      notGoingReason.classList.remove("hidden");
      submitButton.classList.remove("hidden");
      submitButton.removeAttribute("disabled");
    } else {
      nextButton.classList.remove("hidden");
      numberOfAttendeeWrapper.classList.remove("hidden");
      notGoingReason.classList.add("hidden");
      submitButton.classList.add("hidden");
      submitButton.setAttribute("disabled", true);
    }
  });

  // Toggle attendee section
  const attendeeToggler = jQuery(".view-all-button");
  const attendeeGrid = jQuery(".rsvp-attendee-grid");

  attendeeToggler.on("click", function () {
    attendeeGrid.slideToggle(function () {
      attendeeToggler.text(
        attendeeGrid.is(":visible") ? "Hide Attendees" : "View All attendees"
      );
    });
  });

  // Validate first input on load
  validateEntry();

  // Next: Change UI relative to current step and account for button permissions
  if (nextButton == null) {
    return;
  }

  nextButton.addEventListener("click", (event) => {
    event.preventDefault();

    // Hide current tab
    tabPanels[currentStep].classList.add("hidden");
    tabTargets[currentStep].classList.remove("active");
    tabTargets[currentStep].classList.add("done");

    // Show next tab
    tabPanels[currentStep + 1].classList.remove("hidden");
    tabTargets[currentStep + 1].classList.add("active");
    currentStep += 1;

    validateEntry();
    updateStatusDisplay();
    // generate multiple form based on attendee number
    obj.generate_attendee_form($("#rsvp-next-btn"));
  });

  // Previous: Change UI relative to current step and account for button permissions
  previousButton.addEventListener("click", (event) => {
    event.preventDefault();

    // Hide current tab
    tabPanels[currentStep].classList.add("hidden");
    tabTargets[currentStep].classList.remove("active");

    // Show previous tab
    tabPanels[currentStep - 1].classList.remove("hidden");
    tabTargets[currentStep - 1].classList.add("active");
    tabTargets[currentStep - 1].classList.remove("done");
    currentStep -= 1;

    nextButton.removeAttribute("disabled");
    updateStatusDisplay();
    // re-open the response form
    $(".response-form").fadeIn();
  });

  function updateStatusDisplay() {
    // If on the last step, hide the next button and show submit
    if (currentStep === tabTargets.length - 1) {
      nextButton.classList.add("hidden");
      previousButton.classList.remove("hidden");
      submitButton.classList.remove("hidden");
      validateEntry();

      // If it's the first step hide the previous button
    } else if (currentStep === 0) {
      nextButton.classList.remove("hidden");
      previousButton.classList.add("hidden");
      submitButton.classList.add("hidden");
      // In all other instances display both buttons
    } else {
      nextButton.classList.remove("hidden");
      previousButton.classList.remove("hidden");
      submitButton.classList.add("hidden");
    }
  }

  // Validate form entries.
  function validateEntry() {
    if (submitButton === null) {
      return;
    }

    submitButton.setAttribute("disabled", true);
    nextButton.setAttribute("disabled", true);

    // Validate attendee number name and email address
    const numberOfAttendee = document.querySelector(".number-of-attendee");
    const attendeeName = document.querySelector(".attendee-name");
    const attendeeEmail = document.querySelector(".attendee-email");
    const validateNameAndEmail = [
      numberOfAttendee,
      attendeeName,
      attendeeEmail,
    ];

    validateNameAndEmail.forEach((inputElement) => {
      inputElement.addEventListener("keyup", function () {
        const RSVPAvailability = parseInt(
          numberOfAttendee.getAttribute("data-available-rsvp")
        );
        const isAvailable = RSVPAvailability >= numberOfAttendee.value;
        const RSVPRemaining = parseInt(
          numberOfAttendee.getAttribute("data-remaining-capacity")
        );
        const isRemaining = RSVPRemaining >= numberOfAttendee.value;
        const isValid = numberOfAttendee.value > 0;
        const isEmailValid = validEmail.test(
          String(attendeeEmail?.value).toLowerCase()
        );

        // Handel form element error message
        const numberErrorNote = document.querySelector(
          ".attendee-number-error"
        );
        const nameError = document.querySelector(".attendee-name-error");
        const emailError = document.querySelector(".attendee-email-error");

        nameError.innerHTML = "";
        emailError.innerHTML = "";
        numberErrorNote.innerHTML = "";

        attendeeName.classList.remove("input-error");
        attendeeEmail.classList.remove("input-error");
        numberOfAttendee.classList.remove("input-error");

        // Show error message based on input state.
        if (!isValid) {
          numberErrorNote.innerHTML = "Please enter a positive number.";
          numberOfAttendee.classList.add("input-error");
        } else if (!isAvailable) {
          numberErrorNote.innerHTML =
            "Input quantity exceeded max submission limit.";
          numberOfAttendee.classList.add("input-error");
        } else if (!isRemaining) {
          numberErrorNote.innerHTML =
            "Input quantity exceeded max capacity limit.";
          numberOfAttendee.classList.add("input-error");
        }
        if (attendeeName.value === "") {
          nameError.innerHTML = "Please enter attendee name.";
          attendeeName.classList.add("input-error");
        }
        if (!isEmailValid) {
          emailError.innerHTML = "Enter a valid email address.";
          attendeeEmail.classList.add("input-error");
        }

        // Disable next button based on input values
        if (
          !isEmpty(numberOfAttendee.value) ||
          !isAvailable ||
          !isEmailValid ||
          attendeeName.value === ""
        ) {
          nextButton.setAttribute("disabled", true);
          submitButton.setAttribute("disabled", true);
        } else {
          nextButton.removeAttribute("disabled");
          submitButton.removeAttribute("disabled");
        }
      });
    });
  }

  /*
   * Select element
   */
  var rsvp_form = $(".rsvp_submit");
  var rsvp_submit_btn = $(".rsvp-submit-btn");
  var attendee_checked = $("#different-attendee-checkbox");
  var submission_message = $(".rsvp-submission-message");
  var rsvp_tab = $(".rsvp-tab-wrapper");

  var obj = {
    /*
     * Re format data
     */
    serializeObject(serializedArray) {
      var serializedObj = {};
      const result = serializedArray.reduce((acc, d) => {
        let idx = acc.findIndex((a) => a.name === d.name);
        let val = d.value;

        if (idx > -1) {
          acc[idx].value.push(val);
        } else {
          if (d.name.includes("[]")) {
            acc.push({ name: d.name, value: [val] });
          } else {
            acc.push({ name: d.name, value: val });
          }
        }

        return acc;
      }, []);

      result.forEach(function (i) {
        if (i.name.includes("[]")) {
          var input_name = i.name.replace("[]", "");
          serializedObj[input_name] = i.value;
        } else {
          serializedObj[i.name] = i.value;
        }
      });

      return serializedObj;
    },

    /**
     * get total attendee
     * @returns
     */
    get_total_attendee() {
      var attendee_num = parseInt($("#number-of-attendee").val());
      var total_form = attendee_num <= 1 ? 1 : attendee_num;
      return total_form;
    },

    /**
     * Append attendee form based on attendee number
     * @returns
     */
    attendee_form_generate() {
      var is_checked = true;
      var submit_btn = $("#rsvp-submit-btn");
      if (attendee_checked) {
        var name = "";
        var email = "";
        attendee_checked.on("change", function () {
          var total_form = obj.get_total_attendee();
          is_checked = attendee_checked.is(":checked");
          if (is_checked) {
            attendee_checked.attr("checked", true);
            name = $(`#attendee-name-0`).val();
            email = $(`#attendee-email-0`).val();
            submit_btn.attr("disabled", false);
          } else {
            name = "";
            email = "";
            attendee_checked.removeAttr("checked");
            submit_btn.attr("disabled", true);
          }

          for (let index = 0; index < total_form; index++) {
            const element = index + 1;
            // remove data
            $(`#attendee-name-${element}`).val(name);
            $(`#attendee-email-${element}`).val(email);
          }
        });
      }
      return is_checked;
    },

    /**
     * Get attendee value and check validation
     */
    get_attendee_value() {
      var total_form = obj.get_total_attendee();
      for (let index = 0; index <= total_form; index++) {
        const attendee_name = $(`#attendee-name-${index}`);
        const attendee_email = $(`#attendee-email-${index}`);
        const nameError = $(`.attendee-name-error-${index}`);
        const emailError = $(`.attendee-email-error-${index}`);

        const attendee_name_value = attendee_name.val();
        const attendee_email_value = attendee_email.val();
        const isEmailValid = validEmail.test(
          String(attendee_email_value).toLowerCase()
        );

        if (attendee_name_value === "") {
          attendee_name.addClass("input-error");
          nameError.text("Please enter attendee name.");
        } else {
          attendee_name.removeClass("input-error");
          nameError.text("");
        }
        if (attendee_email_value === "" || !isEmailValid) {
          attendee_email.addClass("input-error");
          emailError.text("Enter a valid email address.");
        } else {
          attendee_email.removeClass("input-error");
          emailError.text("");
        }

        // Disable next button based on input values
        if (attendee_name_value === "" || !isEmailValid) {
          submitButton.setAttribute("disabled", true);
        } else {
          submitButton.removeAttribute("disabled");
        }
      }
    },

    /**
     * Generate attendee form
     * @param {*} e
     */
    generate_attendee_form($this) {
      var total_form = obj.get_total_attendee();
      var html = "";
      var name = "";
      var email = "";

      if (parseInt(total_form) > 0) {
        var is_checked = true;
        if (is_checked) {
          name = $(`#attendee-name-0`).val();
          email = $(`#attendee-email-0`).val();
        } else {
          name = "";
          email = "";
        }
        // remove existing wrapper and then insert
        $(".rsvp-attendee-wrapper").remove();
        for (let index = 0; index < total_form; index++) {
          html += `<div class="rsvp-attendee-wrapper">
							<h2>${localized_rsvp_data?.attendee_title} - ${index + 1}</h2>
							<div class="rsvp-form-element">
								<label for="attendee-name-${index + 1}">Name</label>
								<input name="attendee_name[]" id="attendee-name-${
                  index + 1
                }" type="text" value='${name}' placeholder="Enter name of attendee">
								<div class="rsvp-error-message attendee-name-error-${index + 1}"></div>
							</div>
							<div class="rsvp-form-element">
								<label for="attendee-email-${index + 1}">Email</label>
								<input name="attendee_email[]" id="attendee-email-${
                  index + 1
                }" type="email" value='${email}' placeholder="Enter email of attendee">
								<div class="rsvp-error-message attendee-email-error-${index + 1}"></div>
							</div>

						</div>`;
        }
        $this.fadeOut();
        $(".response-form").fadeOut();
        $(".rsvp-attendee-form").empty().append(html);
        $(".rsvp-submit-btn").removeClass("etn-d-none").fadeIn();
        // get attendee value
        obj.get_attendee_value();
      }
    },

    /*
     * Submit data
     */
    submit_rsvp_form(e) {
      e.preventDefault();

      // receive form data
      var values = obj.serializeObject($(this).serializeArray());

      $.ajax({
        method: "POST",
        url: localized_rsvp_data?.ajax_url || "",
        data: { action: "etn_rsvp_insert", fields: JSON.stringify(values) },
        beforeSend: function () {
          rsvp_submit_btn.addClass("etn-button-loading");
        },
        success: function (response) {
          rsvp_submit_btn.removeClass("etn-button-loading");
          rsvp_tab.fadeOut();
          rsvp_form.fadeOut();
          submission_message.fadeIn();
        },
      });
    },
  };

  /**
   * Rsvp form submit from frontend
   */
  rsvp_form.submit(obj.submit_rsvp_form);

  /**
   * Rsvp attendee form check un/check
   */
  obj.attendee_form_generate();

  /**
   * Generate attendee form on next click
   */
  $(".rsvp-next-btn").on("click", function () {
    obj.generate_attendee_form($(this));
  });

  /**
   * On key up validation
   */

  if ($(".rsvp-attendee-form").length > 0) {
    $(".rsvp-attendee-form").on("keyup", function () {
      obj.get_attendee_value();
    });
  }
});
