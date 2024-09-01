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
      lote_item: { picture: "" },
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
      this.lote_items = {};
      this.lote_item = { picture: "" };
      this.lote_edit = false;
      this.page_list = false;
      this.page_form = false;
    },
    lote_save: function () {
      this.btn_save = false;
      try {
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
      this.lote_item.lote_item_id = 0;
      this.lote_item.lote_item_bound = this.lote_id;
      this.lote_item.picture = this.urlimages + "_nodisponible.jpg";
      this.lote_item.rubro = "alimentos";
      this.lote_item.u_medida = "UNIDAD";
      this.lote_item.intervencion_inal = "NO";
      this.lote_item.intervencion_seguridad = "NO";
      this.lote_item.intervencion_juguete = "NO";
      this.lote_item.keywords = "";
      this.page_form = true;
    },
    save_item: function () {
      if (!this.item_edition) {
        this.lote_items.push(this.lote_item);
        toastr("Artículo agregado", "bg-secondary");
        console.log("Artículo agregado");
      } else {
        //this.$set(this.lote_items, this.item_index, this.lote_item);
        toastr("Artículo actualizado", "bg-success");
        this.item_edition = false
        console.log("Artículo actualizado");
      }
      this.filter_items();
      this.lote_item = { picture: "" };
      this.page_list = true;
    },
    item_edit: function (item) {
      this.item_edition = true;
      this.lote_item = item;
      this.page_form = true;
    },
    item_recycle: function (item) {
      this.item_edition = false;
      let recycle = Object.assign({}, item);
      this.lote_item = recycle;
      this.lote_item.lote_item_id = 0;
      this.page_form = true; 
    },
    item_dispose: function (item, index) {
      this.item_edition = true;
      this.lote_item = item;
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
          if (item.lote_item_id) {
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
