<!-- -------------------
Account page.
------------------- -->

<section class="px-3 py-5 p-lg-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col mb-3 mb-lg-5">
        <h2 class="display-4">{name}</h2>
      </div>
    </div>

    <div class="card mb-5">
      <div class="card-header">
        <span class="d-block text-center">Account Details</span>
      </div>
      <div class="card-body">
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom">
          <span class="col-auto col-md-6 text-secondary">Currently Available Balance</span>
          <span class="col-auto col-md-6 text-secondary">{balance}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom">
          <span class="col-auto col-md-6 text-secondary">Account Owner</span>
          <span class="col-auto col-md-6 text-secondary">{owner}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom">
          <span class="col-auto col-md-6 text-secondary">Date account created</span>
          <span class="col-auto col-md-6 text-secondary">{created}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom">
          <span class="col-auto col-md-6 text-secondary">Date interest last assessed</span>
          <span class="col-auto col-md-6 text-secondary">{last_interest}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom">
          <span class="col-auto col-md-6 text-secondary">Interest Rate</span>
          <span class="col-auto col-md-6 text-secondary">{rate}%</span>
        </div>
      </div>



    </div>

    <a href="/account/{account_id}/newtransaction" id="newTransactionBtn" class="btn btn-success mb-5">+ New Transaction</a>
    <a href="/newtransfer" id="newTransferBtn" class="btn btn-success mb-5">+ New Transfer</a>

    <div class="card">
      <div class="card-header">
        <span class="d-block text-center">Transaction History</span>
      </div>
      <div class="card-body">
        <div class="row justify-content-between align-items-center mx-3 pb-1 pl-3 pr-3 border-bottom">
          <span class="col-auto col-md-2 text-uppercase text-secondary">Date</span>
          <span class="col-auto col-md-4 text-uppercase text-secondary">Transaction name</span>
          <span class="col-auto col-md-2 text-uppercase text-secondary text-center">Type</span>
          <span class="col-auto col-md-2 text-uppercase text-secondary text-center">Amount</span>
          <span class="col-auto col-md-2 text-uppercase text-secondary text-center">Balance</span>
        </div>

        {transactions}
        <div class="row justify-content-between align-items-center mx-3 p-3 border-bottom">
          <div class="col-auto col-md-2">
            <div class="flex-column">
              <span class="font-weight-bold">{month}</span>
              <span class="font-weight-bold">{day},</span>
              <span class="font-weight-bold">{year}</span>
            </div>
          </div>
          <span class="col-auto col-md-4 text-secondary">{name}</span>
          <span class="col-auto col-md-2 text-secondary text-center">{type}</span>
          <span class="money-l2 col-auto col-md-2 font-weight-bold text-center">{amount}</span>
          <span class="money-l2 col-auto col-md-2 font-weight-bold text-center">{balance_after}</span>
        </div>
        {/transactions}
      </div>
    </div>

  </div>
</section>