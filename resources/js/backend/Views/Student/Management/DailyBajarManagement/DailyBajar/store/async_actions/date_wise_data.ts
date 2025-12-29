import axios from "axios";
import setup from "../../setup";
import { mapWritableState } from "pinia";
import { store } from "..";

async function execute(date) {
    let state = mapWritableState(store, [
        "dateData",
        "all_data_count",
        "active_data_count",
        "inactive_data_count",
        "trased_data_count",
        "page",
        "paginate",
        "search_key",
        "sort_by_col",
        "sort_type",
        "status",
        "filter_criteria",
        "start_date",
        "end_date",
    ]);

    // build URL with query params so backend receives status / pagination / filters
    let base = `${setup.api_host}/${setup.api_version}/${setup.api_end_point}/date-wise-data/${date}`;
    let full = new URL(base);
    try {
        // basic params
        full.searchParams.set("page", state.page.get() + "");
        full.searchParams.set("paginate", state.paginate.get() + "");
        full.searchParams.set("limit", state.paginate.get() + "");
        full.searchParams.set("search_key", state.search_key.get() || "");
        full.searchParams.set("search", state.search_key.get() || "");
        full.searchParams.set("sort_by_col", state.sort_by_col.get() || "");
        full.searchParams.set("sort_type", state.sort_type.get() || "");
        full.searchParams.set("status", state.status.get() || "");
        if (state.start_date.get()) full.searchParams.set("start_date", state.start_date.get());
        if (state.end_date.get()) full.searchParams.set("end_date", state.end_date.get());

        // filter criterias
        let idx = 0;
        const criterias = state.filter_criteria.get();
        for (let key in criterias) {
            let value = criterias[key];
            if (value) {
                full.searchParams.set(`filter_criterias[${idx}][key]`, key);
                full.searchParams.set(`filter_criterias[${idx}][value]`, value);
                full.searchParams.set(key, value);
                idx++;
            }
        }

    // debug: log the URL being fetched so we can confirm status param is sent
    console.debug("date_wise_data fetch:", full.href);
    let response = await axios.get(full.href);

    // debug: log response for quick tracing in browser console
    console.debug("date_wise_data response:", response.data);

    // response.data.data is the paginated payload for dateData
        state.dateData.set(response.data.data);

            // set counts if provided by the API (axios response nests payload under response.data.data)
            if (response.data && response.data.data) {
                const payload = response.data.data;
                if (typeof payload.active_data_count !== "undefined")
                    state.active_data_count.set(payload.active_data_count);
                if (typeof payload.inactive_data_count !== "undefined")
                    state.inactive_data_count.set(payload.inactive_data_count);
                if (typeof payload.trased_data_count !== "undefined")
                    state.trased_data_count.set(payload.trased_data_count);
                if (typeof payload.total !== "undefined") state.all_data_count.set(payload.total);
            }

    } catch (error) {
        (window as any).s_alert("something is wrong.", "error");
        return error.response;
    }
}

export default execute;
