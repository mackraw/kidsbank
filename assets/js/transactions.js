"use strict";
// Client-side validation of the new transaction form.

const submitData = (args) => {
  $.ajax({
    url: "/dashboard/add_transaction",
    type: "POST",
    data: {
      name: args[0],
      amount: args[1],
      account: args[2],
      type: args[3],
    },
    success: function (response) {
      if (response === "okay") {
        clearForm();
        $("#msg").removeClass("text-danger");
        $("#msg").addClass("text-success");
        $("#msg").text(`Transaction created. Redirecting you to your Dashboard...`);
        setTimeout(()=>{
          document.location.href = '/dashboard';
        }, 2500);
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
  const amount = parseFloat($("#amount").val()).toFixed(2);
  const accountId = parseInt($("#account_id").val());
  const transactionType = $('input:radio');
  const msgs = $(".msg");
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
  if (!transactionType[0].checked && !transactionType[1].checked) {
    $("#transactionTypeSelection").prev().text(`Please select transaction type.`);
    errors = true;
  }

  // validate amount
  const reValidAmount = /(((\d{0,3},?)?\d{0,3}){0,9}.?\d{2}?)|\d/g;
  if (!reValidAmount.test(amount)) {
    $('#amount').prev().text(`Please enter a valid amount.`);
    errors = true;
  } else if (amount <= 0) {
    $("#amount").prev().text(`Amount must be greater than zero.`);
    errors = true;
  } else {
    inputs.push(amount);
  }

  inputs.push(accountId);

  // push selection to inputs array
  $.each(transactionType, function () {
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
  $("#createTransactionBtn").click(function (e) {
    handleSend(e);
  });
  $("#cancelBtn").click(function () {
    window.history.back();
  });
});
