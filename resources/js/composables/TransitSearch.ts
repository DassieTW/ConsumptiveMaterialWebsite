import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useTransitSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // let transitclient = sessionStorage.getItem("transitclient");
        try {
            let response = await axios.post('/api/month/getTransit', {
                DB: getDB.data,
            });

            mats.value = JSON.stringify(response.data);
            // console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // get mats

    const updateInTransit = async (isn, qty, descr) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');
        
        try {
            let response = await axios.post('/api/month/updateTransit', {
                DB: getDB.data,
                username: username.data,
                isn: JSON.stringify(isn),
                qty: JSON.stringify(qty),
                descr: JSON.stringify(descr)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // updateInTransit

    return {
        mats,
        getMats,
        updateInTransit
    } // return
} // useConsumptiveMaterials
