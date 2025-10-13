<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">

        <!-- <div class="card-header d-flex justify-content-between">  
          <h5 class="text-capitalize">
            {{ setup.monthly_report_page_title }}
          </h5>

          <div class="row ml-5 align-items-end">
            <form @submit.prevent="filterDate">
              <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                  <label for="start_date" class="form-label">START Month</label>
                  <input
                    type="month"
                    name="start_month"
                    id="start_month"
                    class="form-control"
                    v-model="start_month"
                  />
                </div>
  
                <div class="col-md-3 mb-3">
                  <label for="end_month" class="form-label">END Month</label>
                  <input
                    type="month"
                    name="end_month"
                    id="end_month"
                    class="form-control"
                    v-model="end_month"
                  />
                </div>
  
                <div class="col-md-3 mb-3 d-flex align-items-end">
                  <button type="submit" class="btn btn-outline-success w-100">
                    FILTER DATA
                  </button>
                </div>
  
                <div class="col-md-3 mb-3 d-flex align-items-end">
                  <button @click="clearData" type="button" class="btn btn-outline-warning w-100">
                    CLEAR DATA
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div> -->

        <div class="card-header">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="text-capitalize mb-3 mb-md-0">
              {{ setup.monthly_report_page_title }}
            </h5>

            <form @submit.prevent="filterDate" class="w-100">
              <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-3">
                  <label for="start_month" class="form-label">START Month</label>
                  <input
                    type="month"
                    id="start_month"
                    class="form-control"
                    v-model="start_month"
                  />
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                  <label for="end_month" class="form-label">END Month</label>
                  <input
                    type="month"
                    id="end_month"
                    class="form-control"
                    v-model="end_month"
                  />
                </div>

                <div class="col-12 col-sm-6 col-md-3 pt-2 pt-sm-0 d-flex align-items-end">
                  <button type="submit" class="btn btn-outline-success w-100 mt-2 mt-sm-0">
                    FILTER DATA
                  </button>
                </div>

                <div class="col-12 col-sm-6 col-md-3 pt-2 pt-sm-0 d-flex align-items-end">
                  <button
                    @click="clearData"
                    type="button"
                    class="btn btn-outline-warning w-100 mt-2 mt-sm-0"
                  >
                    CLEAR DATA
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-12">

              <table class="table table-hover text-center table-bordered">
                <thead>
                  <tr>
                    <th class="w-10">ID</th>
                    <th>Meal Quantity</th>
                    <th>Meal Rate</th>
                    <th>Total Bajar</th>
                    <th>Cook Sallary</th>
                    <th>Total Expence</th>
                    <th>Month</th>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(items, index) in item?.data"
                    :key="items.id"
                    :class="`table_rows table_row_${items.id}`"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ items.meal_qty ?? "N/A " }}</td>
                    <td>{{ items.meal_rate ?? "N/A " }}</td>
                    <td>{{ items.total_cost ?? "N/A " }}</td>
                    <td>{{ items.cook_salary ?? "N/A " }}</td>
                    <td>{{ items.grand_total ?? "N/A " }}</td>
                    <td>{{ items.month ?? "N/A" }}</td>
                    <td>
                      <ul>
                            <li>
                                <router-link
                                  :to="{
                                      name: `MonthlyDetails${setup.route_prefix}`,
                                      params: {
                                          month: items.month
                                      },
                                  }"
                                  
                                class="btn btn-outline-warning btn-sm ml-2" 
                                >
                                <i class="fa fa-eye text-warning"></i>
                                Details
                                </router-link>
                            </li>
                        </ul>
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
        </div>
        <div class="card-footer">
          
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <!-- <h5>Total Amount : {{ total_amount }} TK.</h5> -->
              </div>

            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import axios from "axios";

export default {
  data: () => ({
    setup,
    user_id: '',
    start_month: '',
    end_month: '',
    total_amount: 0,
    // onload: false,
    // filteredData: []
  }),

  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    await this.get_data(id);
  },

  methods: {
    ...mapActions(store, {
      monthlyReport: "monthlyReport",
      // get_all: "get_all",
    }),
    
    get_data: async function () {
      // this.item = {};
      this.item = null;
      await this.monthlyReport();
    //   this.user_id = this.$route.params.id;
    },


    monthlyReport: async function () {
      try {
        const response = await axios.get(`/meal-report/monthly-report`);
        this.item = response.data.data;
        // log('daily report', this.item);
      } catch (error) {
        console.error('Submission error:', error.response?.data.data || error.message);
      }
    },

    filterDate: async function () {

      if (!this.start_month || !this.end_month) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Dates',
          text: 'Please select both start and end dates.'
        });
        return;
      }

      if (new Date(this.start_month) > new Date(this.end_month)) {
        return window.alert('Start month cannot be after End month.');
      }

          try {
            const response = await axios.get(`/meal-report/monthly-report`, {
            params: {
                start_month: this.start_month,
                end_month: this.end_month,
            }
            });

            this.item = response.data.data;
            // this.total_amount = this.item.data.reduce((sum, entry) => sum + Number(entry.amount), 0);

          } catch (error) {
            console.error('Error fetching filtered data:', error);
          }
    },


    clearData: async function(){
      this.start_month = null;
      this.end_month = null;
      await this.monthlyReport();
    }

  },


  computed: {
    ...mapWritableState(store, {
      item: "item",
    }),

  
  },
};
</script>

<style>

tr th {
  text-align: left !important;
}

</style>
