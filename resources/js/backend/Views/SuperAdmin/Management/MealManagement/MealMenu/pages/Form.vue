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
                  <label for="mealRate" class="form-label">Meal Date</label>
                  <input type="date" v-model="form_fields.meal_date" name="meal_date" class="form-control" id="mealRate">
                </div>
              </div>

              <div class="col-md-6 pull-right">

                <div class="mb-3">
                  <label for="receipy" class="form-label">Receipy</label>
                  <input type="text" v-model="form_fields.receipy" name="receipy" placeholder="receipy" class="form-control" id="receipy">
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <text-editor :name="'description'" />
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
import textEditor from "../../../../../../GlobalComponents/FormComponents/TextEditor.vue";
import axios from "axios";

export default {
  components: { textEditor },

  data: () => ({
    setup,
    form_fields,
    param_id: null,

    form_fields:{
      meal_date: '',
      receipy: '',
    }
    
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
          this.form_fields.meal_date = this.item.meal_date;
          this.form_fields.receipy = this.item.receipy;
          $("#description").summernote("code", this.item.description);
        }
    },

    submitHandler: async function ($event) {
      this.set_only_latest_data(true);
      if (this.param_id) {
        this.setSummerEditor();
        let response = await this.update($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data successfully updated");
          this.$router.push({
            name: `All${this.setup.route_prefix}`,
          });
        }
      } else {
        this.setSummerEditor();
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

    setSummerEditor() {
      var markupStr = $("#description").summernote("code");
      var target = document.createElement("input");
      target.setAttribute("name", "description");
      target.value = markupStr;

      document.getElementById("description").appendChild(target);
    },

  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>
