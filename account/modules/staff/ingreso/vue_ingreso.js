var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      store_id: 0,
      display: "",
      incomes_list: {},
      btn_save: true,
    };
  },
  mounted: function () {
    this.store_id = this.$el.getAttribute("store_id");
    this.list_incomes();
  },
  watch: {},
  computed: {},
  methods: {
    add(num) {
      if (this.display.length < 8) {
        this.display += num;
      }
    },
    clearDisplay() {
      this.display = "";
    },
    list_incomes: function () {
      let url = `${this.url}_incomes/list`;
      let data = new FormData();
      data.append("csrf", this.csrf);
      data.append("income_store_id", this.store_id);
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                this.incomes_list = res.data;
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
          this.btn_save = true;
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición get_options ${error}`, "bg-danger");
        });
    },
    set_income: function () {
      let url = `${this.url}_incomes/set`;
      let data = new FormData();
      data.append("csrf", this.csrf);
      data.append("income_person_id", this.display);
      data.append("income_store_id", this.store_id);
      this.btn_save = false;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                toastr(res.msg, "bg-success");
                this.incomes_list = res.data;
                break;
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
          this.btn_save = true;
          this.clearDisplay();
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición get_options ${error}`, "bg-danger");
        });
    },
    hours_income: function () {
      let url = `${this.url}_incomes/resume_incomes`;
      let data = new FormData();
      data.append("csrf", this.csrf);
      data.append("income_entry", this.income_entry);
      data.append("income_exit", this.income_exit);
      this.btn_save = false;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != {}) {
                toastr(res.msg, "bg-success");
                break;
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
          this.btn_save = true;
          this.clearDisplay();
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición get_options ${error}`, "bg-danger");
        });
    },
  },
});
