var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      page_spinner: true,
      page_error: false,
      page_list: true,

      page_title: "Lotes Disponibles",
      page_options: [],
      page_articles: [],
      search_lotes: "",

      radio_lotes: "pending",
      radio_toolbar: [],
      pending_lotes: [],
      lote_listed: [],
      leaked_lotes: [],
      lote_form: { lote_items: {} },
      lote_conditions: {},
      lote_done: 1,
      form_cancel: [],
      positive_class: "",
    };
  },
  mounted: function () {
    this.get_options();
    /*  setInterval(() => {
      this.refresh();
    }, 10000); */
  },
  watch: {
    search_lotes: function () {
      this.filter_lotes();
    },
    lote_done: function () {
      this.filter_lotes();
    },
    radio_lotes: function () {
      this.filter_lotes();
    },
  },
  computed: {},
  methods: {
    refresh: function () {
      this.get_options();
    },
    get_options: function () {
      this.page_spinner = true;
      const url = `${this.url}_lotes/lotes_available`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.lote_listed = res.data.lotes;
                this.lote_conditions = res.data.lotes_condition;
                this.filter_lotes();
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
          this.page_spinner = false;
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición get_options ${error}`, "bg-danger");
        });
    },
    filter_lotes: function () {
      let array_list = this.lote_listed;
      let text = this.search_lotes.toLowerCase();
      array_list = array_list.filter((item) => item.keywords.match(text) && item.lote_condition == this.lote_done);

      this.leaked_lotes = array_list;
      this.page_list = true;
    },
    lote_update: function () {
      const url = `${this.url}_lotes/lote_update`;

      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("lote_form", JSON.stringify(this.lote_form));
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              toastr(res.msg, "bg-success");
              this.refresh();
              break;
            case 201:
              toastr(res.msg, "bg-danger");
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición lote_cancels ${error}`, "bg-danger");
        });
    },
    lote_delete: function (lote) {
      Swal.fire({
        text: `¿Seguro desea eliminar el articulo seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${this.url}_lotes/lote_delete`;
          let data = new FormData();
          data.append("csrf", getMeta("csrf"));
          data.append("lote_form", JSON.stringify(lote));
          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  toastr(res.msg, "bg-success");
                  this.refresh();
                  break;
                default:
                  get_action(res.status, res.msg);
                  break;
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición lote_cancels ${error}`, "bg-danger");
            });
        }
      });
    },
    lote_view: function (lote) {
      let itemX = Object.assign({}, lote);
      this.lote_form = itemX;
    },
  },
});
