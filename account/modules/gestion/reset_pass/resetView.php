<div class="auth-page-wrapper pt-3">
  <div class="auth-one-bg-position auth-one-bg" style="background-image: url('<?php echo bg_enterprise ?>');" id="auth-particles">
    <div class="bg-overlay"></div>
    <div class="shape">
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
      </svg>
    </div>
  </div>
  <div class="auth-page-content p-0">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
          <div class="card mt-2">
            <div class="card-body p-4">
              <div class="text-center mb-1 text-white-50">
                <div>
                  <a class="d-inline-block auth-logo">
                    <img src="<?php echo $site->logo ?>" alt="" height="48">
                  </a>
                </div>
              </div>
              <div class="text-center mt-2 mb-2">
                <h5 class="text-primary">Crear nueva clave de acceso!</h5>
                <p class="text-muted">Recuerde seguir las siguientes recomendaciones para crear una clave segura</p>
                <?php echo Flasher::flash() ?>
                <label class="text-muted">Debe contener al menos</label><br />
                <span class="badge badge-soft-primary badge-border">Dos caracteres numéricos</span>
                <span class="badge badge-soft-primary badge-border">Seis caracteres alfabeticos</span>
                <span class="badge badge-soft-primary badge-border">Longitud mayor a ocho caracteres</span>
                <span class="badge badge-soft-primary badge-border">Una minúscula</span>
                <span class="badge badge-soft-primary badge-border">Una mayúscula</span>
              </div>
              <div class="p-2 mt-2">
                <form method="POST" action="<?php echo $site->action ?>" autocomplete="off">
                  <?php echo insert_inputs(); ?>
                  <div class="mb-3">
                    <label for="pass_old" class="form-label">Contraseña Actual</label>
                    <input type="password" class="form-control" name="pass_old" placeholder="Ingrese su contraseña" autofocus required>
                  </div>
                  <div class="mb-3">
                    <label for="pass_new" class="form-label">Contraseña Nueva</label>
                    <input type="password" class="form-control" name="pass_new" placeholder="Ingrese su nueva contraseña" required>
                  </div>
                  <div class="mb-3">
                    <label for="pass_renew" class="form-label">Reingrese Contraseña</label>
                    <input type="password" class="form-control" name="pass_renew" placeholder="Re ingrese su nueva contraseña" required>
                  </div>
                  <div class="row mt-4">
                    <div class="col-md-6 mb-1">
                      <a class="btn btn-info w-100" href="index">Volver</a>
                    </div>
                    <div class="col-md-6">
                      <button class="btn btn-info w-100" type="submit">Cambiar Clave</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="mt-4 text-center">
                <p class="mb-0">No es usted? <a href="logout" class="fw-semibold text-primary text-decoration-underline"> Cerrar Sesion</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>