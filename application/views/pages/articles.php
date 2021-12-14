<!-- -------------------
HTML template for displaying all articles.
------------------- -->

<section class="px-3 py-5 p-lg-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col text-center mb-3 mb-lg-5">
        <h2 class="display-2"><?= $title ?></h2>
      </div>
    </div>
    <?= $articles ?>

  </div>
</section>