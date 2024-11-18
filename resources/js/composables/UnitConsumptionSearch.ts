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
    const recordCount = ref(""); // test
    const router = useRouter();

    const getAll = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/allUC', {
                DB: getDB.data,
            });

            mats.value = JSON.stringify(response.data);
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getAll

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
    } // getRejected

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

    const uploadToDB = async (pnArray, pn90Array, ucArray, email) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');
        // console.log(username); // test
        try {
            let response = await axios.post('/api/month/send_UC_to_DB', {
                DB: getDB.data,
                username: username.data,
                number: JSON.stringify(pnArray),
                number90: JSON.stringify(pn90Array),
                consume: JSON.stringify(ucArray),
                email: email,
            });

            recordCount.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    }; // uploadToDB

    const deleteUC = async (pnArray, pn90Array) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        try {
            let response = await axios.post('/api/month/deleteUC', {
                DB: getDB.data,
                number: JSON.stringify(pnArray),
                number90: JSON.stringify(pn90Array),
            });

            recordCount.value = JSON.stringify(response.data);
            console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    }; // deleteUC

    return {
        mats,
        mails,
        recordCount,
        getAll,
        getRejected,
        getCheckersMails,
        uploadToDB,
        deleteUC
    } // return
} // useUnitConsumptionSearch
