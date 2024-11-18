import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useTransitSearch() {
    const mats_inTransit = ref("");
    const errors = ref("");
    const router = useRouter();

    const getTransit = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        try {
            let response = await axios.post('/api/month/getTransit', {
                DB: getDB.data,
            });

            mats_inTransit.value = JSON.stringify(response.data);
            // console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // get mats_inTransit

    const updateInTransit = async (isn, qty, descr) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');
        // console.log(username); // test
        try {
            let response = await axios.post('/api/month/updateTransit', {
                DB: getDB.data,
                username: username.data,
                isn: JSON.stringify(isn),
                qty: JSON.stringify(qty),
                descr: JSON.stringify(descr)
            });

            // console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // try catch
    } // updateInTransit

    return {
        mats_inTransit,
        getTransit,
        updateInTransit
    } // return
} // useConsumptiveMaterials
