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
              v-if="item.slug"
              class="btn btn-outline-info mr-2 btn-sm"
              :to="{
                name: `Details${setup.route_prefix}`,
                params: { id: item.slug },
              }"
            >
              {{ setup.details_page_title }}
            </router-link>
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
            <div class="col-md-12">
              <div class="row mb-2">
                <div class="col-md-4 pull-left">
                  <div class="mb-2">
                    <label class="form-label">Quantity</label>
                    <input
                      type="number"
                      name="quantity"
                      v-model="form_fields.quantity"
                      class="form-control"
                      min="1"
                      max="1"
                    />
                  </div>
                </div>

                <div class="col-md-4 pull-left">
                  <div class="mb-2">
                    <label class="form-label">Date</label>
                    <input
                      type="date"
                      name="date"
                      v-model="form_fields.date"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button
            type="button"
            class="btn btn-light btn-square px-5"
            @click="studentMealUpdate"
            :disabled="isMealUpdateDisabled(form_fields.date || item?.date)"
          >
            <i class="icon-lock"></i>
            Update
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
import form_fields from "../setup/form_fields";
import axios from "axios";

export default {
  data: () => ({
    // user_id: window.auth_user?.id || null,
    setup,
    param_id: null,
    all_user: [],
    user_id: null,
    filteredUsers: [],
    all_meals: [],
    res: null,
    meal_quantity: "",
    offMealDate: "",
    error: "",
    off_date: null,

    form_fields: {},
  }),

  created: async function () {
    this.get_all_meals();
    let id = (this.param_id = this.$route.params.id);
    // console.log("route id", id);
    if (id) {
      this.set_fields(id);
    }

    // const today = new Date();
    // const year = today.getFullYear();
    // const month = today.getMonth();

    // this.form_fields = this.getDatesOfMonth(year, month);

    // this.form_fields = this.form_fields.map((date) => ({
    //   quantity: 1,
    //   date: date,
    //   checked: false,
    // }));
  },

  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),

    // studentMealUpdate: async function () {
    //   const payload =
    //     this.form_fields && Object.keys(this.form_fields).length
    //       ? this.form_fields
    //       : this.item || {};

    //   const id = this.item?.slug || this.param_id || payload.id;
    //   if (!id) {
    //     window.s_alert("Cannot update meal: missing item id.");
    //     return;
    //   }

    //   if (!payload.date) {
    //     window.s_alert("Please provide a date for the meal.");
    //     return;
    //   }

    //   const quantity = Number(payload.quantity) || 0;
    //   if (quantity <= 0) {
    //     window.s_alert("Quantity must be at least 1.");
    //     return;
    //   }

    //   if (this.isMealUpdateDisabled(payload.date)) {
    //     window.s_alert("Meal Update not allowed now!");
    //     return;
    //   }

    //   try {
    //     const url = `user-meals/student-update-meal/${id}`;
    //     // console.log("POST ->", url, { quantity, date: payload.date });
    //     let res = await axios.post(url, {
    //       quantity,
    //       date: payload.date,
    //     });

    //     this.res = res.data?.data;
    //     window.s_alert("Meal updated successfully!");
    //     this.$router.push({ name: `All${this.setup.route_prefix}` });
    //   } catch (error) {
    //     console.error("API error:", error.response?.data || error);
    //     window.s_alert("Update failed. Check console for details.");
    //   }
    // },



    studentMealUpdate: async function () {
      const payload =
        this.form_fields && Object.keys(this.form_fields).length
          ? this.form_fields
          : this.item || {};

      const id = this.item?.slug || this.param_id || payload.id;
      if (!id) {
        window.s_alert("Cannot update meal: missing item id.");
        return;
      }

      if (!payload.date) {
        window.s_alert("Please provide a date for the meal.");
        return;
      }
      const quantity = Number(payload.quantity) || 0;
      // console.log("Quantity:", quantity);

      if (quantity < 1) {
        window.s_alert("Quantity must be at least 1.");
        return;
      }
      
      if (quantity > 1) {
        window.s_alert("You can take maximum 1 meal only!");
        return;
      }

      if (this.isMealUpdateDisabled(payload.date)) {
        window.s_alert("Meal update is not allowed at this time.");
        return;
      }

      try {
        const url = `user-meals/student-update-meal/${id}`;

        const res = await axios.post(url, {
          quantity: 1, 
          date: payload.date
        });

        this.res = res.data?.data;
        window.s_alert("Meal updated successfully!");

        this.$router.push({ name: `All${this.setup.route_prefix}` });

      } catch (error) {
        console.error("API error:", error.response?.data || error);
        window.s_alert("Meal update failed. Please try again.");
      }
    },


    isMealUpdateDisabled(date) {
      const now = new Date();
      const todayStr = now.toISOString().slice(0, 10);

      const currentMinutes = now.getHours() * 60 + now.getMinutes();
      const start = 7 * 60;
      const end = 10 * 60;

      if (date === todayStr) {
        return currentMinutes < start || currentMinutes > end;
      }

      const endOfToday = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate() + 1,
        0,
        0,
        0
      );
      return now >= endOfToday;
    },

   

    set_fields: async function (id) {
      this.param_id = id;
      // console.log("set_field", this.param_id);
      await this.details(id);

      if (this.item) {
        this.form_fields.quantity = this.item.quantity;
        this.form_fields.date = this.item.date;
      }
    },

    get_all_meals: async function () {
      try {
        const response = await axios.get("monthly-meal-rates");
        this.all_meals = response.data.data.data;
      } catch (error) {
        console.error("Error fetching users:", error);
        this.all_meals = [];
      }
    },

    // submitHandler: async function ($event) {
    //   this.set_only_latest_data(true);
    //   if (this.param_id) {
    //     let response = await this.update($event);
    //     // await this.get_all();
    //     if ([200, 201].includes(response.status)) {
    //       window.s_alert("Data successfully updated");
    //       this.$router.push({
    //         name: `All${this.setup.route_prefix}`,
    //       });
    //     }
    //   } else {
    //     let response = await this.create($event);
    //     // await this.get_all();
    //     if ([200, 201].includes(response.status)) {
    //       window.s_alert("Data Successfully Created");
    //       this.$router.push({
    //         name: `All${this.setup.route_prefix}`,
    //       });
    //     }
    //   }
    // },
    
  },

 

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>

<style scoped>
.tooltip-text {
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
}

button:disabled .tooltip-text {
  opacity: 1;
}
</style>
