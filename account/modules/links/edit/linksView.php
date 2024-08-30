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
                  <button @click="new_link()" v-if="page_list" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_link" type="button" class="btn btn-outline-primary btn-sm">Nuevo</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
        <div data-name="listado" v-if="page_list" class="row">
          <div class="col-12">
            <div class="card-group" style="min-height:500px">
              <div class="card mb-4">
                <div class="card-header">
                  <div class="row g-3">
                    <div class="input-group">
                      <select v-model="selected_link" class="form-select" aria-label="Pagina a editar">
                        <option value="index" selected>Index</option>
                        <option value="menu">Carta</option>
                        <option value="flyers">Promos</option>
                        <option value="stores">Locales</option>
                      </select>
                      <input type="text" v-model="search_link" id="search_link" class="form-control search" placeholder="Buscar por razon social, contacto, telefono, email...">
                      <button type="button" class="btn btn-outline-primary btn-icon waves-effect waves-light" @click="search_link = ''">
                        <i class="bx bx-brush-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body p-2">
                  <ul class="list-group list-group-flush overflow-auto">
                    <li class="list-group-item" v-if="!leaked_link.length">
                      <h6>No hay registros</h6>
                    </li>
                    <li class="list-group-item pt-1 pb-1" v-for="(link, index) in leaked_link" v-show="(page_current - 1) * page_items <= index  && page_current * page_items > index" v-if="leaked_link.length">
                      <div class="row g-1" v-if="link.spinner === true">
                        <div class="d-flex justify-content-center">
                          <div class="spinner-border text-dark" role="status">
                            <span class="sr-only">Loading...</span>
                          </div>
                        </div>
                      </div>
                      <div class="row g-1" v-if="link.spinner === false">
                        <div class="col-xl-2 d-flex align-items-center">
                          <p class="fw-bold mb-2">{{link.link_type}}</p>
                        </div>
                        <div class="col-xl-5 d-flex align-items-center">
                          <p class="">{{link.link_name}}</p>
                        </div>
                        <div class="col-xl-5">
                          <div class="d-flex align-items-center justify-content-end">
                            <span class="badge rounded-pill badge-outline-dark">{{link.link_orderby}}</span>
                            <button @click="copy_link(link)" class="btn btn-icon text-dark" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_link">
                              <i class="ri-recycle-line ri-xl"></i>
                            </button>
                            <button @click="view_link(link)" class="btn btn-icon text-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_link">
                              <i class="ri-edit-2-line ri-xl"></i></button>
                            <button @click="delete_link(link)" class="btn btn-icon text-danger">
                              <i class="ri-delete-bin-line ri-xl"></i>
                            </button>
                            <div class="form-check form-switch form-switch-success" :id="link.link_id">
                              <input @click="status_link(link)" v-model="link.link_condition" class="form-check-input" type="checkbox" role="switch" :name="link.link_id" checked>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="card-footer">
                  <div class="col-sm">
                    <div class="text-muted">Total Paginas: {{page_current}}/<span class="fw-semibold">{{Math.ceil(leaked_link.length / page_items)}}</span></div>
                    <nav aria-label="...">
                      <ul class="pagination justify-content-center">
                        <li class="page-item">
                          <span class="page-link cursor-pointer" @click.prevent="page_current = 1"><i class="ri-home-line ri-md align-middle"></i></span>
                        </li>
                        <li class="page-item">
                          <span class="page-link cursor-pointer" v-show="page_current != 1" @click.prevent="page_current -= 1"><i class="ri-arrow-left-s-line ri-md align-middle"></i></span>
                        </li>
                        <li class="page-item">
                          <a class="page-link cursor-pointer" v-show="page_current * page_items / leaked_link.length < 1" @click.prevent="page_current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
                        </li>
                      </ul>
                    </nav>
                  </div>
                </div>
              </div>
              <div class="card mb-4 d-none d-lg-block">
                <div class="card-body" style="background-color:#DE2626">
                  <div class="col-lg-12">
                    <div class="text-center mt-sm-5 pt-4 mb-4">
                      <a v-for="(item, index) in leaked_type.logo" :key="item.link_id" href="#" class="mb-2">
                        <img :src="item.link_picture" class="img-fluid" style="height:48px;">
                      </a>
                      <div class="row justify-content-center mt-2" v-if="leaked_type.image">
                        <template v-for="(item, index) in leaked_type.image">
                          <div :key="item.link_id" class="col-md-6 col-lg-4 mt-2">
                            <img :class="item.link_class" :alt="item.link_name" :src="item.link_picture">
                          </div>
                        </template>
                      </div>
                      <div class="row justify-content-center mt-2" v-if="leaked_type.button">
                        <div class="col-8 bd-highlight">
                          <a v-for="(item, index) in leaked_type.button" :key="item.link_id" href="#" :class="item.link_class" :style="item.link_style">
                            <i :class="item.link_icon" v-if="item.link_icon"></i>
                            {{item.link_name}}
                          </a>
                        </div>
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
                  <p class="text-muted mb-4 fs-14">No posee acceso al link solicitado.<br /> Favor de no incurrir en consultas sin permisos</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-name="spinner" v-if="page_spinner" class="row justify-content-center">
          <div class="loader"></div>
        </div>
        <div data-name="formulario_link" tabindex="-1" id="offcanvas_link" aria-labelledby="offcanvas_orderpending" class="offcanvas offcanvas-end border-0" aria-modal="true" role="dialog">
          <div class="offcanvas-header border pt-2 pb-1">
            <h5>Elemento Web</h5>
            <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" class="btn-close text-reset"></button>
          </div>
          <div class="offcanvas-body p-1">
            <div class="card border border-dark mb-2">
              <div class="card-body pt-1 pb-1 m-0">
                <div class="row">
                  <div class="col-12">
                    <label class="form-label" for="link_type">Elemento</label>
                    <select v-model="form_link.link_type" id="link_type" class="form-control">
                      <option value="image">Flyers</option>
                      <option value="button">Boton</option>
                      <option value="logo">Logo</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <label class="form-label" for="link_name">Texto</label>
                    <input v-model="form_link.link_name" id="link_name" type="text" class="form-control">
                  </div>
                  <div class="col-4">
                    <label class="form-label" for="link_icon">Orden</label>
                    <input v-model="form_link.link_orderby" id="link_orderby" type="text" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <label class="form-label" for="link_class">Formato</label>
                    <textarea v-model="form_link.link_class" id="link_class" class="form-control"></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <label class="form-label" for="link_style">Estilo</label>
                    <textarea v-model="form_link.link_style" id="link_style" class="form-control"></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <label class="form-label" for="link_icon">Icono</label>
                    <textarea v-model="form_link.link_icon" id="link_icon" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card border border-dark mb-2" v-if="form_link.link_type == 'button'">
              <div class="card-body pt-1 pb-1 m-0">
                <div class="row">
                  <div class="col-12">
                    <label class="form-label" for="link_href">Enlace</label>
                    <textarea v-model="form_link.link_href" id="link_href" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card border border-dark mb-2" v-if="form_link.link_type != 'button'">
              <div class="card-body pt-1 pb-1 m-0 text-center">
                <div class="profile-user position-relative d-inline-block mx-auto">
                  <div class="d-flex justify-content-center align-items-center" style="width: 200px; height: 100px; overflow: hidden;">
                    <img :src="form_link.link_picture" class="img-fluid" alt="imagen">
                  </div>
                  <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                    <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="picture_upload()" ref="profile_picture">
                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                      <span class="avatar-title rounded-circle bg-light text-body">
                        <i class="ri-camera-fill"></i>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="offcanvas-footer border-top p-3 text-center">
            <div class="row">
              <div class="col-6">
                <button type="button" data-bs-dismiss="offcanvas" class="btn btn-outline-danger btn-sm btn-label waves-effect waves-light w-100">
                  <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Volver
                </button>
              </div>
              <div class="col-6">
                <button @click="save_link" type="button" data-bs-dismiss="offcanvas" class="btn btn-outline-success btn-sm btn-label waves-effect waves-light w-100">
                  <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Confirmar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer"></footer>
  </div>
</div>