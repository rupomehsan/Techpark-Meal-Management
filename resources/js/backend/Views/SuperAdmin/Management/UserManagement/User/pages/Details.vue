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
                    <!-- {{ item }} -->
                    <th>Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.name ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.email ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Phone Number</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.phone_number ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Role</th>
                    <th class="text-center">:</th>
                    <th>{{ item.role?.name ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Department</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.department ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Batch Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.batch?.batch_name ?? "N/A" }}</th>
                  </tr>

                  <tr>
                    <th>Current Balence</th>
                    <th class="text-center">:</th>
                    <th>
                      &#2547; {{ userMonthBalance ?? currentBalance(item.id) }}
                    </th>
                  </tr>

                  <tr>
                    <th>image</th>
                    <th class="text-center">:</th>
                    <th>
                      <img
                        height="100px"
                        width="100px"
                        :src="item.image"
                        alt=""
                      />
                    </th>
                  </tr>

                  <tr>
                    <th>Present Address</th>
                    <th class="text-center">:</th>
                    <th v-html="item?.address ? item.address : 'N/A'"></th>
                  </tr>
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
    userMonthBalance: null,
    balanceList: [],
    balanceMap: {},
  }),

  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    // console.log('created',id);
    await this.get_data(id);
    // await this.userCurrentBlance(id);
  },

  mounted: async function () {
    let id = (this.param_id = this.$route.params.id);
    await this.userCurrentBlance(id);
  },

  methods: {
    ...mapActions(store, {
      details: "details",
    }),
    get_data: async function (slug) {
      // console.log("slug", slug);
      this.item = {};
      await this.details(slug);
      // console.log("item", this.item);
    },


    userCurrentBlance: async function (id, month) {
      try {
        if (!month) {
          const now = new Date();
          const yyyy = now.getFullYear();
          const mm = String(now.getMonth() + 1).padStart(2, "0");
          month = `${yyyy}-${mm}`;
        }
        const response = await axios.get(
          `users/user-current-month-blance/${month}`
        );
        const balances = response.data?.data || [];
        console.log("Fetched balances all:", balances);
        this.balanceList = balances;

        this.balanceMap = {};
        balances.forEach((b) => {
          const uid = b.user_id ?? b.id ?? (b.user && b.user.id) ?? null;
          const val = b.current_balance ?? b.balance ?? b.total ?? 0;
          if (uid !== null && uid !== undefined) this.balanceMap[uid] = val;
        });
      } catch (error) {
        console.error("Error fetching user balance:", error);
      }
    },

    
    currentBalance: function (id) {
      if (!id) return 0;
      if (this.balanceMap && this.balanceMap[id] !== undefined)
        return this.balanceMap[id];
      if (Array.isArray(this.balanceList)) {
        const u = this.balanceList.find(
          (x) => (x.user_id ?? x.id ?? (x.user && x.user.id)) === id
        );
        if (u) {
          const raw = u.current_balance ?? u.balance ?? u.total ?? 0;
          const num = Number(raw) || 0;
          console.log("found balance for user :", num);
          return Math.round(num);
        }
      }
      return 0;
    },

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
