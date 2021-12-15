<!-- -------------------
Account page.
------------------- -->

<section class="px-3 py-5 p-lg-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col mb-3 mb-lg-5">
        <h2 class="display-4">Account Name</h2>
      </div>
    </div>
    <div class="row justify-content-between align-items-center mx-3 px-3">
      <span>AVAILABLE BALANCE</span>
      <span id="totalBalance" class="money-l1 font-weight-bold">$2,455.67</span>
    </div>
  </div>

  <button type="button" id="newTransactionBtn" class="btn btn-success mb-5">+ New Transaction</button>
  <button type="button" id="newTransferBtn" class="btn btn-success mb-5">+ New Transfer</button>

  <div class="card">
    <div class="card-header">
      <span class="d-block text-center">My Accounts</span>
    </div>
    <div class="card-body">
      <div class="row justify-content-between align-items-center mx-3 pb-1 pl-3 pr-3 border-bottom">
        <span class="col-auto col-md-2 text-secondary align-center">Date</span>
        <span class="col-auto col-md-4 text-secondary">Transaction name</span>
        <span class="col-auto col-md-2 text-secondary text-center">Type</span>
        <span class="col-auto col-md-2 text-secondary text-center">Amount</span>
        <span class="col-auto col-md-2 text-secondary text-center">Balance</span>
      </div>

      <div class="row justify-content-between align-items-center mx-3 p-3 border-bottom">
        <div class="col-auto col-md-2">
          <div class="flex-column">
            <span class="font-weight-bold">APR</span>
            <span class="font-weight-bold">28</span>
          </div>
        </div>
        <span class="col-auto col-md-4 text-secondary">Transaction name</span>
        <span class="col-auto col-md-2 text-secondary text-center">Credit</span>
        <span class="money-l2 col-auto col-md-2 font-weight-bold text-center">$2,455.78</span>
        <span class="money-l2 col-auto col-md-2 font-weight-bold text-center">$2,455.78</span>
      </div>
    </div>
  </div>

  </div>
</section>