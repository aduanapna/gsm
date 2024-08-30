<div id="layout-wrapper">
  <?php require_once(get_include('gestion', 'header')) ?>
  <?php require_once(get_include('gestion', 'topbar')) ?>
  <div class="vertical-overlay"></div>
  <div class="main-content" id="app">
    <div class="page-content" v-cloak>
      <div class="container-fluid mb-2">
        <div data-name="cabecera" class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">{{page_title}}</h4>
              <div class="page-title-right">
                <div class="input-group">
                  <button v-if="page_form" type="button" class="btn btn-primary btn-sm" @click="save_store()">Guardar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
        <div data-name="formulario" v-if="page_form" class="row">
          <div class="col-xl-12">
            <div class="card h-100">
              <div class="card-body p-4">
                <div class="row">
                  <h5 class="fw-bold">Datos Sucursal</h5>
                  <div class="col-auto ms-auto">
                    <div class="form-check form-switch form-switch-success">
                      <input v-model="form_store.store_open" class="form-check-input" type="checkbox" role="switch" id="store_open">
                      <label class="form-check-label" for="store_open">Local Abierto</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-auto">
                    <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                      <img :src="form_store.store_picture" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                      <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                        <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="profile_picture_upload()" ref="profile_picture">
                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                          <span class="avatar-title rounded-circle bg-light text-body">
                            <i class="ri-camera-fill"></i>
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="row">
                      <div class="col-md-3">
                        <label class="form-label" for="store_name">Nombre</label>
                        <input v-model="form_store.store_name" id="store_name" type="text" class="form-control" required>
                      </div>
                      <div class="col-md-7">
                        <label class="form-label" for="store_address">Direccion</label>
                        <input v-model="form_store.store_address" id="store_address" type="text" class="form-control text-capitalize" paceholder="Direccion completa">
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="store_cash">Desc. Efectivo</label>
                        <input v-model="form_store.store_cash" id="store_cash" type="number" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="store_description">Descripcion</label>
                        <input v-model="form_store.store_description" id="store_description" type="text" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="store_phonenumber">Telefono</label>
                        <input v-model="form_store.store_phonenumber" id="store_phonenumber" type="tel" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label class="form-label" for="store_facebook">Facebook</label>
                    <input v-model="form_store.store_facebook" id="store_facebook" type="text" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="store_instagram">Instagram</label>
                    <input v-model="form_store.store_instagram" id="store_instagram" type="text" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="store_email">Email</label>
                    <input v-model="form_store.store_email" id="store_email" type="text" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="store_web">Sitio Web</label>
                    <input v-model="form_store.store_web" id="store_web" type="text" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label class="form-label" for="store_open_am">Apertura Mañana</label>
                    <input v-model="form_store.store_open_am" id="store_open_am" type="time" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="store_close_am">Cierre Mañana</label>
                    <input v-model="form_store.store_close_am" id="store_close_am" type="time" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="store_open_pm">Apertura Tarde</label>
                    <input v-model="form_store.store_open_pm" id="store_open_pm" type="time" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="store_close_pm">Cierre Tarde</label>
                    <input v-model="form_store.store_close_pm" id="store_close_pm" type="time" class="form-control">
                  </div>
                </div>
                <div class="row mt-2">
                  <h5 class="fw-bold">Zonas</h5>
                  <div v-for="(zone,index) in form_store.zones" class="col-md-2">
                    <label class="form-label" :for="zone.zone_id">{{zone.zone_name}}</label>
                    <input v-model="zone.zone_cost" :id="zone.zone_id" type="number" class="form-control">
                  </div>
                </div>
                <div class="row mt-3">
                  <h5 class="fw-bold">Impresora Predeterminada</h5>
                  <div class="col-md-2">
                    <label class="form-label" for="printer_name">Nombre Impresora</label>
                    <input type="text" v-model="form_store.printer_name" id="printer_name" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label" for="printer_initial_height">Altura Inicial</label>
                    <input type="number" min="1" step="1" v-model="form_store.printer_initial_height" id="printer_initial_height" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label" for="printer_left_margin">Margen Izq</label>
                    <input type="number" min="1" step="1" v-model="form_store.printer_left_margin" id="printer_left_margin" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label" for="printer_leading">Altura Linea</label>
                    <input type="number" min="1" step="1" v-model="form_store.printer_leading" id="printer_leading" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label" for="printer_width">Ancho Ticket</label>
                    <input type="number" min="1" step="1" v-model="form_store.printer_width" id="printer_width" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label" for="printer_font">Fuente</label>
                    <input type="text" v-model="form_store.printer_font" id="printer_font" class="form-control">
                  </div>
                  <p class="fw-12 mt-4">Token: {{form_store.printer_token}}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-name="error" v-if="page_error" class="row justify-content-center">
          <div class="col-xl-5">
            <div class="card overflow-hidden">
              <div class="card-body p-4">
                <div class="text-center">
                  <img src="<?php echo logo_stop ?>" alt="" height="210">
                  <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
                  <p class="text-muted mb-4 fs-14">No posee acceso al staff solicitado.<br /> Favor de no incurrir en consultas sin permisos</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-name="spinner" v-if="page_spinner" class="row justify-content-center">
          <div class="loader"></div>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer"></footer>
</div>