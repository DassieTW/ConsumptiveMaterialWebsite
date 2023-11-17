import { ref, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useConsumptiveMaterials() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post("/getCurrentDB");
        let lookInTargets = sessionStorage.getItem("lookInTargets");
        let lookInType = sessionStorage.getItem("lookInType");
        let lookInSend = sessionStorage.getItem("lookInSend");

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

    return {
        mats,
        getMats,
        deletePN,
    }; // return
} // useConsumptiveMaterials
