import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useUnitConsumptionSearch() {
    const mats = ref("");
    const mails = ref("");
    const errors = ref("");
    const router = useRouter();

    const getRejected = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/rejectedUC', {
                DB: getDB.data,
            });

            mats.value = JSON.stringify(response.data);
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getMats

    const getCheckersMails = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/checkersMail', {
                DB: getDB.data,
            });

            mails.value = JSON.stringify(response.data);
            // console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getCheckersMails

    const uploadToDB = async (inputArray) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(inputArray); // test
        try {
            let response = await axios.post('/api/month/send_UC_to_DB', {
                DB: getDB.data,
                isnArray: JSON.stringify(inputArray)
            });

            mats.value = JSON.stringify(response.data);
            console.log(JSON.stringify(response.data)); // test
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
        mails,
        getRejected,
        getCheckersMails,
        uploadToDB
    } // return
} // useInboundStockSearch
