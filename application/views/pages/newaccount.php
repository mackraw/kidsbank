<!-- New account form -->

<section class="contact container-fluid">
  <div class="row justify-content-start">

    <div class="p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Create a New Bank Account</h1>
      </div>

      <div class="form">
        <form>
          <div class="form-group">
            <label for="name">Account Name</label>
            <small class="msg text-danger"></small>
            <input type="text" id="name" class="form-control" name="name" maxlength="64">
          </div>
          <div class="form-group">
            <label>Account Type</label>
            <small class="msg text-danger"></small>
            <div id="accountTypeSelection">
              <input type="radio" value="checking" name="account-type" id="checking">
              <label for="checking">Checking</label>
              <input type="radio" value="credit" name="account-type" id="credit">
              <label for="credit">Credit Card</label>
            </div>
            </select>
          </div>
        </form>

        <div id="msg" class="mb-3"></div>

        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
        <button type="submit" id="createAccountBtn" class="btn btn-success">Create</button>

      </div>
    </div>
  </div>

</section>