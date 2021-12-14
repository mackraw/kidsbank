'use strict';
// Client-side validation to the contact form.

const submitData = (...args) => {
  $.ajax({
    url: '/contact/contact',
    type: 'POST',
    data: {
      name: args[0],
      email: args[1],
      subject: args[3],
      message: args[4],
    },
    success: function (response) {
      if (response === 'okay') {
        clearForm();
        $('#msg').removeClass('text-danger');
        $('#msg').addClass('text-success');
        $('#msg').text(`Thank you for contacting us. Your message was sent.`);
      } else {
        $('#msg').removeClass('text-success');
        $('#msg').addClass('text-danger');
        $('#msg').html('Processing Error!');
      }
    },
    error: function () {
      $('#msg').addClass('text-danger');
      $('#msg').html('Server Error!');
    },
  });
  return;
};

const validate = () => {
  let errors = false;
  const name = $('#name').val();
  const email = $('#email').val();
  const emailconf = $('#emailCheck').val();
  const inputs = $('.form-control');
  const msgs = $('.msg');

  // Clear error messages
  $.each(msgs, function () {
    $(this).text('');
  });

  // Check that inputs aren't blank
  $.each(inputs, function () {
    const trimmedInput = $(this).val().trim();
    $(this).val(trimmedInput);
    if (trimmedInput === '') {
      $(this).prev().text(`This field cannot be empty.`);
      errors = true;
    }
  });

  const reInvalidName = /[^a-zA-Z ]/g;
  if (reInvalidName.test(name)) {
    $('#name').prev().text(`Only letters and spaces are allowed.`);
    errors = true;
  }

  // validate email
  if (!validEmail(email)) {
    $('#email').prev().text(`Please enter a valid email address.`);
    errors = true;
  }
  if (email !== emailconf) {
    $('#emailCheck').prev().text(`Email addresses must match.`);
    errors = true;
  }

  const inputsText = inputs.map(function () {
    return this.value;
  });

  return [errors, inputsText];
};

const handleSend = (e) => {
  const [errorsPresent, inputs] = validate();

  if (errorsPresent) {
    e.preventDefault();
  } else {
    submitData(...inputs);
  }
};

$(document).ready(function () {
  $('#clearBtn').click(function () {
    clearForm();
  });
  $('#sendBtn').click(function (e) {
    handleSend(e);
  });
});