<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          
          <h5 class="text-capitalize">
            {{ setup.payment_history_page_title }}
          </h5>
          
          <!-- <div>
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `All${setup.route_prefix}` }"
            >
              {{ setup.all_page_title }}
            </router-link>
          </div> -->
        </div>

        <div class="row ml-5 align-items-end">
          <!-- <div class="row align-items-end"> -->
          <form @submit.prevent="filterDate">
            <div class="row align-items-end">
              <!-- Start Date -->
              <div class="col-md-3 mb-3">
                <label for="start_date" class="form-label">START DATE</label>
                <input
                  type="date"
                  name="start_date"
                  id="start_date"
                  class="form-control"
                  v-model="start_date"
                />
              </div>

              <!-- End Date -->
              <div class="col-md-3 mb-3">
                <label for="end_date" class="form-label">END DATE</label>
                <input
                  type="date"
                  name="end_date"
                  id="end_date"
                  class="form-control"
                  v-model="end_date"
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

        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-12">
               <table class="table table-hover text-center table-bordered">
                <thead>
                  <tr>
                    <th class="w-10">ID</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-if="item?.data && item.data.length"
                    v-for="(items, index) in item?.data"
                    :key="items.id"
                    :class="`table_rows table_row_${items.id}`"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ items.amount ?? "N/A " }}</td>
                    <td>{{ items.created_at ? items.created_at.slice(0, 10) : "N/A" }}</td>
                  </tr>

                  <tr v-else>
                    <td colspan="3" class="text-center text-muted">No data found</td>
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
                <h5>Total Amount : {{ total_amount }} TK.</h5>
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
    start_date: '',
    end_date: '',
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
      paymentHistory: "paymentHistory",
      // get_all: "get_all",
    }),
    
    get_data: async function (slug) {
      this.item = {};
      await this.paymentHistory(slug);
      this.user_id = this.$route.params.id;
    },


    paymentHistory: async function (id) {
      try {
        const response = await axios.get(`user-payments/employee-payment-history`);
        this.item = response.data;
        this.total_amount = this.item.data.reduce((sum, entry) => sum + Number(entry.amount), 0);
      } catch (error) {
        console.error('Submission error:', error.response?.data || error.message);
      }
    },

    

    filterDate: async function () {

      if (!this.start_date || !this.end_date) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Dates',
          text: 'Please select both start and end dates.'
        });
        return;
      }

      if (new Date(this.start_date) > new Date(this.end_date)) {
        return window.alert('Start Date cannot be after End Date.');
      }

      try {
        let response = await axios.get(`user-payments/employee-payment-history`, {
          params: {
            start_date: this.start_date,
            end_date: this.end_date,
            limit: 30
          }
        });

        this.item = response.data;
        this.total_amount = this.item.data.reduce((sum, entry) => sum + Number(entry.amount), 0);

      } catch (error) {
        console.error('Error fetching filtered data:', error);
      }
    },


    clearData: async function(){
      this.start_date = null;
      this.end_date = null;
      await this.paymentHistory(this.user_id);
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
