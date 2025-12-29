<template>
  <div>
    <!-- <form @submit.prevent="submitHandler"> -->
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{ setup.monthly_meal_page_title }}
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
            <div class="col-lg-12">
               <table class="table table-hover text-center table-bordered">
                <thead>
                  <tr>
                    <th class="w-10">ID</th>
                    <!-- <th>User Name</th> -->
                    <th>Meal Quantity</th>
                    <th>Meal Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-if="mealData && mealData.length"
                    v-for="(items, index) in mealData"
                    :key="items.id"
                    :class="`table_rows table_row_${items.id}`"
                  >
                    <td>{{ index + 1 }}</td>
                    <!-- <td>{{ items.user?.name ?? "N/A " }}</td> -->
                    <td>{{ items.quantity ?? "N/A " }}</td>
                    <td>{{ items.date ? items.date.slice(0, 10) : "N/A" }}</td>
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
                <h5>Monthly Total Meals : {{ total_quantity }} </h5>
              </div>

            </div>
          </div>
        </div>
      </div>
    <!-- </form> -->
  </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";

export default {
  data: () => ({
    setup,
    mealData: '',
    total_quantity: 0,
  }),

  created: async function () {
    let date = this.$route.params.date;
    await this.get_data(date);
  },

  methods: {
    ...mapActions(store, {
      toDayMeal: "toDayMeal",
    }),
    
    // get_data: async function (date) {
    //   this.item = {};
    //   // await this.toDayMeal(date);
    // },

 

    get_data: async function (date) {
      try {
        const response = await axios.get(`user-meals/employee-monthly-meal/${date}`);
        this.mealData = response?.data?.data;
        // console.log('ok', this.mealData);
        
        this.total_quantity = this.mealData.reduce((sum, item) => sum + (item.quantity || 0), 0);
      } catch (error) {
        console.error("Failed to fetch today meal:", error);
      }
    }

  },

  computed: {
    ...mapWritableState(store, {
      item: "item",
    }),

  totalTodayQuantity() {
    return this.mealData.reduce((sum, item) => sum + (item.quantity || 0), 0);
  }

  
  },

};


</script>


<style scoped>

tr th {
  text-align: left !important;
}


</style>
