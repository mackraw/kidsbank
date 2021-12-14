<!-- -------------------
Static Login page.
------------------- -->

<section class="signup container-fluid">
  <div class="row justify-content-end">

    <div class="signup-card p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Log in</h1>
      </div>

      <div class="form">
        <form>

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

        </form>
        <div id="msg" class="mb-3"></div>
        <button class="btn btn-success" type="submit" id="loginBtn">Log In</button>
        <div class="mt-3 text-center">
          <p class="copy mb-0 mt-3">Don't have an account? <a href="register" class="text-success text-center">Register here.</a></p>
        </div>

      </div>
    </div>
  </div>

</section>