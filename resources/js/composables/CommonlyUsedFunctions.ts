import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useCommonlyUsedFunctions() {
    const queryResult = ref("");
    const locations = ref("");
    const errors = ref("");
    const manualResult = ref("");
    const router = useRouter();

    // for uploaded excel
    const validateISN = async (inputArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/validateISN', {
                DB: getDB.data,
                isnArray: JSON.stringify(inputArray)
            });

            queryResult.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // validateISN

    // for user type-in
    const validateISN_manual = async (inputArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/validateISN', {
                DB: getDB.data,
                isnArray: JSON.stringify(inputArray)
            });

            manualResult.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // validateISN_manual

    const getLocs = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/getLocs', {
                DB: getDB.data
            });

            locations.value = JSON.stringify(response.data);
            // console.log(locations.value); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getLocs

    return {
        queryResult,
        manualResult,
        locations,
        validateISN,
        validateISN_manual,
        getLocs,
    } // return
} // useCommonlyUsedFunctions
