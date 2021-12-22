<!-- A form to create a new transfer -->

<section class="container-fluid">
  <div class="row justify-content-start">

    <div class="p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Create a New Transfer</h1>
      </div>

      <div class="form">
        <form>
          <div class="form-group">
            <label for="account-from">Account From</label>
            <small class="msg text-danger"></small>
            <select class="form-control" name="accountFrom" id="accountFrom">
              <option value="">--Please choose an account--</option>
              {accounts}
              <option value="{account_id}">{account_name}</option>
              {/accounts}
            </select>
          </div>
          <div class="form-group">
            <label for="accountTo">Account To</label>
            <small class="msg text-danger"></small>
            <select class="form-control" name="account-to" id="accountTo">
              <option value="">--Please choose an account--</option>
              {accounts}
              <option value="{account_id}">{account_name}</option>
              {/accounts}
            </select>
          </div>
          <div class="form-group">
            <label for="amount">Transfer Amount</label>
            <small class="msg text-danger"></small>
            <input type="text" pattern="(((\d{0,3},?)?\d{0,3}){0,9}.?\d{2}?)|\d" id="amount" class="form-control" name="transaction-amount">
          </div>
          <div class="form-group">
            <label for="amount">Transfer Date</label>
            <small class="msg text-danger"></small>
            <input type="date" id="transferDate" class="form-control" name="transfer-date">
          </div>
          <div class="form-group">
            <label for="name">Transfer Name</label>
            <small class="msg text-danger"></small>
            <input type="text" id="name" class="form-control" name="name" maxlength="64">
          </div>

        </form>

        <div id="msg" class="mb-3"></div>

        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
        <button type="submit" id="createTransferBtn" class="btn btn-success">Submit Transfer</button>

      </div>
    </div>
  </div>

</section>