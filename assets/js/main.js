'use strict';
// Common JS functions.

const clearForm = () => {
  const inputs = $('.form-control');
  $.each(inputs, function () {
    $(this).val('');
  });
  $('#msg').text('');
};

const validEmail = (email) => {
  const re =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
};
