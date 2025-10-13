import axios from "axios";
import setup from "../../setup";
import { mapWritableState } from "pinia";
import { store } from "..";

async function execute(date){
    let state = mapWritableState(store, [
        'item',
    ]);

    let url = `${setup.api_host}/${setup.api_version}/${setup.api_end_point}/${date}`;
    try {
        let response = await axios.get(url);
        console.log('OK', response);

        state.item.set(response.data.data);
    } catch (error) {
        (window as any).s_alert('something is wrong.','error');
        return error.response;
    }
}

export default execute;
