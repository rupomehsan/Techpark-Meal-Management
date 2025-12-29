<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{ setup.details_page_title }}
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
            <div class="col-lg-8">
              <table class="table quick_modal_table table-bordered">
                <tbody>
                
                  <tr>
                    <th>User Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.user?.name ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Quantity</th>
                    <th class="text-center">:</th>
                    <!-- <td v-html="item?.quantity ?? 'N/A'"></td> -->
                     <td :class="getQuantityClass(item.quantity)">
                      {{ item?.quantity ?? 'N/A' }}
                    </td>
                  </tr>
                  <tr>
                    <th>Date</th>
                    <th class="text-center">:</th>
                    <td>{{ item?.date ?? 'N/A' }}</td>
                  </tr>
                  <tr>
                    <th>Meal Status</th>
                    <th class="text-center">:</th>
                    <td :class="item?.meal_status === 'on' ? 'meal-on' : (item?.meal_status === 'off' ? 'meal-off' : '')">
                      {{ item?.meal_status ?? "N/A" }}
                    </td>
                  </tr>

                  <!-- <tr>
                    <th>Meal Rate</th>
                    <th class="text-center">:</th>
                    <td>{{ item?.meal_rate?.meal_rate ?? "N/A" }}</td>
                  </tr> -->
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <router-link
            class="btn btn-outline-warning btn-sm"
            :to="{
              name: `Edit${setup.route_prefix}`,
              params: { id: item.slug },
            }"
          >
            {{ setup.edit_page_title }}
          </router-link>

          <a
            href=""
            v-if="item.prev_slug"
            @click.prevent="get_data(item.prev_slug)"
            class="btn btn-secondary btn-sm ml-2"
          >
            <i class="fa fa-angle-left"></i>
            Previous {{ setup.route_prefix }} ({{ item.prev_count }})
          </a>

          <a
            href=""
            v-if="item.next_slug"
            @click.prevent="get_data(item.next_slug)"
            class="btn btn-secondary btn-sm ml-2"
          >
            Next {{ setup.route_prefix }} ({{ item.next_count }})
            <i class="fa fa-angle-right"></i>
          </a>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";

export default {
  data: () => ({
    setup,
  }),
  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    await this.get_data(id);
  },
  methods: {
    ...mapActions(store, {
      details: "details",
    }),
    get_data: async function (slug) {
      this.item = {};
      await this.details(slug);
    },

    getQuantityClass(quantity) {
      if (quantity == 0) {
        return 'quantity-zero';
      } else if (quantity > 0) {
        return 'quantity-positive';
      } else {
        return '';
      }
    },
  },
  computed: {
    ...mapWritableState(store, {
      item: "item",
    }),
  },
};
</script>

<style scoped>
tr th {
  text-align: left !important;
}

.meal-on {
  background-color: rgb(13, 156, 13);
}
.meal-off {
  background-color: rgb(219, 48, 48);
}

.quantity-zero {
  background-color: rgb(219, 48, 48); 
  color: white;
}

.quantity-positive {
  background-color: rgb(13, 156, 13); 
  color: white;
  }

</style>
