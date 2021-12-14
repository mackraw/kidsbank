<!-- -------------------
Static Contact page.
------------------- -->

<section class="contact container-fluid">
  <div class="row justify-content-start">

    <div class="p-4 my-2 mx-auto m-sm-5 col-sm-8 col-10 col-md-5 col-xl-4 bg-light rounded">

      <div>
        <h1 class="display-6 text-center">Contact Kids' Bank</h1>
      </div>

      <div class="form">
        <form>
          <div class="form-group">
            <label for="name">Name</label>
            <small class="msg text-danger"></small>
            <input type="text" id="name" class="form-control" name="name" maxlength="64">
          </div>
          <div class="form-group">
            <label for="email">Return email</label>
            <small class="msg text-danger"></small>
            <input type="text" id="email" class="form-control" name="email" maxlength="64">
          </div>
          <div class="form-group">
            <label for="email-check">Re-enter return email</label>
            <small class="msg text-danger"></small>
            <input type="text" id="emailCheck" class="form-control" name="email-check" maxlength="64">
          </div>
          <div class="form-group">
            <label for="subject">Subject</label>
            <small class="msg text-danger"></small>
            <input type="text" id="subject" class="form-control" name="subject" maxlength="64">
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <small class="msg text-danger"></small>
            <textarea name="message" id="message" class="form-control" maxlength="1000"></textarea>
          </div>
        </form>

        <div id="msg" class="mb-3"></div>

        <button type="submit" id="sendBtn" class="btn btn-success">Send</button>
        <button type="button" id="clearBtn" class="btn btn-secondary">Clear</button>

      </div>
    </div>
  </div>

</section>