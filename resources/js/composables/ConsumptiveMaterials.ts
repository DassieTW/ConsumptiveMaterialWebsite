import { ref, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useConsumptiveMaterials() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test
        try {
            let response = await axios.post('/api/basic/mats', {DB: getDB.data});
            mats.value =JSON.stringify(response.data);
            // console.log( JSON.parse(mats.value)); // test
        } catch (e) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors
        } // try catch
    } // get mats

    return {
        mats,
        getMats
    }
} // useConsumptiveMaterials
