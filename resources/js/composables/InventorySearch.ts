import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useInventorySearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getInventory = async () => {
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
    } // getInventory

    return {
        mats,
        getInventory
    } // return
} // useConsumptiveMaterials
