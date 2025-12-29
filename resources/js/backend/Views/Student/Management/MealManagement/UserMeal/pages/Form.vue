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
              <div
                v-for="(item, index) in form_fields"
                :key="index"
                class="row mb-2"
              >
                <div class="col-md-4 pull-left">
                  <div class="mb-2">
                    <label class="form-label">Quantity</label>
                    <input
                      type="number"
                      v-model="item.quantity"
                      name="quantity"
                      class="form-control"
                      min="1"
                    />
                  </div>
                </div>

                <div class="col-md-4 pull-left">
                  <div class="mb-2">
                    <label class="form-label">Date</label>
                    <input
                      type="date"
                      name="date"
                      v-model="item.date"
                      class="form-control"
                    />
                  </div>
                </div>

                <div class="col-md-4 pull-left" v-if="!param_id">
                  <div class="mb-2 mt-4">
                    <input
                      type="checkbox"
                      v-model="item.checked"
                      class=""
                      @change="storeEmployeeMeal(item, index)"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <!-- <button
            v-if="param_id"
            type="button"
            class="btn btn-light btn-square px-5"
            @click="employeeMealUpdate(item)"
            :disabled="isMealUpdateDisabled(item?.date)"
          >
            <i class="icon-lock"></i>
            Update
          </button> -->

          <button
            v-if="param_id"
            type="button"
            class="btn btn-light btn-square px-5"
            @click="employeeMealUpdate"
            :disabled="isMealUpdateDisabled(form_fields[0]?.date)"
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

    form_fields: [],
  }),

  created: async function () {
    // this.get_all_meals();
    let id = (this.param_id = this.$route.params.id);
    // console.log("route id", id);
    if (id) {
      this.set_fields(id);
    }

    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth();

    this.form_fields = this.getDatesOfMonth(year, month);
    this.form_fields = this.form_fields.map((date) => ({
      quantity: 1,
      date: date,
      checked: false,
    }));
  },

  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),

    // storeEmployeeMeal: async function (item) {
    //   const now = new Date();
    //   const hour = now.getHours();
    //   const minute = now.getMinutes();

    //   const current = hour * 60 + minute;
    //   const start = 7 * 60;
    //   const end = 9 * 60;

    //   const today = now.toISOString().substr(0, 10);

    //   // Prevent creating meals for past dates
    //   if (item.date) {
    //     if (item.date < today) {
    //       item.checked = false;
    //       window.s_alert("You cannot create meal for past dates");
    //       return;
    //     }
    //   }

    //   if (item.date === today) {
    //     if (current < start || current > end) {
    //       item.checked = false;
    //       window.s_alert("Your time is over now!");
    //       // window.s_alert("আজকের meal দিতে পারবেন সকাল 7টা থেকে 10টার মধ্যে!");
    //       return;
    //     }
    //   }

    //   if (item.checked) {
    //     if (!item.date) {
    //       console.warn("Date missing. Cannot submit to API.");
    //       item.checked = false;
    //       return;
    //     }

    //     // ✔ Push item (if needed)
    //     this.form_fields.push({
    //       quantity: item.quantity || 1,
    //       date: item.date || "",
    //       checked: item.checked || false,
    //     });

    //     try {
    //       let res = await axios.post(
    //         `user-meals/student-store-meal/${item.date}`,
    //         {
    //           quantity: item.quantity,
    //           date: item.date,
    //           checked: item.checked,
    //         }
    //       );

    //       window.s_alert("Data successfully created");

    //       this.$router.push({
    //         name: `All${this.setup.route_prefix}`,
    //       });

    //       console.log("employee meal response", res.data);
    //     } catch (error) {
    //       console.error("API error:", error.response?.data || error);
    //     }
    //   }
    // },

    storeEmployeeMeal: async function (item) {
      const now = new Date();
      const hour = now.getHours();
      const minute = now.getMinutes();

      const current = hour * 60 + minute;
      const start = 7 * 60;
      const end = 9 * 60;

      const today = now.toISOString().substr(0, 10);

      // Prevent creating meals for past dates
      if (item.date) {
        if (item.date < today) {
          item.checked = false;
          window.s_alert("You cannot create meal for past dates");
          return;
        }
      }

      if (item.date === today) {
        if (current < start || current > end) {
          item.checked = false;
          window.s_alert("Your time is over now!");
          // window.s_alert("আজকের meal দিতে পারবেন সকাল 7টা থেকে 10টার মধ্যে!");
          return;
        }
      }

      if (item.checked) {
        if (!item.date) {
          console.warn("Date missing. Cannot submit to API.");
          item.checked = false;
          return;
        }
        try {
          // Call API first to verify balance and create/update meal
          const res = await axios.post(
            `user-meals/student-store-meal/${item.date}`,
            {
              quantity: item.quantity,
              date: item.date,
              checked: item.checked,
            }
          );

          // On success, optionally update local form state and navigate
          window.s_alert(res.data?.message || "Data successfully created");

          // If API returned remaining balance, show it
          if (res.data?.data?.remaining_balance !== undefined) {
            const rem = res.data.data.remaining_balance;
            window.s_alert(`Remaining balance: ${rem}`);
          }

          // push local entry only after success (prevents duplicates on failure)
          this.form_fields.push({
            quantity: item.quantity || 1,
            date: item.date || "",
            checked: item.checked || false,
          });

          this.$router.push({ name: `All${this.setup.route_prefix}` });

          console.log("student meal response", res.data);
        } catch (error) {
          const resp = error.response;
          // insufficient balance
          if (resp && resp.status === 402) {
            const msg = resp.data?.message || "Insufficient balance. Please add money to your account to take a meal.";
            window.s_alert(msg);
            item.checked = false;
            return;
          }

          console.error("API error:", resp?.data || error);
          const fallback = resp?.data?.message || "An error occurred. Please try again.";
          window.s_alert(fallback);
          item.checked = false;
        }
      }
    },



    getDatesOfMonth() {
      const today = new Date();
      const year = today.getFullYear();
      const month = today.getMonth();

      let date = new Date(year, month);
      let dates = [];

      while (date.getMonth() === month) {
        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, "0");
        const dd = String(date.getDate()).padStart(2, "0");

        dates.push(`${yyyy}-${mm}-${dd}`);
        date.setDate(date.getDate() + 1);
      }

      return dates;
    },

    set_fields: async function (id) {
      this.param_id = id;
      // console.log("set_field", this.param_id);
      await this.details(id);

      if (this.item) {
        // this.form_fields.quantity = this.item.quantity;
        // this.form_fields.date = this.item.date;

        this.form_fields = [
          {
            id: this.item.id,
            quantity: this.item.quantity,
            date: this.item.date,
            checked: this.item.checked || false,
          },
        ];
      }
    },

    // get_all_meals: async function () {
    //   try {
    //     const response = await axios.get("monthly-meal-rates");
    //     this.all_meals = response.data.data.data;
    //   } catch (error) {
    //     console.error("Error fetching users:", error);
    //     this.all_meals = [];
    //   }
    // },

    submitHandler: async function ($event) {
      this.set_only_latest_data(true);
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
