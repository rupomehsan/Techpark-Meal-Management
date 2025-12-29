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
                  <label for="name" class="form-label">Name</label>
                  <input
                    type="text"
                    name="name"
                    v-model="form_fields.name"
                    class="form-control"
                    id="name"
                  />
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    name="email"
                    v-model="form_fields.email"
                    class="form-control"
                    id="email"
                  />
                </div>

                <div class="mb-3">
                  <label for="department" class="form-label">Department</label>
                  <select
                    name="department"
                    v-model="form_fields.department"
                    class="form-control"
                    @change="department"
                    id="department"
                  >
                    <option value="">Select a Department</option>
                    <option value="IT">IT</option>
                    <option value="IELTS">IELTS</option>
                    <option value="Spoken">Spoken</option>
                    <option value="Employee">Employee</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="batch_id" class="form-label">batch Name</label>
                  <select
                    name="batch_id"
                    v-model="form_fields.batch_id"
                    id="batch_id"
                    class="form-control"
                  >
                    <option value="">Select a Batch Name</option>
                    <option
                      v-for="batch in all_batches"
                      :key="batch.id"
                      :value="batch.id"
                    >
                      {{ batch.batch_name }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-md-6 pull-right">
                <div class="mb-3">
                  <label for="phone_number" class="form-label"
                    >phone number</label
                  >
                  <input
                    type="number"
                    name="phone_number"
                    v-model="form_fields.phone_number"
                    class="form-control"
                    id="phone_number"
                  />
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input
                    type="password"
                    v-model="form_fields.password"
                    name="password"
                    class="form-control"
                    id="password"
                  />
                </div>

                <div class="mb-3">
                  <label for="department" class="form-label">Role</label>
                  <select
                    name="role_id"
                    v-model="form_fields.role_id"
                    class="form-control"
                    id="role_id"
                  >
                    <option value="">Select a Role</option>
                    <option
                      v-for="role in all_role"
                      :key="role.id"
                      :value="role.id"
                    >
                      {{ role.name }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="image" class="form-label">Image</label>
                  <input
                    type="file"
                    name="image"
                    class="form-control"
                    id="image"
                  />
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label for="address" class="form-label">address</label>
                <text-editor :name="'address'" />
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            Submit
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
    all_role: [],
    all_batches: [],

    form_fields: {
      name: "",
      email: "",
      department: "",
      phone_number: "",
      password: "",
      batch_id: "",
      // address: '',
      role_id: "",
    },
  }),
  created: async function () {
    this.get_all_role();
    // this.get_all_role();

    let id = (this.param_id = this.$route.params.id);
    // this.reset_fields();
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

    department: async function () {
      try {
        const department = this.form_fields.department;
        const response = await axios.get(
          `/users/department-by-batchname/${department}`
        );
        this.all_batches = response.data.data;
      } catch (error) {
        console.error("Error fetching batches:", error);
      }
    },

    get_all_role: async function () {
      try {
        const response = await axios.get("roles");
        this.all_role = response.data.data.data;
        console.log("All roles:", this.all_role);
      } catch (error) {
        console.error("Error fetching roles:", error);
        this.all_role = [];
      }
    },


    // set_fields: async function (id) {
    //   this.param_id = id;
    //   await this.details(id);
    //   if (this.item) {
    //     this.form_fields.name = this.item.name;
    //     this.form_fields.email = this.item.email;
    //     this.form_fields.department = this.item.department;
    //     this.form_fields.phone_number = this.item.phone_number;
    //     this.form_fields.password = this.item.password;
    //     this.form_fields.batch_id = this.item.batch_id;
    //     this.form_fields.role_id = this.item.role_id;
    //     $("#address").summernote("code", this.item.address);
    //   }
    // },


    // get_all_role: async function () {
    //   let response = await axios.get("roles");
    //   if (response.data.status == "success") {
    //     const roles = response.data?.data?.data || [];
    //     console.log(roles);

    //     this.form_fields[8].data_list = roles.map((role) => ({
    //       label: role.name,
    //       value: role.id,
    //     }));

    //   }
    // },




    // set_fields: async function (id) {
    //   this.param_id = id;
    //   await this.details(id);
    //   if (this.item) {
    //     this.form_fields.name = this.item.name;
    //     this.form_fields.email = this.item.email;
    //     this.form_fields.phone_number = this.item.phone_number;
    //     this.form_fields.password = this.item.password;

    //     // set department first, then load batches for that department
    //     this.form_fields.department = this.item.department;
    //     await this.department(); // fills this.all_batches

    //     // set batch_id to the batch id (backend returns batch_id or nested batch.id)
    //     this.form_fields.batch_id =
    //       this.item.batch_id || (this.item.batch && this.item.batch.id) || "";

    //     this.form_fields.role_id = this.item.role_id;
    //     $("#address").summernote("code", this.item.address);
    //   }
    // },



  // set_fields: async function (id) {
  // this.param_id = id;
  // await this.details(id);

  // if (this.item) {
  //   // basic fields
  //   this.form_fields.name = this.item.name;
  //   this.form_fields.email = this.item.email;
  //   this.form_fields.phone_number = this.item.phone_number;
  //   this.form_fields.password = this.item.password;

  //   // department আগে সেট করো
  //   this.form_fields.department = this.item.department;

  //   // department wise batch গুলো load করো
  //   await this.department();

  //   // এখন batch_id বসাও (department লোড হয়ে গেলে select করা যাবে)
  //   this.form_fields.batch_id =
  //     this.item.batch_id || (this.item.batch && this.item.batch.id) || "";

  //   // role
  //   this.form_fields.role_id = this.item.role_id;

  //   // address summernote এ বসাও
  //   $("#address").summernote("code", this.item.address);
  // }
  // },


    set_fields: async function (id) {
      this.param_id = id;
      await this.details(id);

      if (this.item) {
        // basic fields
        this.form_fields.name = this.item.name;
        this.form_fields.email = this.item.email;
        this.form_fields.phone_number = this.item.phone_number;
        this.form_fields.password = this.item.password;

        // step 1: set department
        this.form_fields.department = this.item.department;

        // step 2: load batches for that department
        await this.department();

        // step 3: now set batch_id
        if (this.item.batch_id) {
          // case 1: backend directly returns batch_id
          this.form_fields.batch_id = this.item.batch_id;
        } else if (this.item.batch && this.item.batch.id) {
          // case 2: backend sends nested batch object
          this.form_fields.batch_id = this.item.batch.id;
        } else {
          this.form_fields.batch_id = "";
        }

        // role + address
        this.form_fields.role_id = this.item.role_id;
        $("#address").summernote("code", this.item.address);
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
      var markupStr = $("#address").summernote("code");
      var target = document.createElement("input");
      target.setAttribute("name", "address");
      target.value = markupStr;

      document.getElementById("address").appendChild(target);
    },

  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>
