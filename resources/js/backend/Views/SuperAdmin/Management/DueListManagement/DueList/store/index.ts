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

/** actions */
import set_only_latest_data from './actions/set_only_latest'
import set_item from "./actions/set_item";
import set_status from './actions/set_status';
import set_page from './actions/set_page';

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
        
        
        
        /* actions */
        set_only_latest_data: set_only_latest_data,
        set_item: set_item,
        set_status: set_status,
        set_page: set_page

    },
});


