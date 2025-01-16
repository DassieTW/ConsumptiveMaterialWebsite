import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useCheckingInventory() {
    const checking_records = ref("");
    const errors = ref("");
    const router = useRouter();

    const upload_checkig_result = async (pn_array, loc_array, stock_array) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');

        try {
            let response = await axios.post('/api/checking/upload', {
                DB: getDB.data,
                username: username.data,
                pn_array: JSON.stringify(pn_array),
                loc_array: JSON.stringify(loc_array),
                stock_array: JSON.stringify(stock_array)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // try catch
    } // upload_checkig_result

    const get_checking_records = async (timeRange) => {
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');

        try {
            let response = await axios.post('/api/checking/getCheckingRecords', {
                DB: getDB.data,
                timeRange: timeRange,
                username: username.data
            });

            checking_records.value = JSON.stringify(response.data);
        } catch (e) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(e); // test
            errors.value = e.response.data;
        } // try catch
    } // get_checking_records

    const checking_Reject = async (serial_num) => {
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');

        try {
            let response = await axios.post('/api/checking/reject', {
                DB: getDB.data,
                username: username.data,
                serial_num: serial_num,
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // try catch
    } // checking_Reject

    const checking_Approve = async (serial_num, isn, loc, stock) => {
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');

        try {
            let response = await axios.post('/api/checking/approve', {
                DB: getDB.data,
                username: username.data,
                serial_num: serial_num,
                isn: JSON.stringify(isn),
                loc: JSON.stringify(loc),
                stock: JSON.stringify(stock)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // try catch
    } // checking_Approve

    return {
        checking_records,
        upload_checkig_result,
        get_checking_records,
        checking_Approve,
        checking_Reject,
    } // return
} // useSSZSearch
