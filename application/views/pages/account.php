<!-- -------------------
Account page.
------------------- -->

<section class="content-wrapper px-3 py-5 p-lg-5">
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
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom card-font">
          <span class="col-12 col-sm-6 text-secondary">Currently Available Balance</span>
          <span class="col-12 col-sm-6 text-secondary">{balance}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom card-font">
          <span class="col-12 col-sm-6 text-secondary">Interest Rate</span>
          <span class="col-12 col-sm-6 text-secondary">{rate}%</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom card-font">
          <span class="col-12 col-sm-6 text-secondary">Account Type</span>
          <span class="col-12 col-sm-6 text-secondary">{type}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom card-font">
          <span class="col-12 col-sm-6 text-secondary">Account Owner</span>
          <span class="col-12 col-sm-6 text-secondary">{owner}</span>
        </div>
        <div class="row justify-content-between align-items-center mx-3 py-2 px-3 border-bottom card-font">
          <span class="col-12 col-sm-6 text-secondary">Date account created</span>
          <span class="col-12 col-sm-6 text-secondary">{created}</span>
        </div>
      </div>


    </div>

    {buttons}
    <a href="{href}" id="{elem_id}" class="btn btn-success mb-5">{elem_label}</a>
    {/buttons}

    <div class="card">
      <div class="card-header p-2">
        <span class="d-block text-center">Transaction History</span>
      </div>
      <div class="card-body p-1">
        <div class="row justify-content-between align-items-center mx-1 px-0 py-1 border-bottom">
          <div class="col-auto no-gutters">
            <span class="col-auto text-uppercase text-secondary card-font">Date</span>
          </div>
          <div class="col-5 no-gutters">
            <div class="row justify-content-around">
              <span class="col-12 col-sm-auto text-uppercase text-truncate text-secondary card-font">Transaction name</span>
              <span class="col-12 col-sm-auto text-uppercase text-secondary card-font">Type</span>
            </div>
          </div>
          <div class="col-4 p-0 align-items-stretch">
            <div class="row justify-content-around align-items-stretch">
              <span class="col-12 col-sm-auto text-uppercase text-secondary text-center card-font">Amount</span>
              <span class="col-12 col-sm-auto text-uppercase text-secondary text-center card-font">Balance</span>
            </div>
          </div>
        </div>

        {transactions}
        <div class="row justify-content-between align-items-center mx-1 px-0 py-3 border-bottom">
          <div class="col-auto no-gutters">
            <div class="col-auto align-items-center">
              <div>
                <span class="font-weight-bold card-font">{month}</span>
                <span class="font-weight-bold card-font">{day},</span>
              </div>
              <span class="font-weight-bold card-font">{year}</span>
            </div>
          </div>
          <div class="col-5 no-gutters">
            <div class="row justify-content-around">
              <span class="col-12 col-sm-auto px-0 text-secondary text-truncate card-font">{name}</span>
              <span class="col-12 col-sm-auto px-0 text-secondary text-truncate card-font">{type}</span>
            </div>
          </div>
          <div class="col-4 p-0">
            <div class="row justify-content-around align-items-stretch">
              <span class="money-l2 col-12 col-sm-auto px-3 font-weight-bold text-center card-font">{amount}</span>
              <span class="money-l2 col-12 col-sm-auto px-3 text-center text-secondary card-font">{balance_after}</span>
            </div>
          </div>
        </div>
        {/transactions}
      </div>
    </div>

  </div>
</section>