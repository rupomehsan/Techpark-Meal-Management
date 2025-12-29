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
          <router-link
            class="btn btn-outline-warning btn-sm"
            :to="{
              name: `DateWiseData${setup.route_prefix}`,
              params: { id: item.bajar_date },
            }"
          >
            {{ setup.all_page_title }}
          </router-link>
        </div>

        <div class="card-body">
          <!-- Date Row -->
          <div id="date_row" class="row mb-3 align-items-center">
            <div
              class="col-md-5 d-flex align-items-center justify-content-center"
            >
              <label class="form-label" for="bajar_date" style="width: 30%"
                >Bajar Date</label
              >
              <input
                type="date"
                name="bajar_date"
                v-model="form_fields.bajar_date"
                class="form-control"
                id="bajar_date"
              />
            </div>
          </div>

          <!-- Dynamic Row Rendering -->

          <div id="bajar_body">
            <div class="row mb-2" >
              <div class="col-3">
                <input
                  type="text"
                  name="title"
                  v-model="form_fields.title"
                  class="form-control"
                  placeholder="Title"
                />
              </div>

              <div class="col-2">
                <input
                  type="text"
                  name="quantity"
                  v-model="form_fields.quantity"
                  @input="calculateTotal"
                  class="form-control"
                  placeholder="Qty"
                />
              </div>

              <div class="col-2">
                <select
                  class="form-control"
                  name="unit"
                  v-model="form_fields.unit"
                >
                  <option value="">Select a unit</option>
                  <option value="pcs">pcs (pieces)</option>
                  <option value="kg">kg (kilograms)</option>
                  <option value="gr">gr (grams)</option>
                  <option value="ltr">ltr (liters)</option>
                  <option value="ml">ml (milliliters)</option>
                  <option value="box">box</option>
                  <option value="pack">pack</option>
                  <option value="dozen">dozen</option>
                </select>
              </div>

              <div class="col-2">
                <input
                  type="text"
                  name="price"
                  v-model="form_fields.price"
                  @input="calculateTotal"
                  class="form-control"
                  placeholder="Price"
                />
              </div>

              <div class="col-2">
                <input
                  :value="form_fields.total"
                  name="total"
                  class="form-control"
                  placeholder="Total"
                />
              </div>
            </div>
          </div>

        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            Update
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
    bajarDate: "",

    form_fields: {
      title: "",
      quantity: "",
      unit: "",
      price: "",
      total: "",
      bajar_date: "",
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
      update: "update",
      details: "details",
      //   get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),

    set_fields: async function (id) {
      this.param_id = id;
      await this.details(id);
      if (this.item) {
        this.form_fields.title = this.item.title;
        this.form_fields.quantity = this.item.quantity;
        this.form_fields.unit = this.item.unit;
        this.form_fields.price = this.item.price;
        this.form_fields.total = this.item.total;
        this.form_fields.bajar_date = this.item.bajar_date;
      }
    },

    submitHandler: async function ($event) {
      this.set_only_latest_data(true);
      if (this.param_id) {
        let response = await this.update($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data successfully updated");
          this.$router.push({
            name: `DateWiseData${this.setup.route_prefix}`,
            params: {
              id: this.item.bajar_date,
            },
          });
        }
      }
    },
  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),

    calculateTotal() {
        const qty = parseFloat(this.form_fields.quantity) || 0;
        const price = parseFloat(this.form_fields.price) || 0;
        this.form_fields.total = (qty * price);
    },

  },
};
</script>

<style scoped></style>
