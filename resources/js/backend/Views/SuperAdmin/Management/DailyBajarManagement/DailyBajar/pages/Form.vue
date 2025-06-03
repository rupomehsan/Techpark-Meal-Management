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
        <!-- <div class="card-body card_body_fixed_height">
          <div class="row">

            <div class="col-md-12">
              <div class="col-md-4 pull-left">
                  <div class="mb-3">
                    <label for="bajar_date" class="form-label">Bajar Date</label>
                    <input type="date" name="bajar_date"  v-model="form_fields.bajar_date" class="form-control" id="bajar_date" >
                  </div>
              </div>

              <div class="col-md-4 pull-right">
                +
              </div>

            </div>
            <div class="col-md-12">
                
              <div class="col-md-3 pull-left">
                  <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title"  v-model="form_fields.title" class="form-control" id="title" >
                  </div>

                
                
                <div class="mb-3">
                  <label for="unit" class="form-label">Unit</label>
                  <select id="unit" name="unit" v-model="form_fields.unit" class="form-control">
                    <option value="">-- Select a Unit --</option>
                    <option value="pcs">pcs (pieces)</option>
                    <option value="kg">kg (kilograms)</option>
                    <option value="g">gr (grams)</option>
                    <option value="ltr">ltr (liters)</option>
                    <option value="ml">ml (milliliters)</option>
                    <option value="box">box</option>
                    <option value="pack">pack</option>
                    <option value="dozen">dozen</option>
                </select>
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input type="number" name="quantity" v-model="form_fields.quantity" class="form-control" id="quantity" >
                </div>
              </div>

              <div class="col-md-3 pull-right">
                <div class="mb-3">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" name="price"  v-model="form_fields.price" class="form-control" id="price" >
                </div>

                <div class="mb-3">
                  <label for="total" class="form-label">Total</label>
                  <input type="number" name="total"  v-model="form_fields.total" class="form-control" id="total" >
                </div>

              </div>
            </div>

              
          </div>
        </div> -->


          <div class="card-body">
            <div id="date_row" class="row mb-3 align-items-center">
                <div class="col-md-6  d-flex align-items-center justify-content-center">
                  <label class="form-label" for="bajar_date" style="width: 30%">Bajar Date</label>
                  <input type="date" name="bajar_date" class="form-control" id="bajar_date" placeholder="Bajar Date">
                </div>

                <div class="col-md-6 text-end">
                  <div class="badge  bg-label-success rounded" @click="add_row">
                      <i class="fa-solid fa-plus" style="font-size: 20px"></i>
                  </div>
                </div>
            </div>

            <div id="bajar_body" data-select2-id="bajar_body">     
              <div class="row mb-2 row_no_1" data-id="0">

                <div class="col-3" data-field="title">
                    <input type="text" name="bajar[0][title]" class="form-control title" placeholder="Title">
                </div>

                <div class="col-2" data-field="quantity">
                    <input type="number" name="bajar[0][quantity]" class="quantity form-control" placeholder="qty">
                </div>

                <div class="col-2" data-field="unit" data-select2-id="5">
                  <select class="form-control"  name="bajar[0][unit]" data-select2-id="1" tabindex="-1">
                      <option value="" selected="" data-select2-id="3">Select a unit</option>
                      <!-- <option value="kg" data-select2-id="16">KG</option>
                      <option value="pcs" data-select2-id="17">PCS</option>
                      <option value="gm" data-select2-id="18">GM</option> -->
                      <!-- <option value="">-- Select a Unit --</option> -->
                      <option value="pcs">pcs (pieces)</option>
                      <option value="kg">kg (kilograms)</option>
                      <option value="g">gr (grams)</option>
                      <option value="ltr">ltr (liters)</option>
                      <option value="ml">ml (milliliters)</option>
                      <option value="box">box</option>
                      <option value="pack">pack</option>
                      <option value="dozen">dozen</option>
                  </select>
                </div>

                <div class="col-2" data-field="price">
                    <input type="number" name="bajar[0][price]" class="form-control" placeholder="Price">
                </div>

                <div class="col-2" data-field="total">
                    <input type="text" name="bajar[0][total]" class="total form-control" placeholder="Total" readonly="">
                </div>

                <div class="col-1 d-flex align-item-center justify-content-evenly gap-2">
                    <div class="badge  bg-label-danger rounded" @click="delete_row('row_no_1')">
                        <i class="fa-solid fa-trash mt-1"  style="font-size: 20px; color:red"></i>
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
    all_users: [],

    form_fields: {
      title: '',
      quantity: '',
      unit: '',
      price: '',
      total: '',
      bajar_date: ''
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

    get_all_user: async function(){
      let response = await axios.get('users');
      this.all_users = response.data.data.data;

    },

    changeAction: function ($event) {
      if (event.target.name == "role_id") {
        let role_id = event.target.value;
        if (role_id == 2) {
          this.form_fields[9].is_visible = true;
          this.form_fields[10].is_visible = true;
          this.form_fields[11].is_visible = true;
        } else {
          this.form_fields[9].is_visible = false;
          this.form_fields[10].is_visible = false;
          this.form_fields[11].is_visible = false;
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


<style>
  #bajar_body {
    /* display: none; */
  }
</style>