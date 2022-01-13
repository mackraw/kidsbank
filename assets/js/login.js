'use strict';
// Client-side validation to the login form.

const submitData = (...args) => {
  $.ajax({
    url: '/users/login',
    type: 'POST',
    data: {
      email: args[0],
      password: args[1],
    },
    success: function (response) {
      if (response === 'okay') {
        $('#msg').removeClass('text-danger');
        $('#msg').addClass('text-success');
        $('#msg').html('Logging you in...');
        location.href='/dashboard';
      } else {
        $('#msg').removeClass('text-success');
        $('#msg').addClass('text-danger');
        $('#msg').html('User not found or incorrect password.');
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
  const email = $('#email').val();
  const pass = $('#password').val();
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

  // validate email
  if (!validEmail(email)) {
    $('#email').prev().text(`Please enter a valid email address.`);
    errors = true;
  }

  // validate passwords
  if (pass.length < 8 || pass.length > 32) {
    $('#password')
      .prev()
      .text(`Please enter a valid password.`);
    errors = true;
  }

  const inputsText = inputs.map(function () {
    return this.value;
  });

  return [errors, inputsText];
};

const handleLogin = (e) => {
  const [errorsPresent, inputs] = validate();

  if (errorsPresent) {
    e.preventDefault();
  } else {
    submitData(...inputs);
  }
};

$(document).ready(function () {
  $('#loginBtn').click(function (e) {
    handleLogin(e);
  });
  $('.form-control').keypress(function (e) {
    if (e.which == 13) {
      handleLogin(e);
    }
  });
});