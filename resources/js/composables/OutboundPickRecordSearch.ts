import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useOutboundPickRecord() {
    const mats = ref("");
    const reasons = ref("");
    const lines = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let pickrecordclient = sessionStorage.getItem("pickrecordclient");
        let pickrecordproduction = sessionStorage.getItem("pickrecordproduction");
        let pickrecordsend = sessionStorage.getItem("pickrecordsend");
        let pickrecordisn = sessionStorage.getItem("pickrecordisn");
        let pickrecordbegin = sessionStorage.getItem("pickrecordbegin");
        let pickrecordend = sessionStorage.getItem("pickrecordend");

        try {
            const response = await axios.post('/api/outbound/pickrecord', {
                DB: getDB.data,
                pickrecordclient: pickrecordclient,
                pickrecordproduction: pickrecordproduction,
                pickrecordsend: pickrecordsend,
                pickrecordisn: pickrecordisn,
                pickrecordbegin: pickrecordbegin,
                pickrecordend: pickrecordend
            });

            mats.value = JSON.stringify(response.data);
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(errors.value); // test
            return e;
        } // try catch
    } // get mats

    const getPickReason = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        try {
            let response = await axios.post('/api/outbound/pickreason', {
                DB: getDB.data
            });

            reasons.value = JSON.stringify(response.data);
            // console.log(JSON.parse(reasons.value)); // test
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(errors.value); // test
        } // try catch
    } // getPickReason

    const getLines = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        try {
            let response = await axios.post('/api/outbound/lines', {
                DB: getDB.data
            });

            lines.value = JSON.stringify(response.data);
            // console.log(JSON.parse(lines.value)); // test
        } // try
        catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors
        } // catch
    } // getLine

    const uploadNewPickList = async (newpicklist) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let username = await axios.post('/getCurrentUsername');
        try {
            let response = await axios.post('/api/outbound/addNewPicklist', {
                DB: getDB.data,
                username: username.data,
                AllData: JSON.stringify(newpicklist),
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } // try
        catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // catch
    } // uploadNewPickList

    return {
        mats,
        reasons,
        lines,
        getMats,
        getPickReason,
        getLines,
        uploadNewPickList,
    } // return
} // useOutboundPickRecord
