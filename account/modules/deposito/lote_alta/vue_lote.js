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
      lote_item: { picture: "" },
      editing_index: -1, // Nuevo: índice del artículo que se está editando
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
      }
    },
    set_session: function () {
      sessionStorage.lote_form = JSON.stringify(this.lote_form);
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
      /* Eliminamos los datos de la variables */
      this.lote_form = [];

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
                this.lote_item = { picture: "" };
                thisediting_index = -1;
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
        if (vm.lote_form.lote_items.length === 0) {
          throw new toastr("Debe seleccionar articulos", "bg-danger");
        }
      }
      try {
        check_items();
        url = `${this.url}_lotes/lote_add`;

        let data = new FormData();
        data.append("csrf", getMeta("csrf"));
        data.append("lote_form", JSON.stringify(this.lote_form));

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

      array_list = array_list.filter((item) => item.food_metadata.match(text));
      this.page_articles = array_list;
    },

    new_item: function () {
      this.lote_item.picture = this.urlimages + "_nodisponible.jpg";
      this.lote_item.rubro = "alimentos";
      this.lote_item.u_medida = "unidad";
      this.lote_item.intervencion_inal = "no";
      this.lote_item.intervencion_seguridad = "no";
      this.lote_item.intervencion_juguete = "no";
      this.page_form = true;
    },
    add_item: function () {
      if (this.editing_index === -1) {
        // Si no se está editando, agregar un nuevo artículo
        this.lote_form.lote_items.push(this.lote_item);
        toastr("Artículo agregado", "bg-secondary");
      } else {
        // Si se está editando, actualizar el artículo existente
        this.$set(this.lote_form.lote_items, this.editing_index, this.lote_item);
        toastr("Artículo actualizado", "bg-secondary");
        this.editing_index = -1; // Resetear el índice de edición
      }
      this.set_session();
      this.lote_item = { picture: "" };
      this.page_list = true;
    },
    item_edit: function (article, index) {
      this.lote_item = article;
      this.editing_index = index; // Establecer el índice del artículo a editar
      this.page_form = true;
    },
    item_recycle: function (article) {
      let item = Object.assign({}, article);
      this.lote_item = item;
      this.editing_index = -1; // Establecer el índice del artículo a editar
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
          this.lote_form.lote_items.splice(index, 1);
          this.$refs.search_article.focus();
          toastr("Articulo eliminado", "bg-secondary");
          this.set_session();
        }
      });
    },
    item_close: function () {
      this.page_list = true;
      // this.lote_item = { picture: "" };
      // this.editing_index = -1; // Nuevo: índice del artículo que se está editando
    },
  },
});
