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
                    <label for="month" class="form-label">Month</label>
                     <input type="month" v-model="form_fields.month" name="month" class="form-control" id="month" >
                  </div>

                <div class="mb-3">
                  <label for="mealRate" class="form-label">Meal Rate</label>
                  <input type="number" v-model="form_fields.meal_rate" name="meal_rate" class="form-control" id="mealRate" >
                </div>
              </div>

              <div class="col-md-6 pull-right">
                <div class="mb-3">
                  <label for="is_visible" class="form-label">IS Visible</label>
                  <select name="is_visible" v-model="form_fields.is_visible" class="form-control" id="is_visible">
                    <option>Select a Option</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="month_start_date" class="form-label">Month Start Date</label>
                  <input type="date" v-model="form_fields.month_start_date"  name="month_start_date" class="form-control" id="month_start_date" >
                </div>
                
                <div class="mb-3">
                  <label for="month_end_date" class="form-label">Month End Date</label>
                  <input type="date" v-model="form_fields.month_end_date" name="month_end_date" class="form-control" id="month_end_date" >
                </div>
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

    form_fields: {
      month: '',
      meal_rate: '',
      is_visible: '',
      month_start_date: '',
      month_end_date: ''
    }
  }),

  created: async function () {
    
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
          this.form_fields.month = this.item.month;
          this.form_fields.meal_rate = this.item.meal_rate;
          this.form_fields.is_visible = this.item.is_visible;
          this.form_fields.month_start_date = this.item.month_start_date;
          this.form_fields.month_end_date = this.item.month_end_date;
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
