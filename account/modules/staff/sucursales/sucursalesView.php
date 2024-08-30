<?php
$s            = '';
$stores       = new storesModel();
$stores_data  = $stores->access_all();
foreach ($stores_data as $vl_store) {
  $name               = ucwords($vl_store['store_name']);
  $token              = to_json($vl_store['store_id']);
  $token              = iD_encrypt($token); 
  $link               = URL . 'employees/i/' . $token;
  $s                  .= sprintf('<tr><th class="text-start">%s</th><th class="small text-start"><a href="%s" target="_blank">%s</a></th></tr>', $name, $link, $link);
}
?>

<div class="auth-page-wrapper pt-4 bg-dark" id="app">
  <div class="auth-page-content">
    <div class="container">
      <div class="row">
        <div class="row justify-content-center">
          <div class="col-12 text-center mb-4">
            <img src="<?php echo $site->logo ?>" class="img-fluid" style="height:48px;">
          </div>
          <div class="col-8 d-none d-lg-block">
            <div class="card mb-3">
              <div class="card-body p-1">
                <div class="table-responsive">
                  <table class="table text-center">
                    <thead>
                      <tr>
                        <th scope="col">SUCURSAL</th>
                        <th scope="col">LINK</th>
                      </tr>
                    </thead>
                    <tbody class="table-dark">
                      <?php echo $s ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer bg-light">
    <div class="container-fluid">
      <div class="text-sm-end d-none d-sm-block">
        Diseño &amp; Programacion por <a href="https://imaginedesign.ar">iD</a>
      </div>
    </div>
  </footer>
</div>