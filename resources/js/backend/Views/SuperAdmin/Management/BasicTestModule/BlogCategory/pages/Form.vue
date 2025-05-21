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
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `All${setup.route_prefix}` }"
            >
              {{ setup.all_page_title }}
            </router-link>
          </div>
        </div>
        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for=""> Title</label>
                <div class="mt-1 mb-3">
                  <input
                    class="form-control form-control-square mb-2"
                    type="text"
                    name="title"
                    id="title"
                    v-model="form_fields.title"
                  />
                </div>
              </div>
              <div class="form-group">
                <label for=""> Description</label>
                <div class="mt-1 mb-3">
                  <!-- <input
                    class="form-control form-control-square mb-2"
                    type="text"
                    name="description"
                    id="description"
                    v-model="form_fields.description"
                  /> -->

                  <textarea 
                    type="text" cols="10" rows="5"
                    class="form-control form-control-square mb-2"
                    name="description"
                    id="description"
                    v-model="form_fields.description" 
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i> Submit
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

export default {
  data: () => ({
    setup,
    param_id: null,
    form_fields: {
      title: "",
      tdescription: "",
    },
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
        this.form_fields.title = this.item.title;
        this.form_fields.description = this.item.description;
      }
    },

    submitHandler: async function ($event) {
      // this.set_only_latest_data(true);
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
