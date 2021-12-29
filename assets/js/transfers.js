"use strict";
// Client-side validation of the new transfer form.

const submitData = (args) => {
  console.log(args);
  $.ajax({
    url: "/dashboard/add_transfer",
    type: "POST",
    data: {
      accountFrom: args[0],
      accountTo: args[1],
      amount: args[2],
      date: args[3],
      name: args[4]
    },
    success: function (response) {
      if (response === "2") { // created
        clearForm();
        $("#msg").removeClass("text-danger");
        $("#msg").addClass("text-success");
        $("#msg").text(`Transaction created. Redirecting you to your Dashboard...`);
        setTimeout(()=>{
          document.location.href = '/dashboard';
        }, 2500);
      } else if (response === '1') { // scheduled
        clearForm();
        $("#msg").removeClass("text-danger");
        $("#msg").addClass("text-success");
        $("#msg").text(`Transaction scheduled. Redirecting you to your Dashboard...`);
        setTimeout(()=>{
          document.location.href = '/dashboard';
        }, 2500);
      } else {
        console.log(response);
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
  const accountFrom = $('#accountFrom').val();
  const accountTo = $('#accountTo').val();
  const amount = parseFloat($("#amount").val()).toFixed(2);
  const date = $('#transferDate').val();
  const name = $("#name").val();
  const msgs = $(".msg");
  let inputs = [];

  // Clear error messages
  $.each(msgs, function () {
    $(this).text("");
  });

  // validate FROM account
  if (!accountFrom) {
    $('#accountFrom').prev().text(`Please select an account.`);
    errors = true;
  }

  // validate TO account
  if (!accountTo) {
    $('#accountTo').prev().text(`Please select an account.`);
    errors = true;
  }

  if (accountFrom === accountTo) {
    $('#accountFrom').prev().text(`Account From cannot be the same as Account To.`);
    $('#accountTo').prev().text(`Account To cannot be the same as Account From.`);
    errors = true;
  } else if (!errors) {
    inputs.push(accountFrom);
    inputs.push(accountTo);
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

  // validate date
  // TODO validate properly
  const now = new Date();
  const hr = now.getHours();
  const min = now.getMinutes();
  const sec = now.getSeconds();
  const dateArr = date.split('-');
  const transferDate = new Date(dateArr[0], dateArr[1]-1, dateArr[2], hr, min, sec+1);
  console.log($('#transferDate'));
  console.log(now);
  console.log(date);
  console.log(transferDate);
  if (!date) {
    $('#transferDate').prev().text(`Please enter a date.`);
    errors = true;
  } else if (transferDate < now) {
    $('#transferDate').prev().text(`Only today and future dates are allowed.`);
    errors = true;
  } else {
    inputs.push(date);
  }

  // validate name
  const reInvalidName = /[^a-zA-Z0-9 ]/g;
  if (name.trim() === "") {
    $("#name").prev().text(`This field cannot be empty.`);
    errors = true;
  } else if (reInvalidName.test(name)) {
    $("#name").prev().text(`Only letters, numbers and spaces are allowed.`);
    errors = true;
  } else {
    inputs.push(name);
  }

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
  $("#createTransferBtn").click(function (e) {
    handleSend(e);
  });
  $("#cancelBtn").click(function () {
    window.history.back();
  });
});
