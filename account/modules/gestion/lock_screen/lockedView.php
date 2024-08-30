<div class="auth-page-wrapper pt-5">
  <div class="auth-one-bg-position auth-one-bg" style="background-image: url('<?php echo bg_enterprise ?>');" id="auth-particles">
    <div class="bg-overlay"></div>

    <div class="shape">
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
      </svg>
    </div>
  </div>
  <div class="auth-page-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="text-center mt-sm-5 mb-4 text-white-50">
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-md-5">
          <div class="card mt-4">
            <div class="card-body p-4">
              <div class="text-center mb-1 text-white-50">
                <div>
                  <a class="d-inline-block auth-logo">
                    <img src="<?php echo $site->logo ?>" alt="" height="48">
                  </a>
                </div>
              </div>
              <div class="text-center mt-2">
                <h5 class="text-primary">Sistema Gestion Bloqueado</h5>
                <p class="text-muted">Ingrese contraseña para ingresar a su perfil</p>
              </div>
              <div class="row">
                <?php echo Flasher::flash() ?>
              </div>
              <div class="user-thumb text-center">
                <img src="<?php echo $site->user->person_picture ?>" class="rounded-circle img-thumbnail avatar-lg" alt="thumbnail">
                <h5 class="font-size-15 mt-3"><?php echo $site->user->person_name ?></h5>
              </div>
              <div class="p-2 mt-4">
                <form method="POST" action="<?php echo $site->action ?>" autocomplete="off">
                  <?php echo insert_inputs(); ?>
                  <div class="mb-3">
                    <label class="form-label" for="userpassword">Contraseña</label>
                    <input type="password" class="form-control" name="userpassword" placeholder="Ingrese contraseña" required autofocus>
                  </div>
                  <div class="mb-2 mt-4">
                    <button class="btn btn-info w-100" type="submit">Desbloquear</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="mt-4 text-center">
            <p class="mb-0">No es usted? <a href="logout" class="fw-semibold text-primary text-decoration-underline"> Cerrar Sesion</a></p>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>