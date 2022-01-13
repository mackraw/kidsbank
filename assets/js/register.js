'use strict';
// Client-side validation to the registration form.

const submitData = (...args) => {
  $.ajax({
    url: '/users/register',
    type: 'POST',
    data: {
      name: args[0],
      email: args[1],
      password: args[2],
      passconf: args[3],
    },
    success: function (response) {
      if (response === 'okay') {
        clearForm();
        $('#msg').removeClass('text-danger');
        $('#msg').addClass('text-success');
        $('#msg').text(`Thanks for registering. A welcome email was sent.`);
      } else if (response === 'userTaken') {
        $('#msg').removeClass('text-success');
        $('#msg').addClass('text-danger');
        $('#msg').html('This email has already been registered!');
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
  const pass = $('#password').val();
  const passconf = $('#passconf').val();
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

  const reInvalidName = /[^a-zA-ZÀ-ſ ]/g;
  if (reInvalidName.test(name)) {
    $('#name').prev().text(`Only letters and spaces are allowed.`);
    errors = true;
  }

  // validate email
  if (!validEmail(email)) {
    $('#email').prev().text(`Please enter a valid email address.`);
    errors = true;
  }

  // validate passwords
  if (pass.length < 8 || pass.length > 32) {
    $('#password')
      .prev()
      .text(`Password length must be between 8 and 32 characters.`);
    errors = true;
  }

  if (passconf.length < 8 || passconf.length > 32) {
    $('#passconf')
      .prev()
      .text(`Password length must be between 8 and 32 characters.`);
    errors = true;
  }

  if (pass !== passconf) {
    $('#passconf').prev().text(`Passwords must match.`);
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
  $('#sendBtn').click(function (e) {
    handleSend(e);
  });
});