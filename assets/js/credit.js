"use strict";
// Client-side validation of the new purchase form.

const submitPayment = (args) => {
  console.log(args);
  $.ajax({
    url: "/credit/add_payment",
    type: "POST",
    data: {
      accountFrom: args[0],
      accountTo: args[1],
      amount: args[2],
      name: args[3],
    },
    success: function (response) {
      if (response === "okay") { // created
        clearForm();
        $("#msg").removeClass("text-danger");
        $("#msg").addClass("text-success");
        $("#msg").text(`Transaction processed. Redirecting you to your Dashboard...`);
        setTimeout(()=>{
          document.location.href = '/dashboard';
        }, 2500);
      } else if (response === '1') {
        $("#msg").removeClass("text-success");
        $("#msg").addClass("text-danger");
        $("#msg").html("Insufficient funds!");
      } else if (response === '2') {
        $("#msg").removeClass("text-success");
        $("#msg").addClass("text-danger");
        $("#msg").html("Incorrect Payment Amount!");
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

const submitPurchase = (args) => {
  console.log(args);
  $.ajax({
    url: "/transactions/add_transaction",
    type: "POST",
    data: {
      account: args[0],
      type: args[1],
      amount: args[2],
      name: args[3],
    },
    success: function (response) {
      if (response === "okay") {
        clearForm();
        $("#msg").removeClass("text-danger");
        $("#msg").addClass("text-success");
        $("#msg").text(`Purchase created. Redirecting you to your Dashboard...`);
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
  const accountId = parseInt($("#account_id").val());
  const accountFrom = parseInt($('#accountFrom').val());
  const transType = $("#transType").val();
  const amount = parseFloat($("#amount").val()).toFixed(2);
  const name = $("#name").val();
  const msgs = $(".msg");
  let inputs = [];

  // Clear error messages
  $.each(msgs, function () {
    $(this).text("");
  });

  
  // validate account FROM
  if (window.location.pathname.includes('newpayment')) {
    if (!accountFrom) {
      $('#accountFrom').prev().text(`Please select an account.`);
      errors = true;
    } else {
      inputs.push(accountFrom);
    }
  }

  inputs.push(accountId);
  
  if (window.location.pathname.includes('newpurchase')) {
    inputs.push(transType);
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

  // validate name
  if (name.trim() === "") {
    $("#name").prev().text(`This field cannot be empty.`);
    errors = true;
  }
  
  const reInvalidName = /[^a-zA-ZÀ-ſ0-9 ]/g;
  if (reInvalidName.test(name)) {
    $("#name").prev().text(`Only letters, numbers and spaces are allowed.`);
    errors = true;
  } else {
    inputs.push(name);
  }

  return [errors, inputs];
};

const handlePayment = (e) => {
  const [errorsPresent, inputs] = validate();
  console.log(inputs);


  if (errorsPresent) {
    e.preventDefault();
  } else {
    submitPayment(inputs);
  }
};

const handlePurchase = (e) => {
  const [errorsPresent, inputs] = validate();
  console.log(inputs);

  if (errorsPresent) {
    e.preventDefault();
  } else {
    submitPurchase(inputs);
  }
};

$(document).ready(function () {
  $("#createPaymentBtn").click(function (e) {
    handlePayment(e);
  });
  $("#createPurchaseBtn").click(function (e) {
    handlePurchase(e);
  });
  $("#cancelBtn").click(function () {
    window.history.back();
  });
});
