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
              v-if="item.slug"
              class="btn btn-outline-info mr-2 btn-sm"
              :to="{
                name: `Details${setup.route_prefix}`,
                params: { id: item.slug },
              }"
            >
              {{ setup.details_page_title }}
            </router-link>
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

            <div class="col-md-12">
              <div class="col-md-6 pull-left">
                  <div class="mb-3">
                    <label for="user_id" class="form-label">User Id</label>
                    <select v-model="form_fields.user_id" name="user_id" class="form-control" id="user_id">
                      <option v-for="all_user in all_users" :key="all_user.id" :value="all_user.id">
                        {{ all_user.name }}
                      </option>
                    </select>
                  </div>

                <div class="mb-3">
                  <label for="month" class="form-label">Month</label>
                  <input type="month" v-model="form_fields.month" name="month" class="form-control" id="month" >
                </div>
              </div>
              <div class="col-md-6 pull-right">

                <div class="mb-3">
                  <label for="amount" class="form-label">Amount</label>
                  <input type="number" v-model="form_fields.amount" name="amount" class="form-control" id="amount" >
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            {{
              param_id
                ? `Update`
                : `Submit`
            }}
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
import form_fields from "../setup/form_fields";
import axios from "axios";

export default {
  data: () => ({
    setup,
    form_fields,
    param_id: null,
    form_fields: {
        user_id: '',
        month: '',
        amount: ''
    }
    
  }),

  created: async function () {
    await this.get_all_user();
    
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
    //   console.log('nahid', this.param_id);
      
      await this.details(id);
      if (this.item) {
            this.form_fields.user_id = this.item.user_id
            this.form_fields.month = this.item.month
            this.form_fields.amount = this.item.amount
        }
    },

    submitHandler: async function ($event) {
      this.set_only_latest_data(true);
      if (this.param_id) {
        let response = await this.update($event);
        await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data successfully updated");
          this.$router.push({
            name: `All${this.setup.route_prefix}`,
          });
        }
      } else {
        let response = await this.create($event);
        await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data Successfully Created");
          this.$router.push({
            name: `All${this.setup.route_prefix}`,
          });
        }
      }
    },

    get_all_user: async function(){
      let response = await axios.get('users');
      
      this.all_users = response.data.data.data;

    },

    // changeAction: function ($event) {
    //   if (event.target.name == "role_id") {
    //     let role_id = event.target.value;
    //     if (role_id == 2) {
    //       this.form_fields[9].is_visible = true;
    //       this.form_fields[10].is_visible = true;
    //       this.form_fields[11].is_visible = true;
    //     } else {
    //       this.form_fields[9].is_visible = false;
    //       this.form_fields[10].is_visible = false;
    //       this.form_fields[11].is_visible = false;
    //     }
    //   }
    // },
  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>
