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

    const getMats_Monthly = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let month90isn = sessionStorage.getItem("month90isn");
        let monthisn = sessionStorage.getItem("monthisn");

        try {
            let response = await axios.post('/api/month/month', {
                DB: getDB.data,
                number: month90isn,
                number90: monthisn,
            });

            mats.value = JSON.stringify(response.data);
            //console.log(JSON.parse(mats.value)); // test
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(errors.value); // test
        } // try catch
    } // getMats_Monthly

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

    const getMats_nonMonthly = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let notmonthclient = sessionStorage.getItem("notmonthclient");
        let notmonthisn = sessionStorage.getItem("notmonthisn");

        try {
            let response = await axios.post('/api/month/notmonth', {
                DB: getDB.data,
                // notmonthclient: notmonthclient,
                notmonthisn: notmonthisn,
            });

            console.log(response.data); // test
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

    return {
        mats,
        getMats_Monthly,
        getMats_MPS,
        deleteMPS,
        getMats_nonMonthly,
        uploadMonthlyToDB
    } // return
} // useConsumptiveMaterials
