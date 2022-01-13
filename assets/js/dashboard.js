"use strict";
// Client-side validation to the new account form.

const populateInterest = () => {
  const accountType = $('input:radio');
  const interest = $("#interest");
  let type = '';
  $.each(accountType, function () {
    if ($(this)[0].checked) {
      type = $(this)[0].value;
    }
  })
  switch(type) {
    case 'checking':
      interest.html('');
      interest.append('<option value="">--Please choose the interest rate--</option>');
      interest.append('<option value="0">0.0%</option>');
      interest.append('<option value="0.1">0.1%</option>');
      interest.append('<option value="0.2">0.2%</option>');
      break;
    case 'credit':
      interest.html('');
      interest.append('<option value="">--Please choose the interest rate--</option>');
      interest.append('<option value="14">14%</option>');
      interest.append('<option value="19">19%</option>');
      interest.append('<option value="27">27%</option>');
      break;
    case 'savings':
      interest.html('');
      interest.append('<option value="">--Please choose the interest rate--</option>');
      interest.append('<option value="1">1%</option>');
      interest.append('<option value="2">2%</option>');
      interest.append('<option value="3">3%</option>');
      break;
  }
}

const submitData = (args) => {
  $.ajax({
    url: "/accounts/new_account",
    type: "POST",
    data: {
      name: args[0],
      interest: args[1],
      type: args[2],
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
  const interest = parseFloat($("#interest").val());
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
  
  const reInvalidName = /[^a-zA-ZÀ-ſ ]/g;
  if (reInvalidName.test(name)) {
    $("#name").prev().text(`Only letters and spaces are allowed.`);
    errors = true;
  } else {
    inputs.push(name);
  }

  // check that one radio button is selected
  if (!accountType[0].checked && !accountType[1].checked && !accountType[2].checked) {
    $("#accountTypeSelection").prev().text(`Please select account type.`);
    errors = true;
  }

  // validate interest rate
  if (Number.isNaN(interest)) {
    $("#interest").prev().text(`Please select an interest rate.`);
    errors = true;
  } else {
    inputs.push(interest);
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
  $('input:radio').change(function (e) {
    populateInterest(e);
  });
});
