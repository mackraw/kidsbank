<!-- -------------------
HTML template for the locations page.
------------------- -->
<section class="location-hero container-fluid d-flex align-items-center justify-content-center">
  <h1 class="display-1 text-center text-light">Bank Locations</h1>
</section>
<section class="location px-3 py-5 p-lg-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col text-center mb-3 mb-lg-5">
        <h2 class="display-2">Find a bank near you</h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <p class="lead mx-lg-5 mb-5 text-center">You can search for banks near a specific address or find one near you.</p>
    </div>
    <div class="row">
      <div class="col text-center mb-3">
        <form id="bankSearchForm">
          <div>
            <input type="text" id="searchInput" class="form-control col-lg-6 m-auto">
          </div>
        </form>
        <button type="submit" id="searchInputBtn" class="btn btn-success m-3">Search</button>
        <button type="button" id="searchLocationBtn" class="btn btn-success m-3">Find near me</button>
      </div>
    </div>
    <div class="row">
      <div class="col-auto text-center m-auto">
        <div class="mb-4">
          <p id="resultsFor"></p>
        </div>
      </div>
    </div>
    <div class="results row justify-content-around m-auto" id="results">
    </div>
</section>