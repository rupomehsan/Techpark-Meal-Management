<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          
          <h5 class="text-capitalize">
            {{ setup.all_page_title }}
          </h5>
          
          <!-- <div>
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `Employee${setup.route_prefix}` }"
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
                <label for="start_date" class="form-label">START Month</label>
                <input
                  type="month"
                  name="start_month"
                  id="start_month"
                  class="form-control"
                  v-model="start_month"
                />
              </div>

              <!-- End month -->
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

        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-12">
               <table class="table table-hover text-center table-bordered">
                <thead>
                  <tr>
                    <th class="w-10">ID</th>
                    <th>Name</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Advance Amount</th>
                    <th>Due Amount</th>
                    <th>Month</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <tr
                    v-if="item?.data && item.data.length"
                    v-for="(items, index) in item?.data"
                    :key="items.id"
                    :class="`table_rows table_row_${items.id}`"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ items.user_name ?? "N/A " }}</td>
                    <td>{{ items.meal_cost ?? "N/A " }}</td>
                    <td>{{ items.paid_amount ?? "N/A" }}</td>
                    <td>{{ items.advance_amount ?? "N/A" }}</td>
                    <td>{{ items.due_amount ?? "N/A" }}</td>
                    <td>{{ items.month }}</td>
                  </tr> -->

                  <tr
                    v-if="item?.data && item.data.length"
                    v-for="(items, index) in item?.data"
                    :key="items.id"
                    :class="`table_rows table_row_${items.id}`"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ items.user_name ?? "N/A" }}</td>
                    <td :class="getCellClass(items.meal_cost)">{{ items.meal_cost ?? "N/A" }}</td>
                    <td :class="getCellClass(items.paid_amount, 'paid')">{{ items.paid_amount ?? "N/A" }}</td>
                    <td :class="getCellClass(items.advance_amount, 'advance')">{{ items.advance_amount ?? "N/A" }}</td>
                    <td :class="getCellClass(items.due_amount, 'due')">{{ items.due_amount ?? "N/A" }}</td>
                    <td>{{ items.month }}</td>
                  </tr>



                  <tr v-else>
                    <td colspan="4" class="text-center text-muted">No data found</td>
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
                <h5>Total Due Amount : {{ total_due }} TK.</h5>
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
    due_amount: 0,
    user_name: '',
    due_month: '',
    total_due: 0,
    month: '',
    paid_amount: 0,
    meal_cost: 0,
    advance_amount: 0,

    // onload: false,
    // filteredData: []
  }),

  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    await this.get_data(id);
    await this.monthlyDue();
  },

  methods: {
    ...mapActions(store, {
    //   paymentHistory: "paymentHistory",
      // get_all: "get_all",
    }),
    
    get_data: async function (slug) {
      this.item = {};
    //   await this.monthlyDue();
      this.user_id = this.$route.params.id;
    },


    monthlyDue: async function () {
      try {
        const response = await axios.get(`duelist/due-list`);
        // const res = response.data;
        this.item = response.data;
        // console.log('Due List Data:', this.item);
        this.total_due = this.item.data.reduce((sum, entry) => sum + Number(entry.due_amount), 0).toFixed(2);
        // console.log('Total Due Amount:', this.total_due);
      } catch (error) {
        console.error('Submission error:', error.response?.data || error.message);
      }
    },

    filterDate: async function () {

      if (!this.start_month || !this.end_month) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing months',
          text: 'Please select both start and end months.'
        });
        return;
      }

      if (new Date(this.start_month) > new Date(this.end_month)) {
        return window.alert('Start Date cannot be after End Date.');
      }

      try {
        let response = await axios.get(`duelist/due-list`, {
          params: {
            start_month: this.start_month,
            end_month: this.end_month,
            limit: 30
          }
        });

        this.item = response.data;
        this.total_due = this.item.data.reduce((sum, entry) => sum + Number(entry.due_amount), 0).toFixed(2);

      } catch (error) {
        console.error('Error fetching filtered data:', error);
      }
    },


    clearData: async function(){
      this.start_month = null;
      this.end_month = null;
      await this.monthlyDue();
    },

    getCellClass(value, type) {
      if (!value || value === 0) return '';

      switch(type) {
        case 'due':
          return value > 0 ? 'text-red-500' : 'text-green-500'; 
        case 'paid':
          return value > 0 ? 'text-blue-500' : ''; 
        case 'advance':
          return value > 0 ? 'text-yellow-500' : ''; 
        default:
          return ''; 
      }
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

.text-red-500 { background-color: red; }
.text-green-500 { background-color: green; }
.text-blue-500 { background-color: rgb(14, 35, 226); }
.text-yellow-500 { background-color: green; }

</style>
