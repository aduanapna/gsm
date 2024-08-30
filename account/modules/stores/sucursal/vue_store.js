var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      page_form: false,
      page_error: false,
      page_spinner: true,

      page_title: "Administracion de Sucursal",
      page_options: {},
      form_store: {},
    };
  },
  mounted: function () {
    this.get_options();
    this.view_store();
  },
  watch: {
    page_form: function () {
      if (this.page_form) {
        this.page_spinner = false;
        this.page_error = false;
      }
    },
    page_error: function () {
      if (this.page_error) {
        this.page_form = false;
        this.page_spinner = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_form = false;
        this.page_error = false;
      }
    },
  },
  computed: {},
  methods: {
    get_options: function () {
      const url = `${this.url}gestion/data`;
      let data = new FormData();
      data.append("csrf", this.csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                this.page_options = res.data;
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición get_options ${error}`, "bg-danger");
        });
    },
    profile_picture_upload: function () {
      const url_upload = getMeta("urlupload");
      const filename = this.$refs.profile_picture.files[0];

      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("filename", filename);

      axios
        .post(url_upload, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.form_store.store_picture = res.data;
                toastr(res.msg, "bg-success");
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición profile_picture_upload ${error}`, "bg-danger");
        });
    },
    view_store: function (store) {
      const url = `${this.url}_stores/one`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.form_store = res.data;
              this.page_form = true; 
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición view_store ${error}`, "bg-danger");
        });
    },
    save_store: function () {
      if (!this.form_store.store_name) {
        toastr("Debe completar Nombre.", "bg-danger");
        return;
      }
      if (!this.form_store.store_address) {
        toastr("Debe completar campo Direccion.", "bg-danger");
        return;
      }
      this.page_spinner = true;
      url = `${this.url}_stores/update`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("form", JSON.stringify(this.form_store));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                toastr(res.msg, "bg-success");
                this.page_form = true;
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
        });
    },
  },
});
