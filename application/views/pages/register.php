<!-- -------------------
Static SignUp page.
------------------- -->

<section class="signup container-fluid">
  <div class="row justify-content-end">

    <div class="signup-card p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Sign Up</h1>
      </div>

      <div class="form">
        <form>
          <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <small class="msg text-danger"></small>
            <input type="text" id="name" class="form-control" name="name" maxlength="64" required />
          </div>

          <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <small class="msg text-danger"></small>
            <input type="text" id="email" class="form-control" name="email" maxlength="64" required />
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <small class="msg text-danger"></small>
            <input type="password" id="password" class="form-control" name="password" minlength="8" maxlength="32" required />
          </div>

          <div class="form-group">
            <label for="passconf" class="form-label">Confirm password</label>
            <small class="msg text-danger"></small>
            <input type="password" id="passconf" class="form-control" name="passconf" minlength="8" maxlength="32" required />
          </div>

        </form>
        <div id="msg" class="mb-3"></div>
        <button class="btn btn-success" type="submit" id="sendBtn">Sign Up</button>
        <div class="mt-3 text-center">
          <p class="copy mb-0 mt-3">Already have an account? <a href="login" class="text-success text-center">Log in.</a></p>
        </div>

      </div>
    </div>
  </div>

</section>