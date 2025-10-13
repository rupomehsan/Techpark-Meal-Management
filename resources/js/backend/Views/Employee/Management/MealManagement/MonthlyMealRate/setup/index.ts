import app_config from "../../../../../../Config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Meal Rate Management";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "monthly-meal-rates",

  // module_name: "user",
  store_prefix: "monthlymealrates",
  route_prefix: "MonthlyMealRate",
  route_path: "monthlymealrates",

  select_fields: [
    "id",
    "month",
    "meal_rate",
    "is_visible",
    "month_start_date",
    "month_end_date",
    "slug",
    "created_at",
  ],

  sort_by_cols: [
    "id",
    "month",
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
