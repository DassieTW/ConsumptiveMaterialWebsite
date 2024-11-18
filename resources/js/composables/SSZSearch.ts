import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useSSZSearch() {
    const mats_SSZ = ref("");
    const mats_SSZInfo = ref("");
    const errors = ref("");
    const router = useRouter();

    const getSSZ = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/inbound/ssz', {
                DB: getDB.data,
            });

            mats_SSZ.value = JSON.stringify(response.data);
            //console.log(JSON.parse(mats_SSZ.value)); // test
        } catch (e) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(e); // test
        } // try catch
    } // get mats_SSZ

    const getSSZ_info = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(getDB.data); // test
        try {
            let response = await axios.post('/api/inbound/ssz_info', {
                DB: getDB.data,
            });

            mats_SSZInfo.value = JSON.stringify(response.data);
            //console.log(JSON.parse(mats_SSZInfo.value)); // test
        } catch (e) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(e); // test
        } // try catch
    } // get getSSZ_info

    const claim_a_mat = async (ssz_number, newStock, inbound_records) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');

        try {
            let response = await axios.post('/api/inbound/claimSSZ', {
                DB: getDB.data,
                username: username.data,
                ssz: JSON.stringify(ssz_number),
                newStock: JSON.stringify(newStock),
                inboundRecords: JSON.stringify(inbound_records)
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
    } // claim_a_mat

    const updateBasicInfo = async (ISN, PName, spec, price, unit, currency, mpq, moq, lt, gradea, monthly, dispatcher, safe) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/basic/update_pn_fromSSZ', {
                DB: getDB.data,
                pn: JSON.stringify(ISN),
                name: JSON.stringify(PName),
                spec: JSON.stringify(spec),
                price: JSON.stringify(price),
                unit: JSON.stringify(unit),
                currency: JSON.stringify(currency),
                mpq: JSON.stringify(mpq),
                moq: JSON.stringify(moq),
                lt: JSON.stringify(lt),
                gradea: JSON.stringify(gradea),
                monthly: JSON.stringify(monthly),
                dispatcher: JSON.stringify(dispatcher),
                safe: JSON.stringify(safe),
            });

            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // try catch
    } // updateBasicInfo

    return {
        mats_SSZ,
        mats_SSZInfo,
        getSSZ,
        getSSZ_info,
        claim_a_mat,
        updateBasicInfo,
    } // return
} // useSSZSearch
