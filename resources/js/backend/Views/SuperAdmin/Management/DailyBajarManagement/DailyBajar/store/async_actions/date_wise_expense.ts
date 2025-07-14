import axios from "axios";
import setup from "../../setup";
import { mapWritableState } from "pinia";
import { store } from "..";

async function execute(){
    let state = mapWritableState(store, [
        'expense',
    ]);

    let url = `${setup.api_host}/${setup.api_version}/${setup.api_end_point}/expense-date`;
    
    try {
        let response = await axios.get(url);
        state.expense.set(response.data.data);
    } catch (error) {
        (window as any).s_alert('something is wrong.','error');
        return error.response;
    }
}

export default execute;
