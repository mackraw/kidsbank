<!-- A form to create a new payment -->

<section class="transaction container-fluid">
  <div class="row justify-content-start">

    <div class="p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Record a Payment</h1>
      </div>

      <div class="form">
        <form>
          <div class="form-group">
            <label for="amount">To Account</label>
            <input type="text" value="{account_name} (Balance: {account_balance})" id="accountName" class="form-control" name="account_name" data-balance="{account_balance}" disabled>
          </div>
          <div class="form-group">
            <label for="account-from">From Account</label>
            <small class="msg text-danger"></small>
            <select class="form-control" name="accountFrom" id="accountFrom">
              <option value="">--Please choose an account--</option>
              {from_accounts}
              <option value="{account_id}">{account_name} (available balance: {account_balance})</option>
              {/from_accounts}
            </select>
          </div>
          <div class="form-group">
            <label for="amount">Payment Amount</label>
            <small class="msg text-danger"></small>
            <input type="text" pattern="(((\d{0,3},?)?\d{0,3}){0,9}.?\d{2}?)|\d" id="amount" class="form-control w-50" name="transaction-amount">
          </div>
          <div class="form-group">
            <label for="name">Payment Name</label>
            <small class="msg text-danger"></small>
            <input type="text" id="name" class="form-control" name="name" maxlength="64">
          </div>
          <input type="hidden" id="account_id" name="account_id" value="{account_id}">
        </form>

        <div id="msg" class="mb-3"></div>

        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
        <button type="submit" id="createPaymentBtn" class="btn btn-success">Create</button>

      </div>
    </div>
  </div>

</section>