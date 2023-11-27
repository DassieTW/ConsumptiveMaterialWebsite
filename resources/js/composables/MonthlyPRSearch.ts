import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useMonthlyPRSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats_MPS = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(inputArray); // test
        try {
            let response = await axios.post('/api/month/mps', {
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
    } // getMats_MPS

    const deleteMPS = async (ninetyISN, ISN) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(inputArray); // test
        try {
            let response = await axios.post('/api/month/delete_mps', {
                DB: getDB.data,
                isn90: JSON.stringify(ninetyISN),
                isn: JSON.stringify(ISN)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // deleteMPS

    const deleteNonMPS = async (ISN) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(inputArray); // test
        try {
            let response = await axios.post('/api/month/delete_nonmps', {
                DB: getDB.data,
                number: JSON.stringify(ISN)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // deleteNonMPS

    const getMats_nonMonthly = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let notmonthclient = sessionStorage.getItem("notmonthclient");
        let notmonthisn = sessionStorage.getItem("notmonthisn");

        try {
            let response = await axios.post('/api/month/notmonth', {
                DB: getDB.data,
            });

            // console.log(response.data); // test
            mats.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(errors.value); // test
        } // try catch
    } // getMats_nonMonthly

    const uploadMonthlyToDB = async (number, number90, nextmps, nextday, nowmps, nowday) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/submit_monthlypr', {
                DB: getDB.data,
                number: JSON.stringify(number),
                number90: JSON.stringify(number90),
                nextmps: JSON.stringify(nextmps),
                nextday: JSON.stringify(nextday),
                nowmps: JSON.stringify(nowmps),
                nowday: JSON.stringify(nowday)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // uploadMonthlyToDB

    const uploadNonMonthlyToDB = async (number, thisdemand, nextdemand, amount, desc) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/submit_nonmonthlypr', {
                DB: getDB.data,
                number: JSON.stringify(number),
                thisdemand: JSON.stringify(thisdemand),
                nextdemand: JSON.stringify(nextdemand),
                amount: JSON.stringify(amount),
                desc: JSON.stringify(desc),
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // uploadNonMonthlyToDB

    const getMats_Buylist = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // console.log(inputArray); // test
        try {
            let response = await axios.post('/api/month/generate_buylist', {
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
    } // getMats_Buylist

    return {
        mats,
        getMats_MPS,
        deleteMPS,
        getMats_nonMonthly,
        deleteNonMPS,
        uploadMonthlyToDB,
        uploadNonMonthlyToDB,
        getMats_Buylist
    } // return
} // useConsumptiveMaterials
