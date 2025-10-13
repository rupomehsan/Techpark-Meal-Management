<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">{{ setup.daily_report_page_title }}</h5>
        </div>

        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-10 offset-1">
              <table id="#my-table" class="table quick_modal_table table-bordered">
                <tbody>
                  <tr>
                    <th>Meal Quantity</th>
                    <th class="text-center">:</th>
                    <th>
                      {{
                        item && item.data && item.data.total_meal_qty
                          ? item.data.total_meal_qty
                          : "N/A"
                      }}
                    </th>
                  </tr>
                  <tr>
                    <th>Meal Rate</th>
                    <th class="text-center">:</th>
                    <th>
                      Tk.
                      {{
                        item && item.data && item.data.meal_rate
                          ? item.data.meal_rate
                          : "N/A"
                      }}
                    </th>
                  </tr>
                  <tr>
                    <th>Total Bazar</th>
                    <th class="text-center">:</th>
                    <th>
                      Tk.
                      {{
                        item && item.data && item.data.total_bazar_cost
                          ? item.data.total_bazar_cost
                          : "N/A"
                      }}
                    </th>
                  </tr>
                  <tr>
                    <th>Total Cook Sallary</th>
                    <th class="text-center">:</th>
                    <th>
                      Tk.
                      {{
                        item && item.data && item.data.total_cook_salary
                          ? item.data.total_cook_salary
                          : "N/A"
                      }}
                    </th>
                  </tr>
                  <tr>
                    <th>Total Cost</th>
                    <th class="text-center">:</th>
                    <th>
                      Tk.
                      {{
                        item && item.data && item.data.grand_total
                          ? item.data.grand_total
                          : "N/A"
                      }}
                    </th>
                  </tr>
                  <tr>
                    <th>Start Date</th>
                    <th class="text-center">:</th>
                    <th>
                      {{
                        item && item.data && item.data.period_start
                          ? item.data.period_start
                          : "N/A"
                      }}
                    </th>
                  </tr>
                  <tr>
                    <th>End Date</th>
                    <th class="text-center">:</th>
                    <th>
                      {{
                        item && item.data && item.data.period_end
                          ? item.data.period_end
                          : "N/A"
                      }}
                    </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <button
              type="button"
              @click="downloadPDF"
              class="btn btn-outline-warning btn-sm ml-5"
            >
              Download PDF
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import axios from "axios";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export default {
  data: () => ({
    setup,
    user_id: "",
    start_date: "",
    end_date: "",
    total_amount: 0,
    date: "",
    grand_total: 0,
    cook_salary: 0,
    total_cost: 0,
    meal_rate: 0,
    meal_qty: 0,
    total_meal_qty: 0,
  }),

  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    await this.get_data(id);
  },

  methods: {
    ...mapActions(store, {
      dailyReport: "dailyReport",
      // get_all: "get_all",
    }),

    get_data: async function () {
      this.item = null;
      await this.dailyReport();
    },

    downloadPDF: async function() {
      const doc = new jsPDF();

      // compute readable month and today's date for header & filename
      const now = new Date();
      const yyyy = now.getFullYear();
      const mm = String(now.getMonth() + 1).padStart(2, "0");
      const dd = String(now.getDate()).padStart(2, "0");
      const monthName = now.toLocaleString("default", { month: "long" });
      const todayISO = `${yyyy}-${mm}-${dd}`;

      doc.setFontSize(18);
      doc.text(`Daily Meal Report - ${monthName} ${yyyy}`, 14, 22);
      doc.setFontSize(11);
      doc.setTextColor(100);
      // smaller subtitle with exact date
      doc.text(`Date: ${todayISO}`, 14, 30);

      const tableColumn = ["Description", "Amount"];
      const tableRows = [];

      if (this.item && this.item.data) {
        const reportData = [
          {
            description: "Meal Quantity",
            amount: this.item.data.total_meal_qty,
          },
          {
            description: "Meal Rate",
            amount: `Tk. ${this.item.data.meal_rate}`,
          },
          {
            description: "Total Bazar",
            amount: `Tk. ${this.item.data.total_bazar_cost}`,
          },
          {
            description: "Total Cook Salary",
            amount: `Tk. ${this.item.data.total_cook_salary}`,
          },
          {
            description: "Total Cost",
            amount: `Tk. ${this.item.data.grand_total}`,
          },
          // { description: "Start Date", amount: this.item.data.period_start },
          // { description: "End Date", amount: this.item.data.period_end },
        ];

        reportData.forEach((item) => {
          const row = [item.description, item.amount];
          tableRows.push(row);
        });
      }

      autoTable(doc, {
        startY: 38,
        head: [tableColumn],
        body: tableRows,
      });


      const filename = `daily_meal_report_${todayISO}.pdf`;
      // const filename = `daily_meal_report_${monthName}_${todayISO}.pdf`;
      doc.save(filename);
    },


    dailyReport: async function () {
      try {
        const response = await axios.get(`/meal-report/daily-report`);
        this.item = response.data.data;
        console.log("daily report", this.item);
      } catch (error) {
        console.error(
          "Submission error:",
          error.response?.data.data || error.message
        );
      }
    },

    filterDate: async function () {
      if (!this.start_date || !this.end_date) {
        Swal.fire({
          icon: "warning",
          title: "Missing Dates",
          text: "Please select both start and end dates.",
        });
        return;
      }

      if (new Date(this.start_date) > new Date(this.end_date)) {
        return window.alert("Start Date cannot be after End Date.");
      }
      try {
        const response = await axios.get(`/meal-report/daily-report`, {
          params: {
            start_date: this.start_date,
            end_date: this.end_date,
          },
        });

        this.item = response.data.data;
        // this.total_amount = this.item.data.reduce((sum, entry) => sum + Number(entry.amount), 0);
      } catch (error) {
        console.error("Error fetching filtered data:", error);
      }
    },

    clearData: async function () {
      this.start_date = null;
      this.end_date = null;
      await this.dailyReport();
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
