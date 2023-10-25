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
    const router = useRouter();

    const validateISN = async (inputArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/validateISN', {
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
            let response = await axios.post('/api/getLocs', {
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

    return {
        queryResult,
        locations,
        validateISN,
        getLocs,
    } // return
} // useCommonlyUsedFunctions
