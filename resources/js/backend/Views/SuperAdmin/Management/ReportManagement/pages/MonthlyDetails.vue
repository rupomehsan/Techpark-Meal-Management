<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <!-- <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{ setup.monthly_details_report_page_title }}
          </h5>

          <div class="row ml-5 align-items-end">
            <form
              @submit.prevent="filtterByMonthlyAllReport(month)"
              class="w-100"
            >
              <div class="row align-items-end g-2">
                <div class="col-md-2">
                  <label for="start_month" class="form-label mb-0"
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

                <div class="col-md-2">
                  <label for="end_date" class="form-label mb-0">END Date</label>
                  <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    class="form-control"
                    v-model="end_date"
                  />
                </div>

                <div class="col-md-3">
                  <button type="submit" class="btn btn-outline-success w-100">
                    FILTER DATA
                  </button>
                </div>

                <div class="col-md-3">
                  <button
                    @click="clearData"
                    type="button"
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
              class="btn btn-outline-warning btn-sm mt-5"
              :to="{ name: `Monthly${setup.route_prefix}` }"
            >
              {{ setup.monthly_report_page_title }}
            </router-link>
          </div>
        </div> -->

        <div
          class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"
        >
          <h5 class="text-capitalize mb-3 mb-md-0">
            {{ setup.monthly_details_report_page_title }}
          </h5>

          <div class="w-100 w-md-auto">
            <form
              @submit.prevent="filtterByMonthlyAllReport(month)"
              class="w-100"
            >
              <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-3">
                  <label for="start_month" class="form-label mb-0"
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

                <div class="col-12 col-sm-6 col-md-3 pt-4">
                  <button
                    type="submit"
                    class="btn btn-outline-success mt-2 mt-sm-0"
                  >
                    FILTER DATA
                  </button>
                </div>

                <div class="col-12 col-sm-6 col-md-3 pt-4">
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
              :to="{ name: `Monthly${setup.route_prefix}` }"
            >
              {{ setup.monthly_report_page_title }}
            </router-link>
          </div>
        </div>

        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-12 pb-5">
              <table
                id="my-table"
                class="table table-hover text-center table-bordered"
              >
                <thead>
                  <tr>
                    <th class="w-10">#Sl No.</th>
                    <th>Total Bazar</th>
                    <th>Cook Sallary</th>
                    <th>Total Cost</th>
                    <th>Bazar Date</th>
                  </tr>
                </thead>

                <tbody>
                  <tr
                    v-for="(report, index) in item && item.daily_summary
                      ? item.daily_summary
                      : []"
                    :key="report.date"
                    v-if="
                      item &&
                      item.daily_summary &&
                      item.daily_summary.length > 0
                    "
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ report?.total_bajar_cost ?? "N/A" }}</td>
                    <td>{{ report?.cook_salary ?? "N/A" }}</td>
                    <td>{{ report?.grand_total ?? "N/A" }}</td>
                    <td>{{ report?.bajar_date ?? "N/A" }}</td>
                  </tr>

                  <tr v-else>
                    <td colspan="5" class="text-center text-gray-500">
                      No data found
                    </td>
                  </tr>

                  <tr>
                    <td colspan="2" class="text-center font-bold">
                      Total Bazar Cost:
                      {{
                        item &&
                        item.monthly_total &&
                        item.monthly_total.monthly_total_bajar_cost
                          ? item.monthly_total.monthly_total_bajar_cost
                          : "N/A"
                      }}
                    </td>
                    <td class="text-center font-bold">
                      Total Cook Salary:
                      {{
                        item &&
                        item.monthly_total &&
                        item.monthly_total.monthly_total_cook_salary
                          ? item.monthly_total.monthly_total_cook_salary
                          : "N/A"
                      }}
                    </td>
                    <td colspan="2" class="text-center font-bold">
                      Monthly Total Cost:
                      {{
                        item &&
                        item.monthly_total &&
                        item.monthly_total.monthly_grand_total
                          ? item.monthly_total.monthly_grand_total
                          : "N/A"
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button
            type="button"
            @click="downloadPDF"
            class="btn btn-outline-warning btn-sm ml-2"
          >
            Download PDF
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
    month: "",

    // allItems: [],

    selectedMonth: null,
    monthlyItemsMap: {},

    monthly_total: {
      monthly_total_bajar_cost: 0,
      monthly_total_cook_salary: 0,
      monthly_grand_total: 0,
    },
  }),

  created: async function () {
    let month = (this.param_month = this.$route.params.month);
    this.month = month;

    await this.get_data(month);
  },

  methods: {
    ...mapActions(store, {
      getMonthlyDetailsReport: "getMonthlyDetailsReport",
    }),

    get_data: async function (month) {
      // clear item while loading so template guards work reliably
      this.item = null;
      await this.monthlyDetailsReport(month);
      // await this.dawnloadPdf(month);
    },

    // downloadPDF: async function () {
    //   const doc = new jsPDF();

    //   // Title
    //   doc.setFontSize(18);
    //   doc.text("Monthly Details Invoice", 14, 22);

    //   // Convert HTML table to PDF
    //   autoTable(doc, {
    //     html: "#my-table",
    //     startY: 30,
    //     theme: "grid",
    //     headStyles: { fillColor: [34, 67, 45], textColor: 255 },
    //     // footStyles: { fillColor:  [34,67,45], textColor: 255 },
    //     styles: { fontSize: 10 },
    //   });

    //   // Save the PDF
    //   doc.save("monthly-details-invoice.pdf");
    // },

    downloadPDF: async function () {
      const doc = new jsPDF();

      // Title
      doc.setFontSize(18);
      doc.text("Monthly Details Invoice", 14, 22);
      // show month in header if available
      const monthLabel = this.month || this.param_month || "";
      if (monthLabel) {
        doc.setFontSize(12);
        // doc.text(`Month: ${monthLabel}`, 14, 30);
      }

      // Convert HTML table to PDF
      autoTable(doc, {
        html: "#my-table",
        startY: 30,
        theme: "grid",
        headStyles: { fillColor: [34, 67, 45], textColor: 255 },
        // footStyles: { fillColor:  [34,67,45], textColor: 255 },
        styles: { fontSize: 10 },
      });

      // Save the PDF with month in filename when available
      const safeMonth = monthLabel
        ? "_" + String(monthLabel).replace(/[^a-z0-9-_]/gi, "")
        : "";
      doc.save(`monthly-details-invoice${safeMonth}.pdf`);
    },

    monthlyDetailsReport: async function (month) {
      try {
        const response = await axios.get(
          `/meal-report/monthly-details-report/${month}`,
          {
            params: {
              month: month,
            },
          }
        );
        this.item = response.data;

        // console.log('monthly report', this.item);
      } catch (error) {
        console.error("Error fetching user report:", error);
      }
    },

    filtterByMonthlyAllReport: async function (month) {
      const startMonth = this.start_date.slice(0, 7);
      const endMonth = this.end_date.slice(0, 7);

      if (startMonth !== month || endMonth !== month) {
        return alert(`You can only filter within ${month} !`);
      }

      try {
        const response = await axios.get(
          `/meal-report/monthly-details-report/${month}`,
          {
            params: {
              start_date: this.start_date,
              end_date: this.end_date,
            },
          }
        );

        this.item = response.data;
      } catch (error) {
        console.error("Error fetching user report:", error);
      }
    },

    clearData: function () {
      this.start_date = null;
      this.end_date = null;

      if (this.month) {
        this.get_data(this.month);
      } else {
        this.item = [];
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

<style>
tr th {
  text-align: left !important;
}

.download-btn:hover {
  color: white;
}

.download-btn:hover .icon {
  color: white;
}
</style>
