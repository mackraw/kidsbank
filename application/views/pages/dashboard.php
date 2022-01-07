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
          <span id="totalBalance" class="money-l1 font-weight-bold">{total}</span>
        </div>
      </div>
    </div>

    <a href="newaccount" id="newAccountBtn" class="btn btn-success mb-5">+ New Account</a>
    <a href="/newtransfer" id="newTransferBtn" class="btn btn-success mb-5">+ New Transfer</a>

    <div class="card">
      <div class="card-header">
        <span class="d-block text-center">My Accounts</span>
      </div>
      <div class="card-body">

        {accounts}
        <div class="row justify-content-between mx-0 mb-3 pb-3 px-2 border-bottom">
          <div class="col-9">
            <div class="row">

              <h5 class="col-12 p-0">
                <a href="/account/{account_id}" class="font-weight-bold card-font-primary">{name}</a>
              </h5>
              <div class="col-12">
                <div class="row justify-content-start">
                  <span class="px-2 text-secondary card-font">Active since: {created_date}</span>
                  <span class="px-2 text-secondary card-font">Account type: {type}</span>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-12">
                  <a href="/closeaccount/{account_id}" id="closeAccountBtn" class="font-light text-secondary bg-light pb-1 px-2 rounded"><small>Close Account</small></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-3 p-0">
            <span class="money-l1 font-weight-bold text-truncate card-font-primary">{balance}</span>
          </div>
        </div>
        {/accounts}
      </div>
    </div>

  </div>
</section>