// import app_config from "../../../../../Config/app_config";
import app_config from "../../../../../../Config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Off Meal Management";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "off-meals",

  // module_name: "user",
  store_prefix: "offmeals",
  route_prefix: "OffMeals",
  route_path: "offmeals",

  select_fields: [
    "id",
    "off_date",
    "meal_status",
    "description",
    "slug",
  ],

  sort_by_cols: [
    "id",
    "off_date",
    "meal_status",
    "description",
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
