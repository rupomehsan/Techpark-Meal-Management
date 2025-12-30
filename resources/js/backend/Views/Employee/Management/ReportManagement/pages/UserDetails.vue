<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <!-- <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{ setup.user_details_report_page_title }}
          </h5>

          <div class="row ml-5 align-items-end">
            <form @submit.prevent="usersReport(id)" class="w-100">
              <div class="row align-items-end g-2">
                <div class="col-md-3">
                  <label for="start_date" class="form-label mb-0"
                    >START Date</label
                  >
                  <input
                    type="date"
                    name="start_date"
                    id="start_date"
                    class="form-control"
                    v-model="start_date"
                  />
                </div>

                <div class="col-md-3">
                  <label for="end_date" class="form-label mb-0">END Date</label>
                  <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    class="form-control"
                    v-model="end_date"
                  />
                </div>

                <div class="col-md-2">
                  <button type="submit" class="btn btn-outline-success w-100">
                    FILTER DATA
                  </button>
                </div>

                <div class="col-md-2">
                  <button
                    @click="clearData()"
                    type="submit"
                    class="btn btn-outline-warning w-100"
                  >
                    CLEAR DATA
                  </button>
                </div>
              </div>
            </form>
          </div>

          <div>
            <router-link
              class="btn btn-outline-warning btn-sm mt-3"
              :to="{ name: `User${setup.route_prefix}` }"
            >
              {{ setup.users_report_page_title }}
            </router-link>
          </div>
        </div> -->

        <div
          class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"
        >
          <h5 class="text-capitalize mb-3 mb-md-0">
            {{ setup.user_details_report_page_title }}
          </h5>

          <div class="w-100 w-md-auto">
            <form @submit.prevent="usersReport(id)" class="w-100">
              <div class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                  <label for="start_date" class="form-label mb-0"
                    >START Date</label
                  >
                  <input
                    type="date"
                    name="start_date"
                    id="start_date"
                    class="form-control"
                    v-model="start_date"
                  />
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                  <label for="end_date" class="form-label mb-0">END Date</label>
                  <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    class="form-control"
                    v-model="end_date"
                  />
                </div>

                <div class="col-12 col-sm-6 col-md-3 d-flex align-items-end">
                  <button
                    type="submit"
                    class="btn btn-outline-success mt-2 mt-sm-0"
                  >
                    FILTER DATA
                  </button>
                </div>

                <div class="col-12 col-sm-6 col-md-3 d-flex align-items-end">
                  <button
                    @click="clearData"
                    type="button"
                    class="btn btn-outline-warning mt-2 mt-sm-0"
                  >
                    CLEAR DATA
                  </button>
                </div>
              </div>
            </form>
          </div>

          <div class="mt-3 mt-md-0 pt-3">
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `User${setup.route_prefix}` }"
            >
              {{ setup.users_report_page_title }}
            </router-link>
          </div>
        </div>

        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-6 pb-3">
              <table
                id="user_info_table"
                class="table quick_modal_table table-bordered"
              >
                <thead>
                  <tr>
                    <th colspan="3" class="text-center">User InFo.</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <h5 class="text-center mt-2">User InFo.</h5> -->
                  <tr>
                    <th>User Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.user_name ?? "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Month</th>
                    <th class="text-center">:</th>
                    <th>{{ item?.month ?? "N/A" }}</th>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="col-lg-6 pb-3">
              <table
                id="user_expense_table"
                class="table quick_modal_table table-bordered"
              >
                <thead>
                  <tr>
                    <th colspan="3" class="text-center">User Expense Info</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>Total Meals</th>
                    <th class="text-center">:</th>
                    <th>
                      {{
                        item &&
                        item.monthly_summary &&
                        item.monthly_summary.total_meals_in_month
                          ? item.monthly_summary.total_meals_in_month
                          : "0"
                      }}
                    </th>
                  </tr>
                  
                  <tr>
                    <th>Total Cost</th>
                    <th class="text-center">:</th>
                    <th>
                      {{
                        item &&
                        item.monthly_summary &&
                        item.monthly_summary.total_meal_cost_in_month
                          ? item.monthly_summary.total_meal_cost_in_month
                          : "0"
                      }}
                    </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 pb-5">
              <table
                id="my_table"
                class="table table-hover text-center table-bordered"
              >
                <thead>
                  <tr>
                    <th class="w-10">ID</th>
                    <th>Meal Date</th>
                    <th>Meal Quantity / Day</th>
                    <th>Meal Rate / Day</th>
                    <th>Total Meal Rate</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(report, index) in item && item.daily_report
                      ? item.daily_report
                      : []"
                    :key="report.date"
                    v-if="
                      item && item.daily_report && item.daily_report.length > 0
                    "
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ report?.date ?? "N/A" }}</td>
                    <td>{{ report?.quantity ?? "N/A" }}</td>
                    <td>{{ report?.meal_rate ?? "N/A" }}</td>
                    <td>{{ report?.user_amount ?? "N/A" }}</td>
                  </tr>

                  <tr v-else>
                    <td colspan="5" class="text-center text-gray-500">
                      No data found
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- <table id="my_tables" class="table table-bordered w-100 text-center">
                <thead>
                  <tr>
                    <th colspan="6" class="text-center">User Info & Expense Info</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>User Name</th>
                    <td>{{ item?.user_name ?? "N/A" }}</td>
                    <th colspan="3">Total Meals</th>
                    <td>{{ item?.monthly_summary?.total_meals_in_month ?? "0" }}</td>
                  </tr>
                  <tr>
                    <th>Month</th>
                    <td>{{ item?.month ?? "N/A" }}</td>
                    <th colspan="2">Total Cost</th>
                    <td colspan="2" class="text-start">
                      &#2547; {{ item?.monthly_summary?.total_meal_cost_in_month ?? "0" }}
                    </td>
                  </tr>
                </tbody>

                
              </table> -->
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button
            type="button"
            @click="downloadPDF"
            class="btn btn-outline-warning btn-sm ml-2"
          >
            Dawnload Pdf
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export default {
  data: () => ({
    setup,
    start_date: "",
    end_date: "",
    id: "",
    allItems: [],
    // `item` is provided by the store via mapWritableState; don't define it here to avoid collisions.
  }),

  created: async function () {
    let id = (this.param_slug = this.$route.params.slug);
    let month = (this.param_date = this.$route.params.date);
    this.id = id;
    // console.log('created', this.id);

    await this.get_data(id, month);
  },

  methods: {
    ...mapActions(store, {
      getUserReport: "getUserReport",
    }),

    get_data: async function (id, month) {
      // this.item = {};
      this.item = null;
      await this.UserReport(id, month);
      // await this.usersReport(id);
    },

    // downloadPDF: async function () {
    //   const doc = new jsPDF();
    //   doc.setFont("helvetica", "normal");

    //   // Title
    //   doc.setFontSize(18);
    //   doc.text("User Details Report", 14, 22);

    //   // Safe user name and month
    //   const userName =
    //     this.item && this.item.user_name
    //       ? this.item.user_name
    //       : this.id || "User";
    //   const monthLabel =
    //     this.item && this.item.month ? this.item.month : this.param_date || "";

    //   doc.setFontSize(12);
    //   doc.text(
    //     "Name: " + userName + (monthLabel ? " | Month: " + monthLabel : ""),
    //     14,
    //     30
    //   );

    //   // First table start position
    //   const firstTableY = 36;
    //   autoTable(doc, {
    //     html: "#user_info_table",
    //     startY: firstTableY,
    //     theme: "grid",
    //     headStyles: { fillColor: [34, 67, 45], textColor: 255 },
    //     styles: { fontSize: 10 },
    //   });

    //   // Position second table after the first
    //   const afterFirst = doc.lastAutoTable
    //     ? doc.lastAutoTable.finalY + 8
    //     : firstTableY + 30;
    //   autoTable(doc, {
    //     html: "#user_expense_table",
    //     startY: afterFirst,
    //     theme: "grid",
    //     headStyles: { fillColor: [34, 67, 45], textColor: 255 },
    //     styles: { fontSize: 10 },
    //   });

    //   // Position third table after the second
    //   const afterSecond = doc.lastAutoTable
    //     ? doc.lastAutoTable.finalY + 8
    //     : afterFirst + 30;
    //   autoTable(doc, {
    //     html: "#my_table",
    //     startY: afterSecond,
    //     theme: "grid",
    //     headStyles: { fillColor: [34, 67, 45], textColor: 255 },
    //     styles: { fontSize: 10 },
    //   });

    //   // Sanitize filename
    //   const safeName = userName
    //     .replace(/[^a-z0-9-_ ]/gi, "")
    //     .replace(/\s+/g, "_");
    //   const safeMonth = monthLabel
    //     ? "_" + monthLabel.replace(/[^a-z0-9-_]/gi, "")
    //     : "";
    //   doc.save(`user_details_report_${safeName}${safeMonth}.pdf`);
    // },

    downloadPDF: async function () {
      const doc = new jsPDF();
      doc.setFont("helvetica", "normal");

      // Title
      doc.setFontSize(18);
      doc.text("User Details Report", 14, 22);
      doc.setFontSize(12);

      // 1st Table (User Info)
      autoTable(doc, {
        html: "#user_info_table", // প্রথম টেবিলের ID
        startY: 30,
        theme: "grid",
        headStyles: { fillColor: [34, 67, 45], textColor: 255 },
        styles: { fontSize: 10 },
      });

      // 2nd Table (User Expense Info)
      autoTable(doc, {
        html: "#user_expense_table", // দ্বিতীয় টেবিলের ID
        startY: doc.lastAutoTable.finalY + 10, // আগের টেবিলের নিচে বসবে
        theme: "grid",
        headStyles: { fillColor: [34, 67, 45], textColor: 255 },
        styles: { fontSize: 10 },
      });

      // 3rd Table (Daily Report)
      autoTable(doc, {
        html: "#my_table", // আপনার আগের daily report টেবিল
        startY: doc.lastAutoTable.finalY + 10,
        theme: "grid",
        headStyles: { fillColor: [34, 67, 45], textColor: 255 },
        styles: { fontSize: 10 },
      });

      // Save PDF
      doc.save(
        `user_details_report_${this.item.user_name}_${this.item.month}.pdf`
      );
    },

    UserReport: async function (id, month) {
      try {
        const response = await axios.get(`/meal-report/user-report/${id}`, {
          params: { month: month },
        });
        this.item = response.data;
        console.log("user name", this.item);
      } catch (error) {
        console.error("Error fetching user report:", error);
      }
    },

    usersReport: async function (id) {
      if (new Date(this.start_date) > new Date(this.end_date)) {
        return window.alert("Start Date cannot be after End Date.");
      }

      try {
        const response = await axios.get(`/meal-report/user-report/${id}`, {
          params: {
            start_date: this.start_date,
            end_date: this.end_date,
          },
        });
        this.item = response.data;
        console.log("item ok", this.item);
      } catch (error) {
        console.error("Error fetching filtered data:", error);
      }
    },

    clearData: async function () {
      this.start_date = null;
      this.end_date = null;
      this.item = [...this.allItems];
      console.log("user id", this.allItems);
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
