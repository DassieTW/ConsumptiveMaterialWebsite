import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useSxbSearch() {
    const mats = ref("");
    const inTransit = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // let sxbclient = sessionStorage.getItem("sxbclient");
        let sxbisn = sessionStorage.getItem("sxbisn");
        let sxbsend = sessionStorage.getItem("sxbsend");
        let sxbsxb = sessionStorage.getItem("sxbsend");
        let sxbcheck = sessionStorage.getItem("sxbcheck");
        let sxbbegin = sessionStorage.getItem("sxbbegin");
        let sxbend = sessionStorage.getItem("sxbend");

        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/month/sxb', {
                DB: getDB.data,
                // sxbclient: sxbclient,
                sxbisn: sxbisn,
                sxbsend: sxbsend,
                sxbsxb: sxbsxb,
                sxbcheck: sxbcheck,
                sxbbegin: sxbbegin,
                sxbend: sxbend,
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
    } // get mats

    const SXB_Reject = async (sxb_number) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/rejectSXB', {
                DB: getDB.data,
                sxb: JSON.stringify(sxb_number),
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // SXB_Reject

    const SXB_Approve = async (sxb_number, isn, amount) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/approveSXB', {
                DB: getDB.data,
                sxb: JSON.stringify(sxb_number),
                isn: JSON.stringify(isn),
                amount: JSON.stringify(amount),
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // SXB_Approve

    const getTransit = async (isn) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/month/getTransit', {
                DB: getDB.data,
                isn: JSON.stringify(isn),
            });

            // console.log(response.data); // test
            inTransit.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getTransit

    return {
        mats,
        inTransit,
        getMats,
        SXB_Reject,
        SXB_Approve,
        getTransit
    } // return
} // useConsumptiveMaterials
