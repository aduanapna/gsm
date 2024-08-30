<div id="layout-wrapper">
  <?php require_once(get_include('gestion', 'header')) ?>
  <?php require_once(get_include('gestion', 'topbar')) ?>
  <div class="vertical-overlay"></div>
  <!-- NO TOCAR -->
  <div class="main-content" id="app">
    <div class="page-content" v-cloak>
      <div class="container-fluid mb-2">
        <div data-name="cabecera" class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">{{page_title}}</h4>
              <div class="page-title-right">
                <div class="input-group">
                  <button type="button" class="btn btn-outline-primary btn-sm" @click="new_staff()" v-if="page_list">Nuevo</button>
                  <button type="button" class="btn btn-outline-success btn-sm me-1" @click="save_staff()" v-if="page_form">Guardar</button>
                  <button type="button" class="btn btn-outline-danger btn-sm me-1" @click.esc="page_list = true" v-if="page_form">Cancelar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
        <div data-name="listado" v-if="page_list" class="row">
          <div class="card">
            <div class="card-body border-bottom-dashed border-bottom">
              <div class="row g-3">
                <div class="input-group">
                  <input type="text" v-model="search_staff" id="search_staff" class="form-control search" placeholder="Buscar por razon social, contacto, telefono, email...">
                  <button type="button" class="btn btn-outline-primary btn-icon waves-effect waves-light" @click="search_staff = ''">
                    <i class="bx bx-brush-alt"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body p-2">
              <ul class="list-group list-group-flush overflow-auto" style="min-height:25em;max-height:30em;">
                <li class="list-group-item" v-if="!leaked_staff.length">
                  <h6>No hay registros</h6>
                </li>
                <li class="list-group-item p-1" v-for="(staff, index) in leaked_staff" v-show="(page_current - 1) * page_items <= index  && page_current * page_items > index" v-if="leaked_staff.length">
                  <div class="row g-1" v-if="staff.spinner == true">
                    <div class="d-flex justify-content-center">
                      <div class="spinner-border text-dark" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </div>
                  </div>
                  <div class="row g-1" v-if="staff.spinner == false">
                    <div class="col-md-3">
                      <div class="d-flex align-items-center">
                        <img :src="staff.person_picture" alt="" class="rounded-circle avatar-xs me-2">
                        <div>
                          <p class="text-reset fs-14 mb-0 text-capitalize">{{staff.person_name}} {{staff.person_lastname}}</p>
                          <p class="text-muted mb-0">Dni: <span class="fw-medium">{{staff.person_document}}</span></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-7 text-end">
                      <div class="form-check form-switch form-check-inline" dir="ltr">
                        <input v-model="staff.profile_administrator" true-value="1" false-value="0" type="checkbox" class="form-check-input" :id="`profile_administrator-${staff.person_id}`">
                        <label class="form-check-label" :for="`profile_administrator-${staff.person_id}`">Administrador</label>
                      </div>
                      <div class="form-check form-switch form-check-inline" dir="ltr">
                        <input v-model="staff.profile_responsible" true-value="1" false-value="0" type="checkbox" class="form-check-input" :id="`profile_responsible-${staff.person_id}`">
                        <label class="form-check-label" :for="`profile_responsible-${staff.person_id}`">Responsable</label>
                      </div>
                    </div>
                    <div class="col-md-2 ms-auto text-end">
                      <button @click="profile_staff(staff)" class="btn btn-icon text-dark">
                        <i class="ri-save-line ri-xl"></i>
                      </button>
                      <button @click="view_staff(staff)" class="btn btn-icon text-primary">
                        <i class="ri-edit-2-line ri-xl"></i>
                      </button>
                      <button @click="delete_staff(staff)" class="btn btn-icon text-danger">
                        <i class="ri-delete-bin-line ri-xl"></i>
                      </button>
                      <button @click="status_staff(staff)" :class="staff.person_condition_color" class="btn btn-icon">
                        <i class="ri-user-3-line ri-xl"></i>
                      </button>
                      <button @click="reset_pass(staff)" class="btn btn-icon text-warning">
                        <i class="ri-key-2-line ri-xl"></i>
                      </button>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            <div class="card-footer">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page_current}}/<span class="fw-semibold">{{Math.ceil(leaked_staff.length / page_items)}}</span></div>
                <nav aria-label="...">
                  <ul class="pagination justify-content-center">
                    <li class="page-item">
                      <span class="page-link cursor-pointer" @click.prevent="page_current = 1"><i class="ri-home-line ri-md align-middle"></i></span>
                    </li>
                    <li class="page-item">
                      <span class="page-link cursor-pointer" v-show="page_current != 1" @click.prevent="page_current -= 1"><i class="ri-arrow-left-s-line ri-md align-middle"></i></span>
                    </li>
                    <li class="page-item">
                      <a class="page-link cursor-pointer" v-show="page_current * page_items / leaked_staff.length < 1" @click.prevent="page_current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <div data-name="formulario" v-if="page_form" class="row">
          <div class="col-xl-3">
            <div class="card h-100">
              <div class="card-body p-4">
                <div class="text-center">
                  <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                    <img :src="form_staff.person_picture" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                      <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="picture_upload()" ref="profile_picture">
                      <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                        <span class="avatar-title rounded-circle bg-light text-body">
                          <i class="ri-camera-fill"></i>
                        </span>
                      </label>
                    </div>
                  </div>
                  <h5 class="fs-16 mb-1 text-capitalize">{{form_staff.person_name}} {{form_staff.person_lastname}}</h5>
                  <p class="text-muted mb-0">{{form_staff.edad}} años</p>
                  <p class="text-muted mb-0 text-start"><strong>Dni:</strong> {{form_staff.person_document}} </p>
                  <p class="text-muted mb-0 text-start"><strong>Correo Electronico:</strong> {{form_staff.person_email}} </p>
                  <p class="text-muted mb-0 text-start"><strong>Observacion:</strong> {{form_staff.professional_observation}} </p>
                  <hr />
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-9">
            <div class="card h-100">
              <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#data" role="tab" aria-selected="true">
                      Datos Personales
                    </a>
                  </li>
                </ul>
              </div>
              <div class="card-body p-4">
                <div class="tab-content">
                  <div title="data_person" class="tab-pane active" id="data" role="tabpanel">
                    <div class="row mb-1">
                      <div class="col-md-4">
                        <label class="form-label" for="person_document"><mark>Dni</mark></label>
                        <input v-model="form_staff.person_document" id="person_document" type="text" maxlength="8" class="form-control" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="person_cellphone"><mark>Celular</mark></label>
                        <input v-model="form_staff.person_cellphone" id="person_cellphone" type="text" class="form-control text-capitalize" paceholder="Incluya codigo de area">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="person_email">Correo Electronico</label>
                        <input v-model="form_staff.person_email" id="person_email" type="email" class="form-control">
                      </div>
                    </div>
                    <div class="row mb-1">
                      <div class="col-md-4">
                        <label class="form-label" for="person_name"><mark>Nombres</mark></label>
                        <input v-model="form_staff.person_name" id="person_name" type="text" class="form-control text-capitalize" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="person_lastname">Apellidos</label>
                        <input v-model="form_staff.person_lastname" id="person_lastname" type="text" class="form-control text-capitalize" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="person_birthday">Fecha Nacimiento</label>
                        <input v-model="form_staff.person_birthday" @input="edad()" id="person_birthday" type="date" class="form-control" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="person_gender">Sexo</label>
                        <select v-model="form_staff.person_gender" id="person_gender" class="form-control">
                          <option value="1">Masculino</option>
                          <option value="2">Femenino</option>
                          <option value="3">No especifica</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mb-1">
                      <div class="col-md-6">
                        <label class="form-label" for="person_address">Direccion Completa</label>
                        <input v-model="form_staff.person_address" id="person_address" type="text" class="form-control text-capitalize" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="person_city">Ciudad</label>
                        <input v-model="form_staff.person_city" id="person_city" type="text" class="form-control text-capitalize" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="person_postalcode">Codigo Postal</label>
                        <input v-model="form_staff.person_postalcode" id="person_postalcode" type="text" class="form-control text-capitalize" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="person_employee">Area</label>
                        <select v-model="form_staff.person_employee" class="form-control" id="person_employee">
                          <option value="1">No Aplica</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label class="form-label" for="person_observation">Observaciones</label>
                        <textarea v-model="form_staff.person_observation" id="person_observation" class="form-control" style="height:100px"></textarea>
                      </div>
                    </div>
                  </div>
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
  <!-- NO TOCAR -->
  <footer class="footer"></footer>
</div>