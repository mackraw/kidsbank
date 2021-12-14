<!-- -------------------
Header template.
------------------- -->

<!DOCTYPE html>
<html>

<head>
  <title><?= $pagetitle ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./../../../assets/css/bootstrap.css" rel="stylesheet">
  <link href="./../../../assets/css/main.css" rel="stylesheet">
  <meta name="keywords" content="coding07" />
  <meta name="description" content="A demonstration of MVC with Codeigniter" />
  <meta name="Author" content="Maciej Krawczyk" />
  <link rel="shortcut icon" href="./../../../favicon.png" type="image/png">
</head>

<body>
  <header class="page-header">
    <div class="navbar navbar-expand-lg navbar-dark justify-content-lg-around bg-success">
      <a href="/" class="navbar-brand ml-md-5 ml-xs-3 text-light">Kids' Bank</a>

      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

      <nav class="navigation collapse navbar-collapse justify-content-end" id="nav">
        <ul class="navbar-nav justify-content-end mr-md-4 mr-xs-1">
          <li class="nav-item text-center"><a href="../../../../contact" class="nav-link mx-3 p-2 text-light">Contact</a></li>
          <li class="nav-item text-center"><a href="../../../../articles" class="nav-link mx-3 p-2 text-light">Articles</a></li>
          <li class="nav-item text-center"><a href="../../../../locations" class="nav-link mx-3 p-2 text-light">Locations</a></li>
          <li class="nav-item text-center"><a href="../../../../about" class="nav-link mx-3 p-2 text-light">About</a></li>
          <?php if (!$this->session->userdata('logged_in')) : ?>
            <li class="nav-item text-center"><a href="../../../../login" class="nav-link mx-3 p-2 text-light">Log In</a></li>
          <?php endif ?>
          <?php if ($this->session->userdata('logged_in')) : ?>
            <li class="nav-item text-center"><a href="../../../../dashboard" class="nav-link mx-3 p-2 text-light">My Dashboard</a></li>
            <li class="nav-item text-center"><a href="../../../../logout" class="nav-link mx-3 p-2 text-light">Log Out</a></li>
          <?php endif ?>
        </ul>
      </nav>
    </div>
  </header>