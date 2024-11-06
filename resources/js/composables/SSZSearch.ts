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

    const claim_a_mat = async (ssz_number, ) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/inbound/rejectSSZ', {
                DB: getDB.data,
                ssz: JSON.stringify(ssz_number),
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(e); // test
        } // try catch
    } // claim_a_mat

    return {
        mats_SSZ,
        mats_SSZInfo,
        getSSZ,
        getSSZ_info,
        claim_a_mat,
    } // return
} // useConsumptiveMaterials
