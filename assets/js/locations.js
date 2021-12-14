'use strict';
// Fetch bank location data from an external API.

const token = 'pk.0ae9557ee132ce75bcb7fa0418ca38df';

function clearResults() {
  $('#results').html(''); 
  $('#resultsFor').removeClass();
}

function showingResultsFor(data) {
  const a = data.address;
  const address = `Showing banks${
    a.house_number == undefined ? '' : ' near ' + a.house_number
  } ${a.road == undefined ? '' : a.road} in ${
    a.city == undefined ? '' : a.city + ','
  } ${a.state == undefined ? '' : a.state}`;
  $('#resultsFor').addClass('alert alert-primary');
  $('#resultsFor').text(address);
}

async function getBanks(position) {
  const lat = position.coords.latitude;
  const lon = position.coords.longitude;
  try {
    const response = await fetch(
      `https://us1.locationiq.com/v1/nearby.php?key=${token}&lat=${lat}&lon=${lon}&tag=bank&radius=50000&normalizeaddress=1&limit=30&format=json`
    );
    const data = await response.json();
    return data;
  } catch(error) {
    $('#resultsFor').addClass('alert alert-danger');
    $('#resultsFor').text('Error getting banks...');
  }
}

function validateData(data) {
  const validatedData = data.filter((item) => {
    return (
      item.address.road && item.address.city && item.address.house_number && item.address.name
    );
  })
  if(validatedData.length % 2 != 0) {
    validatedData.pop();
  }
  return validatedData;
}

function displayBanks(banks, location) { 
  const validatedBanks = validateData(banks);
  if (validatedBanks.length !== 0) {
    showingResultsFor(location);
    fetch('../assets/templates/location_results_template.html')
      .then((response) => response.text())
      .then((template) => {
        const rendered = Mustache.render(template, validatedBanks);
        $('#results').html(rendered);
      });
  } else {
    $('#resultsFor').addClass('alert alert-danger');
    $('#resultsFor').text('Your search did not return any results.');
  }

}

function valid(input) {
  const re = /(^([0-9]+[ ])?[0-9a-z]+([ ][0-9a-z]+)*,*[ ]*[0-9a-z]+(,[ ][a-z]{2})*,*([ ][0-9]{5})*$)|(^[a-z]{4,}[ ][a-z]{4,},[ ][a-z]{2}$)/gi;
  return re.test(input);
}

async function searchInput(e) {
  e.preventDefault();
  const input = $('#searchInput').val().trim();
  clearResults();

  // Find coordinates based on address entered
  if (valid(input)) {
    try {
      const response = await fetch(`https://us1.locationiq.com/v1/search.php?key=${token}&q=${input}&limit=1&normalizeaddress=1&addressdetails=1&format=json`);
      const location = await response.json();
      const searchCoords = {
        coords: {
          latitude: `${location[0].lat}`,
          longitude: `${location[0].lon}`,
        },
      };
      const banks = await getBanks(searchCoords);
      $('#searchInput').val('');
      displayBanks(banks, location[0]);
    } catch(error) {
      if (error == 'empty') {
        $('#resultsFor').addClass('alert alert-danger');
        $('#resultsFor').text('Please enter a valid address, city, or zip.');
      } else {
        $('#resultsFor').addClass('alert alert-danger');
        $('#resultsFor').text('Please check your spelling and try again.');
      }
    }
  } else {
    $('#resultsFor').addClass('alert alert-danger');
    $('#resultsFor').text('Please check your spelling and try again.');
  }
}

async function searchLocation(position) {
  clearResults();
  const lat = position.coords.latitude;
  const lon = position.coords.longitude;

  // search for address based on user coordinates
  try {
    const response = await fetch(
      `https://us1.locationiq.com/v1/reverse.php?key=${token}&lat=${lat}&lon=${lon}&normalizeaddress=1&addressdetails=1&format=json`
    );
    const location = await response.json();
    const banks = await getBanks(position);
    displayBanks(banks, location);
  } catch {
    $('#resultsFor').addClass('alert alert-danger');
    $('#resultsFor').text('Getting coordinates went wrong...');
  }
}

$(document).ready(function() {
  $('#searchInputBtn').click(function(e) {
    searchInput(e);
  });
  $('#bankSearchForm').submit(function(e) {
    e.preventDefault();
    searchInput(e); 
  });
  $('#searchLocationBtn').click(function() {
    navigator.geolocation.getCurrentPosition(searchLocation);
  });
});