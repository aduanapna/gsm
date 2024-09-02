var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      urlimages: getMeta("urlimages"),
      page_title: "Cargar nueva Orden",
      page_spinner: false,
      page_error: false,
      page_list: true,
      page_form: false,
      page_options: [],

      page_articles: [],
      search_article: "",

      lote_form: {},
      item_form: { picture: "" },
      item_edition: false,
      item_index: -1,
      btn_save: true,
    };
  },
  created: function () {
    this.get_options();
  },
  mounted: function () {},
  watch: {
    search_article: function () {
      if (this.search_article.length >= 3) {
        this.filter_items();
      } else {
        this.page_articles = [];
      }
    },
    page_list: function () {
      if (this.page_list) {
        this.page_form = false;
        this.page_spinner = false;
        this.page_error = false;
      }
    },
    page_form: function () {
      if (this.page_form) {
        this.page_list = false;
        this.page_spinner = false;
        this.page_error = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_list = false;
        this.page_form = false;
        this.page_error = false;
      }
    },
    page_error: function () {
      if (this.page_error) {
        this.page_list = false;
        this.page_form = false;
        this.page_spinner = false;
      }
    },
  },
  computed: {},
  methods: {
    get_session: function () {
      if (!sessionStorage.lote_form) {
        this.lote_new();
      } else {
        this.lote_form = JSON.parse(sessionStorage.lote_form);
        this.lote_items = JSON.parse(sessionStorage.lote_items);
      }
    },
    set_session: function () {
      sessionStorage.lote_form = JSON.stringify(this.lote_form);
      sessionStorage.lote_items = JSON.stringify(this.lote_items);
    },

    get_options: function () {
      const url = `${this.url}_lotes/data`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                this.page_options = res.data;
                this.get_session();
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

    lote_new: function () {
      /* Eliminamos los datos del sessionStorage */
      sessionStorage.removeItem("lote_form");
      sessionStorage.removeItem("lote_items");
      /* Eliminamos los datos de la variables */
      this.lote_form = [];
      this.lote_items = [];
      const url = `${this.url}_lotes/lote_new`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                toastr(res.msg, "bg-info");
                this.lote_form = res.data;
                this.item_form = { picture: "" };
                this.item_index = -1;
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición new_lote ${error}`, "bg-danger");
        });
    },
    lote_save: function () {
      this.btn_save = false;
      function check_items() {
        if (vm.lote_items.length === 0) {
          throw new toastr("Debe seleccionar articulos", "bg-danger");
        }
      }
      try {
        check_items();
        url = `${this.url}_lotes/lote_add`;

        let data = new FormData();
        data.append("csrf", getMeta("csrf"));
        data.append("lote_form", JSON.stringify(this.lote_form));
        data.append("lote_items", JSON.stringify(this.lote_items));
        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != {}) {
                  toastr(res.msg, "bg-success");
                  this.btn_save = true;
                  this.lote_new();
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.btn_save = true;
                break;
            }
          })
          .catch(function (error) {
            toastr(`Hubo un error en la petición get_options ${error}`, "bg-danger");
          });
      } catch (error) {
        this.btn_save = true;
        return;
      }
    },
    filter_items: function () {
      let array_list = this.page_options.foods;
      let text = this.search_article.toLowerCase();

      array_list = array_list.filter((item) => item.descripcion.match(text));
      this.page_articles = array_list;
    },

    new_item: function () {
      this.item_form.picture = this.urlimages + "_nodisponible.jpg";
      this.item_form.rubro = "alimentos";
      this.item_form.u_medida = "unidad";
      this.item_form.intervencion_inal = "no";
      this.item_form.intervencion_seguridad = "no";
      this.item_form.intervencion_juguete = "no";
      this.page_form = true;
    },
    save_item: function () {
      if (!this.item_edition) {
        this.lote_items.push(this.item_form);
        toastr("Artículo agregado", "bg-secondary");
      } else {
        this.$set(this.lote_items, this.item_index, this.item_form);
        toastr("Artículo actualizado", "bg-secondary");
        this.item_edition = false;
      }
      this.set_session();
      this.item_form = { picture: "" };
      this.page_list = true;
    },
    item_edit: function (item, index) {
      this.item_edition = true;
      let itemX = Object.assign({}, item);
      this.item_form = itemX;
      this.item_index = index;
      this.page_form = true;
    },
    item_recycle: function (item) {
      this.item_edition = false;
      let itemX = Object.assign({}, item);
      this.item_form = itemX;
      this.page_form = true;
    },
    item_delete: function (index) {
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
          this.lote_items.splice(index, 1);
          this.$refs.search_article.focus();
          toastr("Articulo eliminado", "bg-secondary");
          this.set_session();
        }
      });
    },
    item_close: function () {
      this.page_list = true;
      // this.item_form = { picture: "" };
      // this.editing_index = -1; // Nuevo: índice del artículo que se está editando
    },
  },
});
