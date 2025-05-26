import { defineStore } from "pinia";
import { initialState } from "./initial_state";

/** async actions */
import all from './async_actions/all';
import create from './async_actions/create';
import details from './async_actions/details';
import update from './async_actions/update';
import destroy from './async_actions/destroy';
import soft_delete from './async_actions/soft_delete';
import restore from './async_actions/restore';
import update_status from './async_actions/update_status';
import bulk_action from './async_actions/bulk_action';
import import_data from './async_actions/import_data';

/** actions */

import set_filter_criteria from "./actions/set_filter_criteria";
import set_item from "./actions/set_item";
import set_only_latest_data from "./actions/set_only_latest";
import set_page from "./actions/set_page";
import set_paginate from "./actions/set_paginate";
import set_show_details_canvas from "./actions/set_show_details_canvas";
import set_show_filter_canvas from "./actions/set_show_filter_canvas";
import set_status from "./actions/set_status";
import clear_selected from "./actions/clear_selected";
import setup from "../setup";

export const store = defineStore(setup.store_prefix, {
    state: () => initialState,
    getters: {},
    actions: {
        /* async actions */
        get_all: all,
        create: create,
        details: details,
        update: update,
        destroy: destroy,
        soft_delete: soft_delete,
        restore: restore,
        update_status: update_status,
        bulk_action: bulk_action,
        import_data: import_data,

        
        
        
        /* actions */
        set_only_latest_data,
        set_item,
        set_page,
        set_paginate,
        set_show_details_canvas,
        set_show_filter_canvas,
        set_filter_criteria,
        set_status,
        clear_selected,

    },
});


