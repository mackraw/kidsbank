"use strict";
// Client-side validation to the login form.

const submitData = (args) => {
  $.ajax({
    url: "/dashboard/new_account",
    type: "POST",
    data: {
      name: args[0],
      type: args[1],
    },
    success: function (response) {
      if (response === "okay") {
        clearForm();
        $("#msg").removeClass("text-danger");
        $("#msg").addClass("text-success");
        $("#msg").text(`New account created. Redirecting you to Dashboard...`);
        setTimeout(()=>{
          location.href = "dashboard";
        }, 1500);
      } else {
        $("#msg").removeClass("text-success");
        $("#msg").addClass("text-danger");
        $("#msg").html("Processing Error!");
      }
    },
    error: function () {
        $("#msg").removeClass("text-success");
        $("#msg").addClass("text-danger");
      $("#msg").html("Server Error!");
    },
  });
  return;
};

const validate = () => {
  let errors = false;
  const name = $("#name").val();
  const msgs = $(".msg");
  const accountType = $('input:radio');
  let inputs = [];

  // Clear error messages
  $.each(msgs, function () {
    $(this).text("");
  });

  if (name.trim() === "") {
    $("#name").prev().text(`This field cannot be empty.`);
    errors = true;
  }
  
  const reInvalidName = /[^a-zA-Z ]/g;
  if (reInvalidName.test(name)) {
    $("#name").prev().text(`Only letters and spaces are allowed.`);
    errors = true;
  } else {
    inputs.push(name);
  }

  // check that one radio button is selected
  if (!accountType[0].checked && !accountType[1].checked) {
    $("#accountTypeSelection").prev().text(`Please select account type.`);
    errors = true;
  }

  // push selection to inputs array
  $.each(accountType, function () {
    if ($(this)[0].checked) {
      inputs.push($(this)[0].value);
    }
  })

  return [errors, inputs];
};

const handleSend = (e) => {
  const [errorsPresent, inputs] = validate();

  if (errorsPresent) {
    e.preventDefault();
  } else {
    submitData(inputs);
  }
};

$(document).ready(function () {
  $("#createAccountBtn").click(function (e) {
    handleSend(e);
  });
  $("#cancelBtn").click(function () {
    location.href = "dashboard";
  });
});
