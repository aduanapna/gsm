var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      page_list: true,
      page_error: false,
      page_form: false,
      page_spinner: false,

      page_title: "Administracion de Links",
      page_current: 1,
      page_items: 30,
      page_data: {},

      search_link: "",
      selected_link: "index",
      listed_link: [],
      leaked_link: [],
      leaked_type: [],
      form_link: {},
    };
  },
  mounted: function () {
    this.get_data();
    this.list_link();
  },
  watch: {
    page_list: function () {
      if (this.page_list) {
        this.page_spinner = false;
        this.page_error = false;
        this.page_form = false;
      }
    },
    page_form: function () {
      if (this.page_form) {
        this.page_spinner = false;
        this.page_error = false;
        this.page_list = false;
      }
    },
    page_error: function () {
      if (this.page_error) {
        this.page_spinner = false;
        this.page_list = false;
        this.page_form = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_error = false;
        this.page_list = false;
        this.page_form = false;
      }
    },
    search_link: function () {
      this.filter_link();
    },
    selected_link: function () {
      this.filter_link();
    },
  },
  computed: {},
  methods: {
    get_data: function () {
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
          toastr(`Hubo un error en la petición get_data ${error}`, "bg-danger");
        });
    },
    picture_upload: function () {
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
                this.form_link.link_picture = res.data;
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
    new_link: function () {
      this.form_link = {};

      const url = `${this.url}_links/new`;
      let data = new FormData();
      data.append("csrf", this.csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.form_link = res.data;
              this.form_link.link_page = this.selected_link;
              break;
            default:
              get_action(res.status, res.msg, res.data);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición add_link ${error}`, "bg-danger");
        });
    },
    copy_link: function (link) {
      this.form_link = link;
      this.form_link.link_id = "";

      link = null;
    },
    view_link: function (link) {
      this.form_link = link;
    },
    delete_link: function (link) {
      Swal.fire({
        text: `¿Seguro desea eliminar?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${this.url}_links/delete`;
          let data = new FormData();
          data.append("csrf", getMeta("csrf"));
          data.append("form", JSON.stringify(link));

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    this.listed_link = res.data;
                    this.filter_link();
                    toastr("Elemento Eliminado", "bg-success");
                  }
                  break;
                default:
                  get_action(res.status, res.msg);
                  this.page_error = true;
                  break;
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición articleDelete ${error}`, "bg-danger");
            });
        }
      });
    },
    save_link: function () {
      if (!this.form_link.link_name) {
        toastr("Debe completar campo Texto.", "bg-danger");
        return;
      }
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("form", JSON.stringify(this.form_link));

      if (this.form_link.link_id != "") {
        url = `${this.url}_links/update`;
        data.append("action", "update");
      } else {
        url = `${this.url}_links/add`;
        data.append("action", "add");
      }

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.listed_link = res.data;
                this.filter_link();
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición articleNew ${error}`, "bg-danger");
        });
    },
    status_link: function (link) {
      link.spinner = true;
      let data = new FormData();
      data.append("csrf", this.csrf);
      data.append("form", JSON.stringify(link));

      url = `${this.url}_links/change`;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                Object.assign(link, res.data);
                this.filter_link();
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición status_link ${error}`, "bg-danger");
        });
    },
    list_link: function () {
      this.page_spinner = true;
      const url = `${this.url}_links/list`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.listed_link = res.data;
              this.filter_link();
              this.page_list = true;
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición listar_link ${error}`, "bg-danger");
        });
    },
    filter_link: function () {
      let text = this.search_link.toLowerCase();
      let selected = this.selected_link.toLowerCase();
      let array_list = this.listed_link.filter((item) => item.keywords.toLowerCase().includes(text) && item.link_page.toLowerCase().includes(selected));

      this.leaked_link = array_list;

      this.leaked_type = array_list
        .filter((item) => item.link_condition)
        .reduce((acc, item) => {
          if (!acc[item.link_type]) {
            acc[item.link_type] = [];
          }
          acc[item.link_type].push(item);
          return acc;
        }, {});
      //this.page_current = 1;
    },
  },
});
