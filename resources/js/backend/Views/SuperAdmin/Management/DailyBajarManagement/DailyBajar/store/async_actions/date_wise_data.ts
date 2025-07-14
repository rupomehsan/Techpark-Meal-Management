import axios from "axios";
import setup from "../../setup";
import { mapWritableState } from "pinia";
import { store } from "..";

async function execute(date){
    let state = mapWritableState(store, [
        'dateData',
    ]);

    let url = `${setup.api_host}/${setup.api_version}/${setup.api_end_point}/date-wise-data/${date}`;
    try {
        let response = await axios.get(url);
        console.log("frem date wise",response.data.data);
        
        state.dateData.set(response.data.data);
        // console.log(" state.item", state.item.get());
        
    } catch (error) {
        (window as any).s_alert('something is wrong.','error');
        return error.response;
    }
}

export default execute;
