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
              <div class="col-md-6 pull-left">
                <!-- <input type="text" name="user_id" :value="user_id" /> -->
              
                 <div class="mb-3">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input
                    type="number"
                    name="quantity"
                    v-model="form_fields.quantity"
                    class="form-control"
                    id="quantity"
                    min="1"
                  />
                  <div v-if="meal_quantity > 0">
                    <strong :class="meal_qty">
                      Your Meal Already Exist On Date:
                      {{ form_fields.date }} with Quantity: {{ meal_quantity }}
                      <span :style="{ color: 'white' }">
                        ( If you want, you can just update the quantity. )
                      </span>
                    </strong>
                  </div>
                </div>

              </div>

              <div class="col-md-6 pull-right">
                 <div class="mb-3">
                  <label for="date" class="form-label">Date</label>
                  <input
                    type="date"
                    name="date"
                    v-model="form_fields.date"
                    class="form-control"
                    @change="selectDate"
                    id="date"
                  />

                  <div v-if="showOffMealMessage">
                    <strong class="text-danger">
                      To Day Meal is Off On by Date : {{ offMealDate.off_date }}
                    </strong>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <!-- <button
            type="submit"
            class="btn btn-light btn-square px-5"
          >
            <i class="icon-lock"></i>
            {{ param_id ? `Update` : `Submit` }}
          </button> -->

          <button
            v-if="offMealDate && offMealDate.off_date"
            type="button"
            class="btn btn-light btn-square px-5"
            @mouseenter="showOffMealAlert"
            :style="{
              cursor: 'not-allowed',
              opacity: 0.6,
              pointerEvents: 'auto',
            }"
          >
            <i class="icon-lock"></i>
            {{ param_id ? "Update" : "Submit" }}
          </button>

          <button v-else type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            {{ param_id ? `Update` : `Submit` }}
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
    form_fields,
    param_id: null,
    all_user: [],
    user_id: null,
    filteredUsers: [],
    all_meals: [],
    meal_quantity: "",
    offMealDate: "",
    error: "",
    off_date: null,
    form_fields: {
      // user_type: "",
      // user_id: "",
      quantity: "",
      date: "",
    },

  }),

  created: async function () {
    // this.get_all_users();
    this.get_all_meals();
    let id = (this.param_id = this.$route.params.id);
    // console.log("route id", id);
    if (id) {
      this.set_fields(id);
    }
  },

  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),

    selectDate: async function () {
      if(!this.form_fields.date) {
          alert("Please select date.");
          return;
      }

      // check user meal history
      try {
        let res = await axios.get(
          `user-meals/users-meal-history`,
          {
            params: { 
              date: this.form_fields.date 
            }
          }
        );
        console.log("meal history response", res);
        this.meal_quantity = res.data.data;
        this.meal_date = res.data.data.date;
        console.log("meal history", this.meal_quantity, this.meal_date);
      } catch (error) {
        console.error("Error fetching meal history:", error);
      }

      // check off meal
      try {
        let response = await axios.get(
          `user-meals/off-meals/${this.form_fields.date}`
        );

        if (response.data.status == "not_found") {
          this.offMealDate = null;
          // this.showOffMealMessage = false;
        } else if (response.data.status == "success") {
          this.offMealDate = response.data.data;
          // this.showOffMealMessage = true;
        } else {
          console.log("response", response);
          this.offMealDate = null;
          // this.showOffMealMessage = false;
        }
      } catch (error) {
        console.error("Error fetching off meal info:", error);
        this.offMealDate = null;
        this.showOffMealMessage = false;
      }
    },

    showOffMealAlert() {
      alert("Meal entry not allowed. To Day Meal is OFF for this date.");
    },

    set_fields: async function (id) {
      this.param_id = id;
      // console.log("set_field", this.param_id);
      await this.details(id);
    
      if (this.item) {
        // set the user type first, load users for that role, then set user_id
        this.form_fields.user_type = this.item.user?.role_id || "";
        
        // ensure the user list is loaded for the role before assigning user_id
        await this.ChangeUserName(this.form_fields.user_type);

        const userId = this.item.user_id;
        // only set the user_id if it exists in the fetched list
        if (
          userId &&
          this.all_user &&
          this.all_user.find((u) => u.id == userId)
        ) {
          this.form_fields.user_id = userId;
        } else {
          this.form_fields.user_id = "";
        }

        this.form_fields.quantity = this.item.quantity;
        this.form_fields.date = this.item.date;
      }
    },

    ChangeUserName: async function () {
      try {
        const name = this.form_fields.user_type;
        const response = await axios.get(`/user-meals/role-by-username/${name}`);
        this.all_user = response.data.data;
        // console.log("All Users:", this.all_user);

        if (this.form_fields.user_id) {
          let exists = this.all_user.find(
            (u) => u.id == this.form_fields.user_id
          );

          if (!exists) {
            this.form_fields.user_id = "";
          }
        }
      } catch (error) {
        console.error("Error fetching role:", error);
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

    meal_qty() {
      return this.meal_quantity > 0 ? "text-danger" : "";
    },

    showOffMealMessage() {
      return (
        this.offMealDate && this.offMealDate.off_date === this.form_fields.date
      );
    },
  },
};
</script>

<style scoped></style>
