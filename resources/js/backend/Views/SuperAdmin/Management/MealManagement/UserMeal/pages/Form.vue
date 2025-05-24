<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{
              param_id
                ? `${setup.edit_page_title}`
                : `${setup.create_page_title}`
            }}
          </h5>
          <div>
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `All${setup.route_prefix}` }"
            >
              {{ setup.all_page_title }}
            </router-link>
          </div>
        </div>
        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="">User ID</label>
                <div class="mt-1 mb-3">
                  <select v-model="form_fields.user_id" class="form-control" name="user_id">
                    <option value="">Selet-- User Id</option>
                    <option
                      v-for="item in userData?.data"
                      :key="item.id"
                      :value="item.id"
                    >
                      {{ item.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="">Quantity</label>
                <div class="mt-1 mb-3">
                  <input
                    class="form-control form-control-square mb-2"
                    type="text"
                    name="quantity"
                    id="quantity"
                    v-model="form_fields.quantity"
                  />
                </div>
              </div>
              <div class="form-group">
                <label for="date">Date</label>
                <div class="mt-1 mb-3">
                  <input
                    type="date"
                    class="form-control form-control-square mb-2"
                    name="date"
                    id="date"
                    v-model="form_fields.date"
                  />
                </div>
              </div>
              <div class="form-group">
                <label for="">Meal Rate Id</label>
                <div class="mt-1 mb-3">
                  <input
                    class="form-control form-control-square mb-2"
                    type="text"
                    name="meal_rate_id"
                    id="meal_rate_id"
                    v-model="form_fields.meal_rate_id"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i> Submit
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../store";
import setup from "../setup";

export default {
  data: () => ({
    setup,
    param_id: null,
    form_fields: {
      user_id: "",
      quantity: "",
      date: "",
      meal_rate_id: "",
    },
    userData: [],
  }),
  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    if (id) {
      this.set_fields(id);
    }

    await this.get_user_data();
  },
  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
      
    }),

    async get_user_data() {
            try {
                let res = await axios.get('/users');
                this.userData = res.data.data;  // ✅ Assign data properly
            } catch (error) {
                console.error("Error fetching users", error);
            }
        },

    set_fields: async function (id) {
      this.param_id = id;
      await this.details(id);
      if (this.item) {
        this.form_fields.user_id = this.item.user_id;
        this.form_fields.quantity = this.item.quantity;
        this.form_fields.date = this.item.date;
        this.form_fields.meal_rate_id = this.item.meal_rate_id;
      }
    },

    submitHandler: async function ($event) {
      // this.set_only_latest_data(true);
      if (this.param_id) {
        let response = await this.update($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data successfully updated");
          this.$router.push({
            name: `All${this.setup.route_prefix}`,
          });
        }
      } else {
        let response = await this.create($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data Successfully Created");
          this.$router.push({
            name: `All${this.setup.route_prefix}`,
          });
        }
      }
    },
  },
  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>
