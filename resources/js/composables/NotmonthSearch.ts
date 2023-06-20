import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useNotmonthSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let notmonthclient = sessionStorage.getItem("notmonthclient");
        let notmonthisn = sessionStorage.getItem("notmonthisn");

        try {
            let response = await axios.post('/api/month/notmonth', {
                DB: getDB.data,
                notmonthclient: notmonthclient,
                notmonthisn: notmonthisn,
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

    return {
        mats,
        getMats
    } // return
} // useConsumptiveMaterials
