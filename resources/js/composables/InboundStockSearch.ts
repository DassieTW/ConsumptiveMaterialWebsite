import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useInboundStockSearch() {
    const mats = ref("");
    const queryResult = ref("");
    const locations = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let inboundclient = sessionStorage.getItem("inboundstockclient");
        let inboundisn = sessionStorage.getItem("inboundstockisn");
        let inboundloc = sessionStorage.getItem("inboundstockloc");
        let inboundsend = sessionStorage.getItem("inboundstocksend");
        let inboundmonth = sessionStorage.getItem("inboundstockmonth");
        let inboundnogood = sessionStorage.getItem("inboundstocknogood");

        try {
            let response = await axios.post('/api/inbound/searchstock', {
                DB: getDB.data,
                inboundclient: inboundclient,
                inboundisn: inboundisn,
                inboundloc: inboundloc,
                inboundsend: inboundsend,
                inboundmonth: inboundmonth,
                inboundnogood: inboundnogood
            });

            mats.value = JSON.stringify(response.data);
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors
            console.log(errors.value); // test
        } // try catch
    } // get mats

    const validateISN = async (inputArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/inbound/validateISN', {
                DB: getDB.data,
                isnArray: JSON.stringify(inputArray)
            });

            queryResult.value = JSON.stringify(response.data);
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors
            console.log(errors.value); // test
        } // try catch
    } // validateISN

    const getLocs = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/inbound/getLocs', {
                DB: getDB.data
            });

            locations.value = JSON.stringify(response.data);
            // console.log(locations.value); // test
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors
            console.log(errors.value); // test
        } // try catch
    } // getLocs

    const uploadToDB = async (inputArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(inputArray); // test
        try {
            let response = await axios.post('/api/inbound/uploadToDB', {
                DB: getDB.data,
                isnArray: JSON.stringify(inputArray)
            });

            mats.value = JSON.stringify(response.data);
            console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve(true);
            });
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors
            console.log(errors.value); // test
        } // try catch

        return new Promise((resolve, reject) => {
            resolve(false);
        });
    }; // uploadToDB

    return {
        mats,
        queryResult,
        locations,
        getMats,
        validateISN,
        getLocs,
        uploadToDB,
    } // return
} // useInboundStockSearch
