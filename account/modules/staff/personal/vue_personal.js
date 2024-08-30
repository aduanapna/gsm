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

      page_title: "Administracion de Personal",
      page_current: 1,
      page_items: 30,
      page_options: {},
      page_componets: {},

      search_staff: "",
      listed_staff: {},
      leaked_staff: {},
      form_staff: {},
      profile_administrator: 0,
      profile_operador: 0,
      profile_encargado: 0,
      profile_cajero: 0,
    };
  },
  mounted: function () {
    this.list_staff();
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
    search_staff: function () {
      this.filter_staff();
    },
  },
  computed: {},
  methods: {
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
                this.form_staff.person_picture = res.data;
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
    new_staff: function () {
      this.page_spinner = true;
      const url = `${this.url}_staff/new`;
      let data = new FormData();
      data.append("csrf", this.csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.form_staff = res.data;
              this.edad();
              this.page_form = true;
              break;
            default:
              get_action(res.status, res.msg, res.data);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición add_staff ${error}`, "bg-danger");
        });
    },
    view_staff: function (staff) {
      console.time();
      let data = new FormData();
      const url = `${this.url}_staff/view`;
      data.append("csrf", getMeta("csrf"));
      data.append("form", JSON.stringify(staff));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.form_staff = res.data;
              this.edad();
              this.page_form = true;
              console.timeEnd();
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición view_staff ${error}`, "bg-danger");
        });
    },
    delete_staff: function (staff) {
      Swal.fire({
        text: `¿Seguro desea eliminar el personal seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          console.time();
          staff.spinner = true;
          const url = `${this.url}_staff/delete`;
          let data = new FormData();
          data.append("csrf", getMeta("csrf"));
          data.append("form", JSON.stringify(staff));

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    this.listed_staff = res.data;
                    this.filter_staff();
                    toastr(res.msg, "bg-success");
                    console.timeEnd();
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
    save_staff: function () {
      if (!this.form_staff.person_document) {
        toastr("Debe completar campo DNI.", "bg-danger");
        return;
      }
      if (!this.form_staff.person_name) {
        toastr("Debe completar campo Nombre.", "bg-danger");
        return;
      }
      /* if (!this.form_staff.person_lastname) {
        toastr("Debe completar campo Apellidos.", "bg-danger");
        return;
      } */
      if (!this.form_staff.person_cellphone) {
        toastr("Debe completar campo Celular.", "bg-danger");
        return;
      }
      if (this.form_staff.person_cellphone.length < 10) {
        toastr("Celular invalido", "bg-danger");
        return;
      }
      this.page_spinner = true;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("form", JSON.stringify(this.form_staff));

      if (this.form_staff.person_id != "") {
        url = `${this.url}_staff/update`;
        data.append("action", "update");
      } else {
        url = `${this.url}_staff/add`;
        data.append("action", "add");
      }

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.listed_staff = res.data;
                toastr(res.msg, "bg-success");
                this.filter_staff();
                this.page_list = true;
              }
              break;
            case 400:
              toastr(res.msg, "bg-danger");
              this.page_form = true;
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
    profile_staff: function (staff) {
      console.time();
      staff.spinner = true;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("form", JSON.stringify(staff));

      url = `${this.url}_staff/profile`;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                toastr(res.msg, "bg-success");
                staff.spinner = false;
                console.timeEnd();
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición status_staff ${error}`, "bg-danger");
        });
    },
    status_staff: function (staff) {
      staff.spinner = true;
      let data = new FormData();
      data.append("csrf", this.csrf);
      data.append("form", JSON.stringify(staff));

      url = `${this.url}_staff/change_condition`;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                staff.person_condition = res.data.person_condition;
                staff.person_condition_color = res.data.person_condition_color;
                staff.spinner = false;
                console.timeEnd();
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición status_staff ${error}`, "bg-danger");
        });
    },
    reset_pass: function (staff) {
      Swal.fire({
        text: `¿Seguro desea resetear contraseña de personal seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, resetear",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          console.time();
          staff.spinner = true;
          let data = new FormData();
          url = `${this.url}_staff/pass`;
          data.append("csrf", this.csrf);
          data.append("form", JSON.stringify(staff));
          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    toastr(res.msg, "bg-success");
                    staff.spinner = false;
                    console.timeEnd();
                  }
                  break;
                default:
                  get_action(res.status, res.msg);
                  this.page_error = true;
                  break;
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición status_staff ${error}`, "bg-danger");
            });
        }
      });
    },
    list_staff: function () {
      this.page_spinner = true;
      const url = `${this.url}_staff/list`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.listed_staff = res.data;
              this.filter_staff();
              this.page_list = true;
              console.timeEnd();
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición listar_staff ${error}`, "bg-danger");
        });
    },
    edad: function () {
      this.form_staff.edad = calculate_age(this.form_staff.person_birthday);
    },
    filter_staff: function () {
      let text = this.search_staff;
      let array_list = this.listed_staff.filter((item) => item.keywords.toLowerCase().includes(text));
      this.leaked_staff = array_list;
      //this.page_current = 1;
    },
  },
});
