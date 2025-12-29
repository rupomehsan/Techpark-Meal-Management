import app_config from "../../../../../Config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Employee Payment";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "user-payments",

  // module_name: "user",
  store_prefix: "employeepayment",
  route_prefix: "EmployeePayment",
  route_path: "employeepayment",

  select_fields: [
    "id",
    "user_id",
    "month",
    "payment_date",
    "amount",
    "slug",
    "created_at",
  ],

  sort_by_cols: [
    "id",
    "user_id",
    "month",
    "payment_date",
    "amount",
    "slug",
    "created_at",
  ],

  layout_title: prefix + " Management",
  page_title: `${prefix} Management`,

  all_page_title: "All " + prefix,
  details_page_title: "Details " + prefix,
  create_page_title: "Create " + prefix,
  edit_page_title: "Edit " + prefix,
};

export default setup;
