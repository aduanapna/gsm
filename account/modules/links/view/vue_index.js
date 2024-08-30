var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      url: getMeta("uri"),
      csrf: getMeta("csrf"),
      page_spinner: true,
      page_error: false,
      page_list: false,
      page_form: false,

      page_title: "",

      links: [],
    };
  },
  mounted: function () {
    this.get_data();
  },
  watch: {
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
  },
  computed: {},
  methods: {
    get_data: function () {
      this.page_spinner = true;
      const url = `${this.url}links/data`;
      let data = new FormData();
      data.append("csrf", this.csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.links = res.data.pages;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
          this.page_form = true;
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición get_data ${error}`, "bg-danger");
        });
    },
  },
});
