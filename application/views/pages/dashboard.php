<!-- -------------------
Dashboard page.
------------------- -->

<section class="px-3 py-5 p-lg-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col mb-3 mb-lg-5">
        <h2 class="display-4">Hello, <?= $user_name ?>!</h2>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="card mb-5">
      <div class="card-body">
        <div class="row justify-content-between align-items-center mx-3 px-3">
          <span>TOTAL BALANCE AVAILABLE</span>
          <span id="totalBalance" class="money-l1 font-weight-bold">$2,455.67</span>
        </div>
      </div>
    </div>



    <div class="card">
      <div class="card-header">
        <span class="d-block text-center">My Accounts</span>
      </div>
      <div class="card-body">

        <div class="row justify-content-between mx-3 pb-3 pl-3 pr-3 border-bottom">
          <div>
            <div>
              <span class="font-weight-bold">ACCOUNT NAME</span>
            </div>
            <div>
              <span class="text-secondary">Account #012505151</span>
              <span class="ml-5 text-secondary">Account type: Checking</span>
            </div>
          </div>
          <div>
            <span class="money-l1 font-weight-bold">$2,455.78</span>
          </div>
        </div>
      </div>
    </div>
    <a href="newaccount" id="newAccountBtn" class="btn btn-success mt-5">+ New Account</a>
  </div>
</section>