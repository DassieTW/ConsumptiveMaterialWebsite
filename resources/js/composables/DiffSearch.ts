import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useDiffSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async (year = new Date().getFullYear()) => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        try {
            let response = await axios.post('/api/call/getYearlyDiff', {
                DB: getDB.data,
                Year: year,
            });

            mats.value = JSON.stringify(response.data);
            // console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            return new Promise((resolve, reject) => {
                reject(e.response);
            });
        } // try catch
    } // get mats

    return {
        mats,
        getMats
    } // return
} // useConsumptiveMaterials
