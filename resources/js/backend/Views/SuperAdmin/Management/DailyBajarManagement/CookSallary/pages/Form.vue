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
                  <label for="month" class="form-label">Month</label>
                  <input
                    type="month"
                    v-model="form_fields.month"
                    @change="selectMonth"
                    name="month"
                    class="form-control"
                    id="month"
                  />
                </div>

                <div class="mb-3">
                  <label for="sallary_status" class="form-label"
                    >Sallary Status</label
                  >
                  <select
                    name="sallary_status"
                    v-model="form_fields.sallary_status"
                    id=""
                    class="form-control"
                  >
                    <option value="">Selecte Salary Status</option>
                    <option value="paid" @click="paidImage">Paid</option>
                    <option value="unpaid" @click="UnpaidImage">UnPaid</option>
                  </select>
                </div>

                <div ref="imageDiv" class="mb-3" style="display: none">
                  <label for="image" class="form-label">Image</label>
                  <input
                    type="file"
                    name="image"
                    class="form-control"
                    id="image"
                  />
                </div>
              </div>

              <div class="col-md-6 pull-right">
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount</label>
                  <input
                    type="number"
                    v-model="form_fields.amount"
                    name="amount"
                    class="form-control"
                    id="amount"
                  />
                </div>

                <div class="mb-3">
                  <label for="amount" class="form-label">Due Amount</label>
                  <input
                    type="number"
                    v-model="form_fields.due_amount"
                    name="due_amount"
                    class="form-control"
                    id="amount"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cook sallary history start -->
        <div class="col-8 offset-2" v-show="showSalaryHistory">
          <table class="table table-hover text-center table-bordered">
            <thead>
              <tr>
                <th class="w-10">ID</th>
                <th>Month</th>
                <th>Amount</th>
                <th>Due Amount</th>
                <th>Sallary Status</th>
                <!-- <th>Image</th> -->
              </tr>
            </thead>

            <tbody v-if="salaryData?.data && salaryData.data.length > 0">
              <tr
                v-for="(item, index) in salaryData?.data"
                :key="item.id"
                :class="`table_rows table_row_${item.id}`"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ item.month ?? "N/A " }}</td>
                <td>{{ item.amount ?? "N/A " }}</td>
                <td>{{ item.due_amount ?? "N/A " }}</td>
                <td>{{ item.sallary_status ?? "N/A " }}</td>
                <!-- <td>
                    <img :src="item.image" alt="" height="50" width="50" />
                  </td>  -->
              </tr>
            </tbody>
          </table>
        </div>
        <!-- cook sallary history end -->

        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            {{ param_id ? `Update` : `Submit` }}
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
    salaryData: [],
    cookDalaryData: 0,

    showSalaryHistory: false,

    form_fields: {
      month: "",
      amount: "",
      due_amount: "",
      sallary_status: "",
    },
  }),

  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    if (id) {
      this.set_fields(id);
    }
    // load salary history on page load
    //await this.cookSallaryByDailyBajar();
  },

  // mounted: async function () {
  //    await this.cookSallaryByDailyBajar();
  // },

  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),

    cookSallaryByDailyBajar: async function () {
      try {
        const response = await axios.get(
          `cook-sallary/cook-salary-by-daily-bajar`
        );
        this.cookDalaryData = response.data.data || 0;
        console.log("cookDalaryData", this.cookDalaryData);
      } catch (error) {
        this.cookDalaryData = 0;
        console.error(
          "Error fetching cook salary by daily bajar:",
          error.response?.data?.message || error.message
        );
      }
    },

    selectMonth: async function () {
      if (!this.form_fields.month) {
        this.showSalaryHistory = false;
        return;
      }

      try {
        const response = await axios.get(
          `cook-sallary/cook-sallary-history/${this.form_fields.month}`
        );

        if (response.data.status == "not_found") {
          this.salaryData = [];
          this.showSalaryHistory = false;
        } else if (response.data.status == "success") {
          this.salaryData = [];
          this.salaryData = response.data;
          this.showSalaryHistory = true;
        } else {
          console.log("res", response);
        }
      } catch (error) {
        this.showSalaryHistory = false;

        this.salaryData = [];
        console.error(
          "Error fetching cook salary history:",
          error.response?.data?.message || error.message
        );
      }
    },

    set_fields: async function (id) {
      this.param_id = id;
      //   console.log('nahid', this.param_id);
      await this.details(id);
      if (this.item) {
        this.form_fields.month = this.item.month;
        this.form_fields.amount = this.item.amount;
        this.form_fields.due_amount = this.item.due_amount;
        this.form_fields.sallary_status = this.item.sallary_status;
      }
    },

    paidImage: async function () {
      this.$refs.imageDiv.style.display = "block";
    },

    UnpaidImage: async function () {
      this.$refs.imageDiv.style.display = "none";
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
  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },

  watch: {
    "form_fields.sallary_status"(newVal) {
      if (newVal === "paid") {
        this.paidImage();
      } else if (newVal === "unpaid") {
        this.UnpaidImage();
      }
    },
  },
};
</script>
