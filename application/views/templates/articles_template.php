<!-- -------------------
HTML template to display a single entry in a list of articles.
------------------- -->

{articles}
<div class="row{styles}{style}{/styles}">
  <div class="col-md-8 col-lg-6 col-sm-10 mx-auto mb-5 pt-3">
    <h3 class="text-secondary">{title}</h3>
    <div class="main">{lead}</div>
    <p class="text-muted text-right my-4">
      <a class="badge badge-light" href="/articles/{slug}">Read more</a>
    </p>
  </div>
</div>
{/articles}