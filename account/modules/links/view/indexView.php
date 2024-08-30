  <div class="auth-page-wrapper pt-4" id="app">
      <div class="auth-page-content p-2" v-cloak>
          <div class="container">
              <div data-name="spinner" v-if="page_spinner" class="row justify-content-center">
                  <div class="loader"></div>
              </div>
              <div data-name="forms" v-if="page_form" class="row">
                  <div class="col-lg-12">
                      <div class="text-center mt-sm-5 pt-4 mb-4">
                          <a v-for="(item, index) in links.logo" :key="item.link_id" :href="item.link_href" class="mb-2">
                              <img :src="item.link_picture" class="img-fluid" style="height:48px;">
                          </a>
                          <div class="row justify-content-center mt-2" v-if="links.image">
                              <template v-for="(item, index) in links.image">
                                  <div :key="item.link_id" class="col-md-6 col-lg-4 mt-2">
                                      <img :class="item.link_class" :alt="item.link_name" :src="item.link_picture">
                                  </div>
                              </template>
                          </div>
                          <div class="row justify-content-center mt-2" v-if="links.button">
                              <div class="col-sm-10 col-lg-4 bd-highlight">
                                  <a v-for="(item, index) in links.button" :key="item.link_id" :href="item.link_href" :class="item.link_class" :style="item.link_style">
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
      <footer class="footer">
          <div class="container-fluid">
              <div class="text-sm-end d-none d-sm-block">
                  <a href="https://imaginedesign.ar" class="text-white">iD</a>
              </div>
          </div>
      </footer>
  </div>