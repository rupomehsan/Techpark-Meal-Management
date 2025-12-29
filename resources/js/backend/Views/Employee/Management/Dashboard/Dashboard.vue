<template>
  <div class="container-fluid">
    <!--Start Dashboard Content-->

    <div class="card mt-3">
      <div class="card-content">
        <div class="row row-group m-0">
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalIncomes }}
                <!-- <span class="float-right"><i class="fa fa-users"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                <h5> {{ all_meal_quantity }} </h5>
                <br>
                All Total Meals
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalExpenses }}
                <!-- <span class="float-right"><i class="fa fa-book"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                <h5> {{ month_meal_quantity }} </h5>
                <br>
                Monthly Total Meals
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalProducts }}
                <!-- <span class="float-right"><i class="fa fa-eye"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                 <h5>&#2547;  {{ total_meal_cost }} </h5>
                <br>
                Monthly Total Meal Cost
                <!-- Total Payable Current Month  -->
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalPurchaseOrders }}
                <!-- <span class="float-right"><i class="fa fa-envira"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              
              <p class="mb-0 text-white small-font">
                <h5>&#2547; {{ current_balance }} </h5>
                <!-- <h5>&#2547; {{ current_balance?.current_balance ?? 0 }} </h5> -->
                <br>
                Current Balance
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-content">
        <div class="row row-group m-0">
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalSuppliers }}
                <!-- <span class="float-right"><i class="fa fa-users"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                <h5>&#2547; {{ due_balance }}</h5>
                <br>
                  Current Month Due
                <!-- <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span> -->
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalSuppliers }}
                <!-- <span class="float-right"><i class="fa fa-users"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                <h5>&#2547; {{ current_payable_month }}</h5>
                <br>
                  Total Payable Current Month 
                <!-- <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span> -->
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalSuppliers }}
                <!-- <span class="float-right"><i class="fa fa-users"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                <h5>&#2547;  {{ total_payable }} </h5>
                <br>
                  All Total Payable
                <!-- <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span> -->
              </p>
            </div>
          </div>

          <!-- <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalWarehouses }}
                <span class="float-right"><i class="fa fa-book"></i></span>
              </h5>
              <div class="progress my-3" style="height: 3px">
                <div class="progress-bar" style="width: 55%"></div>
              </div>
              <p class="mb-0 text-white small-font">
                <h5> 0 </h5>
                <br>
                Cash in hand
                <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span>
              </p>
            </div>
          </div>


          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalCustomers }}
                <span class="float-right"><i class="fa fa-eye"></i></span>
              </h5>
              <div class="progress my-3" style="height: 3px">
                <div class="progress-bar" style="width: 55%"></div>
              </div>
              <p class="mb-0 text-white small-font">
                <h5> 0 </h5>
                <br>
                Current Month Total Meal
                <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span>
              </p>
            </div>
          </div>

          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalSalesOrders }}
                <span class="float-right"><i class="fa fa-envira"></i></span>
              </h5>
              <div class="progress my-3" style="height: 3px">
                <div class="progress-bar" style="width: 55%"></div>
              </div>
              <p class="mb-0 text-white small-font">
                <h5> 0 </h5>
                <br>
                Tomorrow Total meal
                <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span>
              </p>
            </div>
          </div> -->

        </div>
      </div>
    </div>

    <!-- <div class="card mt-3">
      <div class="card-content">
        <div class="row row-group m-0">
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.getTotalSuppliers }}
                <span class="float-right"><i class="fa fa-users"></i></span>
              </h5>
              <div class="progress my-3" style="height: 3px">
                <div class="progress-bar" style="width: 55%"></div>
              </div>
              <p class="mb-0 text-white small-font">
                <h5> 0 </h5>
                <br>
                  Current month meal rate
                <span class="float-right">
                  <i class="zmdi zmdi-long-arrow-up"></i>
                </span>
              </p>
            </div>
          </div>
         
        </div>
      </div>
    </div> -->

    <!--End Row-->
    <!-- <div class="card mt-3">
      <div class="card-header">My Meals</div>
      <div class="card-body">
        <div v-if="isLoadingMeals">Loading meals...</div>
        <div v-else-if="!all_meals || all_meals.length === 0">No meals found for this user.</div>
        <div v-else class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Type</th>
                <th>Note</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(m, idx) in all_meals" :key="m.id || idx">
                <td>{{ idx + 1 }}</td>
                <td>{{ m.date ?? m.created_at ?? 'N/A' }}</td>
                <td>{{ m.quantity ?? m.qty ?? 0 }}</td>
                <td>{{ m.meal_type ?? m.type ?? (m.meal && m.meal.name) ?? '-' }}</td>
                <td>{{ m.note ?? m.remarks ?? '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div> -->
    <!-- <div class="row">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header">Latest Purchase Orders</div>
          <table
            class="table table-hover text-center table-bordered table_responsive"
          >
            <thead>
              <tr>
                <th>#</th>
                <th>Order Id</th>
                <th>reference</th>
                <th>suppliyer</th>
                <th>total</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in data?.getLatesOrders" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ item.Order_id ?? "N/A" }}</td>
                <td>{{ item.reference ?? "N/A" }}</td>
                <td>{{ item.suppliyer?.name ?? "N/A" }}</td>
                <td>{{ item.total ?? "N/A" }}</td>
                <td>{{ item.date ?? "N/A" }}</td>
                <td>
                  <router-link
                    :to="{
                      name: 'EditPurchaseOrder',
                      params: { id: item.slug },
                    }"
                  >
                    <button class="btn btn-info">View</button>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header">Latest Sales Orders</div>
          <table
            class="table table-hover text-center table-bordered table_responsive"
          >
            <thead>
              <tr>
                <th>#</th>
                <th>Order Id</th>
                <th>reference</th>
                <th>costomer</th>
                <th>total</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in data?.getLatesSalesOrders"
                :key="index"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ item.order_id ?? "N/A" }}</td>
                <td>{{ item.reference ?? "N/A" }}</td>
                <td>{{ item.customer?.name.substring(0, 10) ?? "N/A" }}</td>
                <td>{{ item.total ?? "N/A" }}</td>
                <td>{{ item.date ?? "N/A" }}</td>
                <td>
                  <router-link
                    :to="{ name: 'EditSalesOrder', params: { id: item.slug } }"
                  >
                    <button class="btn btn-info">View</button>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div> -->
    <!--End Dashboard Content-->
    <!--start overlay-->

    <div class="overlay"></div>
    <!--end overlay-->
  </div>
  <!-- End container-fluid-->
</template>

<script>
export default {
  data: () => ({
    data: {},
    all_meal_quantity: 0,
    month_meal_quantity: 0,
    current_payable_month: 0,
    current_balance: 0,
    total_meal_cost: 0,
    total_payable: 0,
    due_balance: 0,
    current_balance: 0,
  
    response: {},
    isLoadingMeals: false,
    mealsError: null,
  }),
  
  created: async function () {
    console.log('employee created called..');
    // await this.get_all_dashboard_data();
    await this.employeeDashboardData();
  },

  mounted: async function() {
    // console.log('employee mounted called..');
    // await this.employeeDashboardData();
  },

  methods: {
    // get_all_dashboard_data: async function () {
    //   let response = await axios.get("get-all-dashboard-data");
    //   if (response.status == 200) {
    //     this.data = response.data.data;
    //   }
    // },

    employeeDashboardData: async function () {
      this.isLoadingMeals = true;
      this.mealsError = null;
      try {
        const response = await axios.get(`user-meals/dashboard-stats`);
        const res = response?.data?.data || {};
        // console.log("employee response:", res);
        this.all_meal_quantity      = Number(res.all_meal_quantity || 0);
        this.month_meal_quantity    = Number(res.month_meal_quantity || 0);
        this.current_payable_month  = Number(res.current_payable_month || 0);
        this.current_balance        = Number(res.current_balance || 0);
        // console.log('current balance', this.current_balance);
        this.total_meal_cost        = Number(res.total_meal_cost || 0);
        this.total_payable          = Number(res.total_payable || 0);
        this.due_balance            = Number(res.due_balance || 0);

      } catch (error) {
        console.error("Error fetching employee meals:", error);
        this.mealsError = error;
      } finally {
        this.isLoadingMeals = false;
      }
    },
    
  },

  computed: {
      mealsCount() {
        return Array.isArray(this.all_meals) ? this.all_meals.length : 0;
      },
      
      mealsTotalQuantity() {
        if (!Array.isArray(this.all_meals)) return 0;
        return this.all_meals.reduce((sum, m) => {
          const q = Number(m?.quantity ?? m?.qty ?? 0) || 0;
          return sum + q;
        }, 0);
      },
    },

};


</script>

<style>


</style>
