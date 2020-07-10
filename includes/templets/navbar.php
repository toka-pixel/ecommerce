<div class="contentnav">
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php"><?php echo lang ('HOME_ADMIN') ?> <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang ('CATEGORIES') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="items.php"><?php echo lang ('ITEMS') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php"><?php echo lang ('MEMBERS') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="items.php"><?php echo lang ('COMMENTS') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang ('STATISTICS') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang ('LOGOS') ?></a>
      </li>
     
    
     
    </ul>
    <form class="form-inline my-2 my-lg-0">
    <li class="nav-item dropdown" style="list-style:none">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         toka
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['id'] ?>">edit profile</a>
          <a class="dropdown-item" href="#">settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">logout</a>
        </div>
      </li>
    </form>
  </div>
</nav>
</div>
</div>