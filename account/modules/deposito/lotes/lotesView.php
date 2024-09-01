<div id="layout-wrapper">
    <?php require_once(get_include('gestion', 'header')) ?>
    <?php require_once(get_include('gestion', 'topbar')) ?>
    <div class="vertical-overlay"></div>
    <div class="main-content" id="app">
        <div class="page-content" v-cloak>
            <div class="container-fluid">
                <div data-name="cabecera" v-if="!page_error" class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">{{page_title}}</h4>
                            <div v-if="page_spinner">
                                <div class="loader"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
                <div data-name="activas" v-if="page_list" class="row mb-2">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center p-2">
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label class="small strong">Criterio de busqueda</label>
                                        <div class="search-box">
                                            <input v-model="search_lotes" type="text" placeholder="Ingrese cualquier parametro de busqueda" class="form-control search">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1 text-center">
                                        <div v-for="(status, index) in radio_toolbar" :key="index" class="form-check form-check-inline">
                                            <input v-model="radio_lotes" class="form-check-input" type="radio" :name="status.name" :id="status.name" :value="status.value">
                                            <label class="form-check-label" :for="status.name">{{ status.text }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-uppercase">
                                    <table class="table table-hover table-striped align-middle table-nowrap mb-0 table-mobile-md card-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">GSM</th>
                                                <th scope="col">Deposito</th>
                                                <th scope="col">SIGEA</th>
                                                <th scope="col">m3 Totales</th>
                                                <th scope="col">Valor en Plaza</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(lote,index) in leaked_lotes">
                                                <td data-label="GSM">#{{lote.nro_gsm}}</td>
                                                <td data-label="Deposito">{{lote.deposito}}</td>
                                                <td data-label="SIGEA">{{lote.nro_sigea}}</td>
                                                <td data-label="m3 Totales">{{lote.m3_total}}</td>
                                                <td data-label="Valor en Plaza">$ {{lote.valor_plaza}}</td>
                                                <td data-label="Estado">{{lote.lote_condition_name}}</td>
                                                <td data-label="Acciones">
                                                    <div class="btn-list flex-nowrap">
                                                        <button @click="lote_view(lote)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edit" aria-controls="offcanvas_edit" class="btn btn-icon text-primary" data-bs>
                                                            <i class="ri-edit-2-line ri-xl"></i>
                                                        </button>
                                                        <button @click="lote_delete(lote)" class="btn btn-icon text-danger">
                                                            <i class="ri-delete-bin-line ri-xl"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-name="error" v-if="page_error" class="row justify-content-between align-items-center text-center">
                    <i class="ri-close-circle-line" style="font-size: 50px; color: red;"></i>
                    <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
                </div>
                <div data-name="editar" class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvas_edit" aria-labelledby="offcanvas_lotepending">
                    <div class="offcanvas-header border pt-2 pb-1">
                        <h5>Orden {{lote_form.nro_gsm}}</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-2">
                        <div class="card border border-dark mb-2">
                            <div class="card-body p-1">
                                <div class="row">
                                    <div class="col-5">
                                        <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                                        <input v-model="lote_form.fecha_ingreso" type="date" id="fecha_ingreso" class="form-control">
                                    </div>
                                    <div class="col-7">
                                        <label for="deposito" class="form-label">Deposito</label>
                                        <select v-model="lote_form.deposito" id="deposito" class="form-control">
                                            <option value="aduana">ADUANA</option>
                                            <option value="fuerzas_seguridad">FUERZAS_SEGURIDAD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label for="judicializado" class="form-label">Judicializado</label>
                                        <select v-model="lote_form.judicializado" id="judicializado" class="form-control">
                                            <option value="no">NO</option>
                                            <option value="si">SI</option>
                                        </select>
                                    </div>
                                    <div class="col-7">
                                        <label for="organismo_secuestro" class="form-label">Organismo Secuestro</label>
                                        <select v-model="lote_form.organismo_secuestro" id="organismo_secuestro" class="form-control">
                                            <option value="dga">DGA</option>
                                            <option value="gendarmeria">GENDARMERIA</option>
                                            <option value="pfa">PFA</option>
                                            <option value="policia provincial">POLICIA PROVINCIAL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="nro_gsm" class="form-label">Nro GSM</label>
                                        <input v-model="lote_form.nro_gsm" type="text" id="nro_gsm" class="form-control text-uppercase">
                                    </div>
                                    <div class="col">
                                        <label for="nro_denuncia" class="form-label">Nro Denuncia</label>
                                        <input v-model="lote_form.nro_denuncia" type="text" id="nro_denuncia" class="form-control text-uppercase">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="nro_sumario" class="form-label">Nro Sumario</label>
                                        <input v-model="lote_form.nro_sumario" type="text" id="nro_sumario" class="form-control text-uppercase">
                                    </div>
                                    <div class="col">
                                        <label for="nro_sigea" class="form-label">Nro SIGEA</label>
                                        <input v-model="lote_form.nro_sigea" type="text" id="nro_sigea" class="form-control text-uppercase">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border border-dark mb-2">
                            <div class="card-body p-1">
                                <ul class="list-group list-group-flush overflow-auto">
                                    <li v-if="!lote_form.lote_items.length" class="list-group-item p-1">
                                        <div class="row g-1">
                                            <div class="col">
                                                <p class="text-reset fs-14 mb-0">Sin articulos</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li v-for="(item, index) in lote_form.lote_items" class="list-group-item p-1">
                                        <div class="row g-1">
                                            <div class="col">
                                                <p class="text-reset fs-14 mb-0 text-uppercase">{{item.descripcion}}</p>
                                            </div>
                                            <div v-if="item.gestionado == 'si'" class="col-auto ms-auto text-success text-uppercase">{{item.disposicion_nro}}</div>
                                            <div v-if="item.gestionado != 'si'" class="col-auto ms-auto text-danger text-uppercase">{{item.disposicion_nro}}</div>
                                        </div>
                                        <div class="row g-1">
                                            <div class="col">
                                                <div class="d-flex align-items-center">
                                                    <small class="fw-bold me-1 text-uppercase">{{item.rubro}}</small>
                                                    <small class="fw-reset me-1">{{item.cantidad}}</small>
                                                    <small class="fw-reset text-uppercase">{{item.u_medida}}</small>
                                                </div>
                                            </div>
                                            <div class="col-auto ms-auto">$ {{item.valor_plaza}}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card border border-dark mb-2">
                            <div class="card-body pt-1">
                                <div class="row">
                                    <div class="col">
                                        <label for="lote_observation" class="form-label">Observacion</label>
                                        <textarea v-model="lote_form.lote_observation" class="form-control text-uppercase" id="lote_observation" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="resolucion_nro" class="form-label">Resolucion Sumario</label>
                                        <input v-model="lote_form.resolucion_nro" type="text" id="resolucion_nro" class="form-control text-uppercase">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="lote_condition" class="form-label">Estado Lote</label>
                                        <select v-model="lote_form.lote_condition" id="lote_condition" class="form-control text-uppercase">
                                            <option v-for="(condition, index) in lote_conditions" :value="condition.lote_condition_id">{{condition.lote_condition_name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas-footer border-top p-3 text-end">
                        <a data-bs-dismiss="offcanvas" class="btn btn-link link-danger fw-medium"><i class="ri-close-line me-1 align-middle"></i> Volver</a>
                        <button @click="lote_update" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                <footer class="footer"></footer>
            </div>
        </div>
    </div>
</div>