import app_config from "../../../../../Config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Report Management";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "meal-report",

  // module_name: "user",
  store_prefix: "mealreport",
  route_prefix: "ReportMeal",
  route_path: "mealreport",

  select_fields: [
    "id",
    // "user_id",
    // "quantity",
    // "date",
    // "meal_status",
    "slug",
  ],

  sort_by_cols: [
    "id",
    // "user_id",
    // "quantity",
    "created_at",
  ],

  layout_title: prefix + " Management",
  page_title: `${prefix} Management`,

  all_page_title: "All " + prefix,
  details_page_title: "Details " + prefix,
  create_page_title: "Create " + prefix,
  edit_page_title: "Edit " + prefix,

  users_report_page_title: "Users " + prefix,
  user_report_page_title: "User " + prefix,
  user_details_report_page_title: "User Details " + prefix,
  daily_report_page_title: "Daily " + prefix,
  monthly_report_page_title: "Monthly " + prefix,
  monthly_details_report_page_title: "Monthly Details " + prefix,

};

export default setup;
