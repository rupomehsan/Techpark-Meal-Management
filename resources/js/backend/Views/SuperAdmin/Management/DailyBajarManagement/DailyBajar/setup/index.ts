import app_config from "../../../../../../Config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Daily Bajar";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "daily-bajars",


  store_prefix: "dailybajar",
  route_prefix: "dailyBajar",
  route_path: "dailybajar",

  select_fields: [
    "id",
    "title",
    "quantity",
    "unit",
    "price",
    "total",
    "bajar_date",
    "slug",
    "created_at",
  ],

  sort_by_cols: [
    "id",
    "title",
    "quantity",
    "unit",
    "price",
    "total",
    "bajar_date",
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
