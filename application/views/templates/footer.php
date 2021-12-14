<!-- -------------------
Footer template.
------------------- -->

<footer>
  <div class="bg-dark pt-5">
    <div class="container row mx-auto">


      <div class="col-md-4">
        <img src="./../../../assets/images/bank.png" class="d-block img-fluid my-4 mx-auto" alt="Logo of Kids' Bank">
      </div>
      <div class="col-md-4 my-4">
        <ul class="nav flex-column justify-content-center">
          <li class="h5 text-success">Useful Links</li>
          <li class="nav-item"><a href="https://www.kidsfinancialeducation.com/" class="nav-link text-light" target="_blank">Sagevest Kids</a></li>
          <li class="nav-item"><a href="https://www.moneytimekids.com/for-parents/" class="nav-link text-light" target="_blank">Money Time</a></li>
          <li class="nav-item"><a href="https://www.financialeducatorscouncil.org/financial-education-for-children/" class="nav-link text-light" target="_blank">National Financial Educators Council</a></li>
        </ul>
      </div>
      <div class="col-md-4 my-4">
        <ul class="nav flex-column justify-content-center">
          <li class="h5 text-success">Find Us on Social Media</li>
          <li class="nav-item"><a href="https://facebook.com" class="nav-link text-light" target="_blank">Facebook</a></li>
          <li class="nav-item"><a href="https://twitter.com" class="nav-link text-light" target="_blank">Twitter</a></li>
          <li class="nav-item"><a href="https://instagram.com" class="nav-link text-light" target="_blank">Instagram</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="bg-dark">
    <p class="copy text-center p-3 mb-0 text-secondary">
      Copyright &copy; <?= $localtime ?> <?= $pagename ?>. Not responsible for the content of external sites.
    </p>
  </div>
</footer>
{scripts}
<script src="{script}"></script>
{/scripts}
</body>

</html>