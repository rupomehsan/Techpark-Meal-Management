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
       
        
        <div class="card-body">
          <!-- Date Row -->
          <div id="date_row" class="row mb-3 align-items-center">
            <div class="col-md-6 d-flex align-items-center justify-content-center">
              <label class="form-label" for="bajar_date" style="width: 30%">Bajar Date</label>
              <input type="date" name="bajar_date" v-model="bajarDate" class="form-control" id="bajar_date"/>
            </div>

            <div class="col-md-2 offset-4">
              <div class="add_row pull-right" @click="add_row">
                <i class="fa-solid fa-plus icons"></i>
              </div>
            </div>
          </div>

          <!-- Dynamic Row Rendering -->
          <div id="bajar_body">
            <div class="row mb-2" v-for="(row, index) in rows" :key="row.id">
              <div class="col-3">
                <input type="text" name="title[]" v-model="row.title" class="form-control" :name="`[${index}][title]`" placeholder="Title" />
              </div>

              <div class="col-2">
                <input type="text" name="quantity[]" v-model="row.quantity" @input="calculateTotal(index)" class="form-control" :name="`[${index}][quantity]`" placeholder="Qty" />
              </div>

              <div class="col-2">
                <select class="form-control" name="unit[]"  v-model="row.unit" :name="`[${index}][unit]`">
                  <option value="">Select a unit</option>
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
              
              <div class="col-2">
                <input type="text" name="price[]" v-model="row.price" @input="calculateTotal(index)" class="form-control" :name="`[${index}][price]`" placeholder="Price" />
              </div>

              <div class="col-2">
                <input :value="row.total" name="total[]" class="form-control" :name="`[${index}][total]`" placeholder="Total" />
              </div>

              <div class="col-1 d-flex align-items-center justify-content-evenly gap-2">
                <div class="delete_row" @click="delete_row(index)">
                  <i class="fa-solid fa-trash mt-1 "></i>
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
// import axios from "axios"; 

export default {
  data: () => ({
    setup,
    form_fields,
    param_id: null,
    all_users: [],

    bajarDate: '',
      rows: [
        { id: 1, title: '', quantity: '', unit: '', price: '', total: '' }
      ],
      nextId: 2, // for unique ID tracking

    form_fields: {
      title: '',
      quantity: '',
      unit: '',
      price: '',
      total: '',
      bajar_date: ''
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
          this.form_fields.quantity = this.item.quantity;
          this.form_fields.unit = this.item.unit;
          this.form_fields.price = this.item.price;
          this.form_fields.total = this.item.total;
          this.form_fields.bajar_date = this.item.bajar_date;
        }
    },
    

    add_row() {
      this.rows.push({
        id: this.nextId++,
        title: '',
        quantity: '',
        unit: '',
        price: '',
        total: ''
      });
    },

     delete_row(index) {
      this.rows.splice(index, 1);
    },
    calculateTotal(index) {
      const row = this.rows[index];
      const qty = parseFloat(row.quantity) || 0;
      const price = parseFloat(row.price) || 0;
      row.total = qty * price;
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
            name: `Expense${this.setup.route_prefix}`,
          });
        }
      }
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


<style scoped>
  #bajar_body {
    /* display: none; */
  }


  .icons[data-v-6e7cf8ed] {
     font-size: 21px;
    background-color: #006e802b;
    /* padding: 7px; */
    padding: 8px 11px;
    border-radius: 3px;
    box-shadow: 2px 3px 5px #a7888847;
    margin-right: 28px;
}
  .icons i{
    margin-left: 30px;
  }
  .add_row{
    cursor: pointer;
  }

  .delete_row{
    font-size: 21px;
    color: red;
    cursor: pointer;
    background-color: #00518040;
    padding: 7px 9px;
    border-radius: 3px;
    box-shadow: 2px 2px 5px #a7888847;
    
  }
</style>