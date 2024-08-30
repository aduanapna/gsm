<div class="app-menu navbar-menu">
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="<?php echo $site->home ?>" class="logo logo-dark">
      <span class="logo-sm">
        <img src="<?php echo $site->logo ?>" alt="" height="24">
      </span>
      <span class="logo-lg">
        <img src="<?php echo $site->logo ?>" alt="" height="32">
      </span>
    </a>
    <!-- Light Logo-->
    <a href="<?php echo $site->home ?>" class="logo logo-light">
      <span class="logo-sm">
        <img src="<?php echo $site->logo ?>" alt="" height="24">
      </span>
      <span class="logo-lg">
        <img src="<?php echo $site->logo ?>" alt="" height="32">
      </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
      <i class="ri-record-circle-line"></i>
    </button>
  </div>
  <div id="scrollbar">
    <div class="container-fluid">
      <div id="two-column-menu"></div>
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
        <?php echo Navbar::gestion(gestion_navs, $site->user->profile) ?>
      </ul>
    </div>
  </div>
  <div class="sidebar-background"></div>
  <div class="row position-absolute bottom-0 end-0 p-0">
    <div class="text-light ">
      Diseño <a href="<?php echo $site->about ?>" alt="iD">iD</a>
    </div>
  </div>
</div>