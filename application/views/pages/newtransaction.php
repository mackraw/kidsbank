<!-- A form to create a new transaction -->

<section class="transaction container-fluid">
  <div class="row justify-content-start">

    <div class="p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Record a New Transaction</h1>
      </div>

      <div class="form">
        <form>
          <div class="form-group">
            <label>Transaction Type</label>
            <small class="msg text-danger"></small>
            <div id="transactionTypeSelection">
              <input type="radio" value="deposit" name="transaction-type" id="deposit">
              <label for="deposit">Deposit</label>
              <input type="radio" value="withdrawal" name="transaction-type" id="withdrawal">
              <label for="withdrawal">Withdrawal</label>
            </div>
          </div>
          <div class="form-group">
            <label for="amount">Transaction Amount</label>
            <small class="msg text-danger"></small>
            <input type="text" pattern="(((\d{0,3},?)?\d{0,3}){0,9}.?\d{2}?)|\d" id="amount" class="form-control w-50" name="transaction-amount">
          </div>
          <div class="form-group">
            <label for="name">Transaction Name</label>
            <small class="msg text-danger"></small>
            <input type="text" id="name" class="form-control" name="name" maxlength="64">
          </div>
          <input type="hidden" id="account_id" name="account_id" value="{account_id}">
        </form>

        <div id="msg" class="mb-3"></div>

        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
        <button type="submit" id="createTransactionBtn" class="btn btn-success">Create</button>

      </div>
    </div>
  </div>

</section>