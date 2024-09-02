var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      urlimages: getMeta("urlimages"),
      page_title: "Editar Lote",
      page_spinner: false,
      page_error: false,
      page_list: false,
      page_form: false,
      page_options: [],
      search_item: "",

      lote_number: "",
      lote_id: "",
      lote_items: [],
      lote_deletes: [],
      lote_leaked: {},
      item_form: { picture: "" },
      item_index: -1,
      lote_edit: false,
      item_edition: false,
      item_done: "all",
      btn_save: true,
    };
  },
  created: function () {},
  mounted: function () {},
  watch: {
    search_item: function () {
      this.filter_items();
    },
    item_done: function () {
      this.filter_items();
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
    lote_view: function () {
      const url = `${this.url}_lotes/lote_view`;
      let data = new FormData();
      data.append("csrf", getMeta("csrf"));
      data.append("lote_number", this.lote_number);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                this.lote_id = res.data.lote_id;
                this.lote_items = res.data.lote_items;
                this.lote_edit = true;
                this.page_list = true;
                this.filter_items();
              }
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
          toastr(`Hubo un error en la petición new_lote ${error}`, "bg-danger");
        });
    },
    lote_clean: function () {
      this.lote_number = "";
      this.lote_items = [];
      this.item_form = { picture: "" };
      this.lote_edit = false;
      this.page_list = false;
      this.page_form = false;
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
        url = `${this.url}_lotes/item_update`;

        let data = new FormData();
        data.append("csrf", getMeta("csrf"));
        data.append("lote_items", JSON.stringify(this.lote_items));
        data.append("lote_deletes", JSON.stringify(this.lote_deletes));
        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != {}) {
                  toastr(res.msg, "bg-success");
                  this.btn_save = true;
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
      let array_list = this.lote_items;
      let text = this.search_item.toLowerCase();

      array_list = array_list.filter((item) => item.keywords.match(text) && item.gestionado !== this.item_done);
      this.lote_leaked = array_list;
    },

    new_item: function () {
      this.item_form.item_form_id = 0;
      this.item_form.item_form_bound = this.lote_id;
      this.item_form.picture = this.urlimages + "_nodisponible.jpg";
      this.item_form.rubro = "alimentos";
      this.item_form.u_medida = "UNIDAD";
      this.item_form.intervencion_inal = "NO";
      this.item_form.intervencion_seguridad = "NO";
      this.item_form.intervencion_juguete = "NO";
      this.item_form.keywords = "";
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
      this.filter_items();
      this.item_form = { picture: "" };
      this.item_index = -1;
      this.page_list = true;
    },
    item_edit: function (item, index) {
      this.item_edition = true;
      let itemX = Object.assign({}, item);
      this.item_form = itemX;
      this.item_index = index;
      this.page_form = true;
    },
    item_disponse: function (item, index) {
      this.item_edition = true;
      let itemX = Object.assign({}, item);
      this.item_form = itemX;
      this.item_index = index;
    },
    item_recycle: function (item) {
      this.item_edition = false;
      let recycle = Object.assign({}, item);
      this.item_form = recycle;
      this.item_form.item_form_id = 0;
      this.page_form = true;
    },
    item_delete: function (item, index) {
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
          if (item.item_form_id) {
            this.lote_deletes.push(item);
          }
          this.lote_items.splice(index, 1);
          this.$refs.search_item.focus();
          toastr("Articulo eliminado", "bg-secondary");
          this.filter_items();
        }
      });
    },
    item_close: function () {
      this.page_list = true;
    },
  },
});
