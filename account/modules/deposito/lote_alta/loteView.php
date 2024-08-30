<div id="layout-wrapper">
    <?php require_once(get_include('gestion', 'header')) ?>
    <?php require_once(get_include('gestion', 'topbar')) ?>
    <div class="vertical-overlay"></div>
    <div class="main-content" id="app">
        <div class="page-content" v-cloak>
            <div class="container-fluid">
                <div data-name="cabecera" class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">{{page_title}}</h4>
                            <div class="page-title-right">
                                <button v-if="page_list" @click="lote_new" type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light me-1">Restablecer</button>
                                <button v-if="page_list" :disable="!btn_save" @click="lote_save" type="button" class="btn btn-outline-success btn-sm waves-effect waves-light me-1">
                                    <i v-if="!btn_save" class="ri-history-line label-icon align-middle"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
                <div data-name="listado" v-if="page_list" class="row mb-2">
                    <div data-name="lote" class="row">
                        <div class="card">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                                            <input v-model="lote_form.fecha_ingreso" type="date" id="fecha_ingreso" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="deposito" class="form-label">Deposito</label>
                                            <select v-model="lote_form.deposito" id="deposito" class="form-control">
                                                <option value="ADUANA">ADUANA</option>
                                                <option value="FUERZAS_SEGURIDAD">FUERZAS_SEGURIDAD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="judicializado" class="form-label">Judicializado</label>
                                            <select v-model="lote_form.judicializado" id="judicializado" class="form-control">
                                                <option value="NO">NO</option>
                                                <option value="SI">SI</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="organismo_secuestro" class="form-label">Organismo Secuestro</label>
                                            <select v-model="lote_form.organismo_secuestro" id="organismo_secuestro" class="form-control">
                                                <option value="DGA">DGA</option>
                                                <option value="GENDARMERIA">GENDARMERIA</option>
                                                <option value="PFA">PFA</option>
                                                <option value="POLICIA PROVINCIAL">POLICIA PROVINCIAL</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="nro_gsm" class="form-label">Nro GSM</label>
                                            <input v-model="lote_form.nro_gsm" type="text" id="nro_gsm" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="nro_denuncia" class="form-label">Nro Denuncia</label>
                                            <input v-model="lote_form.nro_denuncia" type="text" id="nro_denuncia" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="nro_sumario" class="form-label">Nro Sumario</label>
                                            <input v-model="lote_form.nro_sumario" type="text" id="nro_sumario" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-1">
                                            <label for="nro_sigea" class="form-label">Nro SIGEA</label>
                                            <input v-model="lote_form.nro_sigea" type="text" id="nro_sigea" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-1">
                                            <label for="lote_observation" class="form-label">Observaciones</label>
                                            <textarea v-model="lote_form.lote_observation" id="lote_observation" class="form-control" placeholder="Observacion de la orden" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-name="items" class="row">
                        <div class="card">
                            <div class="card-body">
                                <p class="fw-bold text-primary mb-1">Mercaderia en LOTE</p>
                                <div class="input-group">
                                    <input @keyup.enter="add_item()" ref="search_article" v-model="search_article" id="search_article" type="text" placeholder="Buscar categoria, nombre, descripcion, codigo" class="form-control search me-1">
                                    <button @click="add_item()" type="button" class="btn btn-outline-primary btn-icon waves-effect waves-light me-1">
                                        <i class="ri-search-2-line"></i>
                                    </button>
                                    <button @click="search_article = ''" type="button" class="btn btn-outline-primary btn-icon waves-effect waves-light me-1">
                                        <i class="ri-delete-back-2-line"></i>
                                    </button>
                                    <button @click="new_item" type="button" class="btn btn-outline-success btn-icon waves-effect waves-light me-1">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                                <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
                                    <li class="list-group-item p-1" v-for="(article,index) in lote_form.lote_items">
                                        <div class="row g-1 mb-1">
                                            <div class="col-8">
                                                <div class="d-flex align-items-center">
                                                    <img :src="article.picture" alt="" class="rounded-circle avatar-sm me-2">
                                                    <div>
                                                        <p class="fw-bold mb-0 text-uppercase">{{article.descripcion}}</p>
                                                        <p class="text-reset fs-14 mb-0">{{article.cantidad}} x {{article.u_medida}} {{article.rubro}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <p class="text-reset fs-14 mb-0">{{article.m3_total}}</p>
                                                <p class="text-muted mb-0">{{article.valor_plaza}}</p>
                                            </div>
                                            <div class="col-auto ms-auto">
                                                <button @click="item_recycle(article)" class="btn btn-icon text-dark">
                                                    <i class="ri-recycle-line ri-xl"></i>
                                                </button>
                                                <button @click="item_edit(article)" class="btn btn-icon text-primary">
                                                    <i class="ri-edit-line ri-xl"></i>
                                                </button>
                                                <button @click="item_delete(index)" class="btn btn-icon text-danger">
                                                    <i class="ri-delete-bin-line ri-xl"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-name="formulario" v-if="page_form" class="row mb-2">
                    <div class="card">
                        <div class="card-header p-2">
                            <div class="row">
                                <div class="col">
                                    <h6>Datos de Articulo</h6>
                                </div>
                                <div class="col text-end">
                                    <button @click="item_close" type="button" class="btn-close text-reset" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-auto">
                                    <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                        <img :src="lote_item.picture" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
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
                                        <div class="col-sm">
                                            <label for="rubro" class="form-label">Rubro</label>
                                            <select v-model="lote_item.rubro" id="rubro" class="form-control">
                                                <option value="alimentos">Alimentos</option>
                                                <option value="aparatos electricos/fotografia">Aparatos Electricos/Fotografia</option>
                                                <option value="armas">Armas</option>
                                                <option value="art. construccion">Art. Construccion</option>
                                                <option value="art. deportivos">Art. Deportivos</option>
                                                <option value="art. ferreteria">Art. Ferreteria</option>
                                                <option value="art. libreria">Art. Libreria</option>
                                                <option value="art. mobiliario">Art. Mobiliario</option>
                                                <option value="bazar">Bazar</option>
                                                <option value="bebidas">Bebidas</option>
                                                <option value="bienes culturales">Bienes Culturales</option>
                                                <option value="calzado">Calzado</option>
                                                <option value="cigarrillo-tabaco">Cigarrillo-Tabaco</option>
                                                <option value="combustible">Combustible</option>
                                                <option value="computacion y accesorios">Computacion y accesorios</option>
                                                <option value="cosmeticos">Cosmeticos</option>
                                                <option value="estupefacientes">Estupefacientes</option>
                                                <option value="fauna y flora">Fauna y Flora</option>
                                                <option value="hojas de coca">Hojas de Coca</option>
                                                <option value="instrumento musical">Instrumento Musical</option>
                                                <option value="juegos y juguetes">Juegos y juguetes</option>
                                                <option value="maquinarias y accesorios">Maquinarias y Accesorios</option>
                                                <option value="marroquineria y accesorios personales">Marroquineria y Accesorios Personales</option>
                                                <option value="material belico y sensitivo">Material Belico y sensitivo</option>
                                                <option value="monedas-billetes">Monedas-Billetes</option>
                                                <option value="optica">Optica</option>
                                                <option value="pilas y baterias">Pilas y Baterias</option>
                                                <option value="pirotecnia">Pirotecnia</option>
                                                <option value="productos farmaceuticos">Productos Farmaceuticos</option>
                                                <option value="productos fonograficos">Productos Fonograficos</option>
                                                <option value="relojes">Relojes</option>
                                                <option value="repuestos y accesorios vehiculos">Repuestos y Accesorios Vehiculos</option>
                                                <option value="residuos">Residuos</option>
                                                <option value="ropa">Ropa</option>
                                                <option value="sustancias agotan capa ozono">Sustancias Agotan Capa Ozono</option>
                                                <option value="telefonia">Telefonia</option>
                                                <option value="textiles no considerados como ropa (blanqueria)">Textiles no considerados como ropa (Blanqueria)</option>
                                                <option value="transportacion(autos,aviones,motos,medido en m2)">Transportacion(autos,aviones,motos,medido en M2)</option>
                                            </select>
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-label" for="posible_destino">Posible Destino</label>
                                            <select v-model="lote_item.posible_destino" id="posible_destino" class="form-control">
                                                <option value="donacion">DONACION</option>
                                                <option value="destruccion">DESTRUCCION</option>
                                                <option value="restitucions">RESTITUCION</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label" for="descripcion">Descripcion</label>
                                            <textarea v-model="lote_item.descripcion" id="descripcion" class="form-control" placeholder="" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label class="form-label" for="cantidad">Cantidad</label>
                                    <input v-model="lote_item.cantidad" id="cantidad" type="text" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="u_medida">Medida</label>
                                    <select v-model="lote_item.u_medida" id="u_medida" class="form-control">
                                        <option value="unidad">UNIDAD</option>
                                        <option value="kg">KG</option>
                                        <option value="metro">METRO</option>
                                        <option value="litro">LITRO</option>
                                        <option value="gr.">GR.</option>
                                        <option value="cm.">CM.</option>
                                        <option value="cm. cubic">CM. CUBIC</option>
                                        <option value="caja">CAJA</option>
                                        <option value="bolsa">BOLSA</option>
                                        <option value="par">PAR</option>
                                        <option value="tonelada">TONELADA</option>
                                        <option value="docena">DOCENA</option>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="intervencion_inal">I.N.A.L</label>
                                    <select v-model="lote_item.intervencion_inal" id="intervencion_inal" class="form-control">
                                        <option value="NO">NO</option>
                                        <option value="SI">SI</option>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="intervencion_seguridad">Seg. Electrica</label>
                                    <select v-model="lote_item.intervencion_seguridad" id="intervencion_seguridad" class="form-control">
                                        <option value="NO">NO</option>
                                        <option value="SI">SI</option>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="intervencion_juguete">Juguetes</label>
                                    <select v-model="lote_item.intervencion_juguete" id="intervencion_juguete" class="form-control">
                                        <option value="NO">NO</option>
                                        <option value="SI">SI</option>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="m3_length">Largo en cm</label>
                                    <input v-model="lote_item.m3_length" id="m3_length" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="m3_width">Ancho en cm</label>
                                    <input v-model="lote_item.m3_width" id="m3_width" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="m3_height">Alto en cm</label>
                                    <input v-model="lote_item.m3_height" id="m3_height" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="m3_unit">m3 Unidad</label>
                                    <input v-model="lote_item.m3_unit" id="m3_unit" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="m3_total">m3 Total</label>
                                    <input v-model="lote_item.m3_total" id="m3_total" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label class="form-label" for="aforo">Aforado</label>
                                    <select v-model="lote_item.aforo" id="aforo" class="form-control">
                                        <option value="NO">NO</option>
                                        <option value="SI">SI</option>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="unitario_usd">Unitario Dolar</label>
                                    <input v-model="lote_item.unitario_usd" id="unitario_usd" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="aforo">Fecha Aforo</label>
                                    <input v-model="lote_item.aforo_fecha" id="aforo_fecha" type="date" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="ncm">N.C.M</label>
                                    <input v-model="lote_item.ncm" id="ncm" type="text" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="aforo">Momento Imponible</label>
                                    <input v-model="lote_item.momento_imponible" id="momento_imponible" type="date" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="cotizacion_dolar">Dolar</label>
                                    <input v-model="lote_item.cotizacion_dolar" id="cotizacion_dolar" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="valor_aduana">Valor Aduana</label>
                                    <input v-model="lote_item.valor_aduana" id="valor_aduana" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label class="form-label" for="derecho_impexp">Derechos</label>
                                    <input v-model="lote_item.derecho_impexp" id="derecho_impexp" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="tasa_estadistica">Tasa Estadistica</label>
                                    <input v-model="lote_item.tasa_estadistica" id="tasa_estadistica" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="impuesto_interno">Impuesto Interno</label>
                                    <input v-model="lote_item.impuesto_interno" id="impuesto_interno" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="iva_general">Iva General</label>
                                    <input v-model="lote_item.iva_general" id="iva_general" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="iva_adicional">Iva Adicional</label>
                                    <input v-model="lote_item.iva_adicional" id="iva_adicional" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="anticipo_ganancias">Anticipo Ganancias</label>
                                    <input v-model="lote_item.anticipo_ganancias" id="anticipo_ganancias" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="arancel_despachante">Arancel Despachante</label>
                                    <input v-model="lote_item.arancel_despachante" id="arancel_despachante" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label class="form-label" for="pesos_iva">Pesos Iva</label>
                                    <input v-model="lote_item.pesos_iva" id="pesos_iva" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="pesos_adicional">Pesos Adicional</label>
                                    <input v-model="lote_item.pesos_adicional" id="pesos_adicional" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="pesos_estadistica">Pesos Estadistica</label>
                                    <input v-model="lote_item.pesos_estadistica" id="pesos_estadistica" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="pesos_impuestos">Pesos Impuestos</label>
                                    <input v-model="lote_item.pesos_impuestos" id="pesos_impuestos" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="pesos_ganancias">Pesos Ganancias</label>
                                    <input v-model="lote_item.pesos_ganancias" id="pesos_ganancias" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="pesos_derechos">Pesos Derechos</label>
                                    <input v-model="lote_item.pesos_derechos" id="pesos_derechos" type="number" class="form-control">
                                </div>
                                <div class="col-sm">
                                    <label class="form-label" for="valor_plaza">Valor Plaza</label>
                                    <input v-model="lote_item.valor_plaza" id="valor_plaza" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a @click="item_close" class="btn btn-link link-danger fw-medium"><i class="ri-close-line me-1 align-middle"></i> Volver</a>
                            <button @click="add_item" type="button" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
                <div data-name="error" v-if="page_error" class="row justify-content-between align-items-center text-center">
                    <i class="ri-close-circle-line" style="font-size: 50px; color: red;"></i>
                    <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
                </div>
                <div data-name="spinner" v-if="page_spinner" class="row justify-content-center">
                    <div class="loader"></div>
                </div>
                <!-- Footer -->
                <footer class="footer"></footer>
            </div>
        </div>
    </div>