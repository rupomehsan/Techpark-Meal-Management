<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <!-- <div class="card-header d-flex justify-content-between"> -->
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
          <h5 class="text-capitalize">
            {{ setup.users_report_page_title }}
          </h5>

          <div class="row ml-5 align-items-end">
            <!-- <div class="row align-items-end"> -->
            <form @submit.prevent="usersReport">
              <div class="row align-items-end">
                <!-- Start Date -->
                <div class="col-md-3 mb-3">
                  <label for="start_month" class="form-label">START Month</label>
                  <input
                    type="month"
                    name="start_month"
                    id="start_month"
                    class="form-control"
                    v-model="start_month"
                  />
                </div>
  
                <!-- End Date -->
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
  
                <!-- Filter Button -->
                <div class="col-md-3 mb-3 d-flex align-items-end">
                  <button type="submit" class="btn btn-outline-success w-100">
                    FILTER DATA
                  </button>
                </div>
  
                <!-- Clear Button -->
                <div class="col-md-3 mb-3 d-flex align-items-end">
                  <button @click="clearData" type="button" class="btn btn-outline-warning w-100">
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
                    <th>Name</th>
                    <th>Meal Quantity</th>
                    <th>Month / Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <!-- v-if="item?.data && item.data.length > 0" -->
                <tbody>
                  <tr
                    v-for="(items, index) in item?.data"
                    :key="index"
                    :class="`table_rows table_row_${items.id}`"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ items.user_name ?? "N/A " }}</td>
                    <td>{{ items.total_quantity ?? "N/A " }}</td>
                    <td>{{ items.month ?? "N/A " }}</td>
                    <td>
                        <ul>
                            <li>
                                <router-link
                                  :to="{
                                      name: `UserDetails${setup.route_prefix}`,
                                      params: {
                                          slug: items.user_slug,
                                          date: items.month
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
  }),

  created: async function () {
    await this.get_data();
  },

  methods: {
    ...mapActions(store, {
      userReport: "userReport",
    }),
    
    get_data: async function () {
      this.item = {};
      await this.usersReport();
    },

    usersReport: async function () {
      if (!this.start_month || !this.end_month) {
        try {
          const response = await axios.get(`/meal-report/users-report`);
          this.item = response.data;
        } catch (error) {
          console.error('Submission error:', error.response?.data?.message || error.message);
        }
      } else {
        if (new Date(this.start_month) > new Date(this.end_month)) {
          return window.alert('Start Date cannot be after End Date.');
        }

        try {
          const response = await axios.get(`/meal-report/users-report`, {
            params: {
              start_month: this.start_month,
              end_month: this.end_month,
            }
          });
          this.item = response.data;
        } catch (error) {
          console.error('Error fetching filtered data:', error);
        }
      }
    },

    clearData: async function(){
      this.start_month = null;
      this.end_month = null;
      await this.usersReport();
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
