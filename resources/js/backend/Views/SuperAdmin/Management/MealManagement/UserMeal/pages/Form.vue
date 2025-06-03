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
                <div class="mb-3">
                  <label for="mealRate" class="form-label">User name</label>
                  <select name="user_id" v-model="form_fields.user_id" id="user_id" class="form-control">
                    <option value="">Select a User Name</option>
                    <option 
                        v-for="user in all_user" 
                        :key="user.id" 
                        :value="user.id">
                        {{ user.name }} 
                      </option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="date" class="form-label">Date</label>
                  <input type="date" name="date" v-model="form_fields.date" class="form-control" id="date">
                </div>
              </div>

              <div class="col-md-6 pull-right">
                <div class="mb-3">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input type="number" name="quantity" v-model="form_fields.quantity" class="form-control" id="quantity">
                </div>

                <!-- <div class="mb-3">
                  <label for="meal_rate_id" class="form-label">Meal Rate ID</label>
                  <select name="meal_rate_id" id="meal_rate_id" class="form-control">
                    <option>Select a Meal Rate</option>
                    <option 
                        v-for="meal in all_meals" 
                        :key="meal.id" 
                        :value="meal.id">
                        &#x09F3; {{ meal.meal_rate }} 
                      </option>
                  </select>
                </div> -->
              </div>
            </div>

          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            {{
              param_id
                ? `Update`
                : `Submit`
            }}
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
    setup,
    form_fields,
    param_id: null,
    all_user: [],
    all_meals: [],
    form_fields:{
      user_id: '',
      quantity: '',
      date: ''
    }
  }),

  created: async function () {
    this.get_all_users();
    this.get_all_meals();

    let id = (this.param_id = this.$route.params.id);
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

    set_fields: async function (id) {
      this.param_id = id;
      await this.details(id);
      if (this.item) {
          this.form_fields.user_id = this.item.user_id;
          this.form_fields.quantity = this.item.quantity;
          this.form_fields.date = this.item.date;
        }
    },

    get_all_users: async function () {
      try {
        const response = await axios.get('users');
        this.all_user = response.data.data.data;
      } catch (error) {
        console.error('Error fetching users:', error);
        this.all_user = [];
      }
    },

    get_all_meals: async function () {
      try {
        const response = await axios.get('monthly-meal-rates');
        this.all_meals = response.data.data.data;
        console.log("ok", this.all_meals);
        
      } catch (error) {
        console.error('Error fetching users:', error);
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
  },
};
</script>
