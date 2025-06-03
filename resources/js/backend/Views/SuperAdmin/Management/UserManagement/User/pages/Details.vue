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
                    <th>name</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.name ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>email</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.email ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>phone number</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.phone_number ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>whatsapp</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.whatsapp ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>telegram</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.telegram ?? "N/A" }}</th>
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
                    <th>role</th>
                    <th class="text-center">:</th>
                    <th>{{ item.role?.name ?? "N/A" }}</th>
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
                    <th>present address</th>
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
