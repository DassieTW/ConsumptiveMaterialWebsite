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
    } // getMats

    const getExistingStock = async (isnArray, locArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/inbound/getExistingStock', {
                DB: getDB.data,
                isnArray: JSON.stringify(isnArray),
                locArray: JSON.stringify(locArray),
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
    } // getExistingStock

    const uploadToDB = async (newStock, inboundCount, newInTransit) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');
        let serialNum = username.data + '_' + Date.now();
        try {
            let response = await axios.post('/api/inbound/uploadToDB', {
                DB: getDB.data,
                User: username.data,
                newStock: JSON.stringify(newStock),
                inboundCount: JSON.stringify(inboundCount),
                newInTransit: JSON.stringify(newInTransit),
                serialNum: JSON.stringify(serialNum),
            });

            // console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    }; // uploadToDB

    return {
        mats,
        getMats,
        getExistingStock,
        uploadToDB,
    } // return
} // useInboundStockSearch
