<div class="auth-page-wrapper pt-4 bg-dark" id="app" store_id="<?php echo $site->token->store_id ?>">
  <div class="auth-page-content">
    <div class="container">
      <div class="row">
        <div class="row justify-content-center">
          <div class="col-12 text-center mb-4">
            <img src="<?php echo $site->logo ?>" class="img-fluid" style="height:48px;">
            <button onclick="toggleFullscreen()" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"><i class="ri-fullscreen-line"></i></button>
          </div>
          <div class="col-12 text-center">
            <h4 class="text-light"><?php echo $site->token->store_name ?></h4>
            <div class="input-group mb-2">
              <input v-model="display" @keypress.enter="set_income" class="form-control form-control-lg" type="number" max="8" placeholder="DNI">
              <button @click="set_income" :disable="btn_save" class="btn btn-outline-danger btn-lg" type="button" id="button-addon2">
                <i v-if="!btn_save" class="ri-history-line label-icon align-middle"></i>
                Ingresar
              </button>
            </div>
          </div>
          <div class="col-12 bd-highlight d-lg-none d-xl-none">
            <div class="btn-group btn-group-lg mb-2 w-100" role="group" aria-label="Basic example">
              <button @click="add(7)" type="button" class="btn btn-outline-light">7</button>
              <button @click="add(8)" type="button" class="btn btn-outline-light">8</button>
              <button @click="add(9)" type="button" class="btn btn-outline-light">9</button>
            </div>
            <div class="btn-group btn-group-lg mb-2 w-100" role="group" aria-label="Basic example">
              <button @click="add(4)" type="button" class="btn btn-outline-light">4</button>
              <button @click="add(5)" type="button" class="btn btn-outline-light">5</button>
              <button @click="add(6)" type="button" class="btn btn-outline-light">6</button>
            </div>
            <div class="btn-group btn-group-lg mb-2 w-100" role="group" aria-label="Basic example">
              <button @click="add(1)" type="button" class="btn btn-outline-light">1</button>
              <button @click="add(2)" type="button" class="btn btn-outline-light">2</button>
              <button @click="add(3)" type="button" class="btn btn-outline-light">3</button>
            </div>
            <div class="btn-group btn-group-lg mb-2 w-100" role="group" aria-label="Basic example">
              <button @click="add(0)" type="button" class="btn btn-outline-light">0</button>
              <button @click="clearDisplay()" type="button" class="btn btn-outline-light">C</button>
              <button @click="add(num)" type="button" class="btn btn-outline-light">></button>
            </div>
          </div>
          <div class="col-12 d-none d-lg-block">
            <div class="card mb-3">
              <div class="card-body p-1">
                <div class="table-responsive">
                  <table class="table text-center">
                    <thead>
                      <tr>
                        <th scope="col">NOMBRE COMPLETO</th>
                        <th scope="col">DNI</th>
                        <th scope="col">HORARIO INGRESO</th>
                      </tr>
                    </thead>
                    <tbody class="table-dark">
                      <tr v-for="(income,index) in incomes_list">
                        <th scope="row" class="text-start">{{income.person_lastname}} {{income.person_name}}</th>
                        <td>{{income.person_document}}</td>
                        <td>{{income.income_entry}}</td>
                      </tr>
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
<script>
  function toggleFullscreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen();
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      }
    }
  }
</script>