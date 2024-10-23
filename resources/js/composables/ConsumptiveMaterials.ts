import { ref, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useConsumptiveMaterials() {
    const mats = ref("");
    const queryResult = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post("/getCurrentDB");
        let lookInTargets = sessionStorage.getItem("lookInTargets");
        let lookInType = sessionStorage.getItem("lookInType");
        let lookInSend = sessionStorage.getItem("lookInSend");

        if (lookInTargets === null || lookInType === null) {
            sessionStorage.setItem("lookInType", JSON.stringify("1"));
            sessionStorage.setItem("lookInTargets", JSON.stringify(""));
            sessionStorage.setItem("lookInSend", JSON.stringify(null));
        } // if

        lookInTargets = sessionStorage.getItem("lookInTargets");
        lookInType = sessionStorage.getItem("lookInType");
        lookInSend = sessionStorage.getItem("lookInSend");

        try {
            let response = await axios.post("/api/basic/mats", {
                DB: getDB.data,
                LookInTargets: lookInTargets,
                LookInType: lookInType,
                LookInSend: lookInSend,
            });

            mats.value = JSON.stringify(response.data);
            // console.log( JSON.parse(mats.value)); // test
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + "  ";
            } // for each errors

            console.log(errors.value); // test
        } // try catch
    }; // get mats

    const getDispatchers = async () => {
        errors.value = "";
        let getDB = await axios.post("/getCurrentDB");

        try {
            let response = await axios.post("/api/basic/get_dispatcher", {
                DB: getDB.data,
            });

            queryResult.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    }; // getDispatchers

    const deletePN = async (ISN) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/basic/delete_pn', {
                DB: getDB.data,
                pn: JSON.stringify(ISN)
            });

            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // deletePN

    const uploadToDB = async (ISN, PName, spec, price, unit, currency, mpq, moq, lt, gradea, monthly, dispatcher, safe) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/basic/update_pn', {
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

            queryResult.value = JSON.stringify(response.data);
            console.log(response.data); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // uploadToDB

    return {
        mats,
        queryResult,
        getMats,
        getDispatchers,
        deletePN,
        uploadToDB,
    }; // return
} // useConsumptiveMaterials
