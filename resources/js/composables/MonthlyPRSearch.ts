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

    const getMats_nonMonthly = async () => {
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
    } // getMats_nonMonthly

    return {
        mats,
        getMats_Monthly,
        getMats_nonMonthly
    } // return
} // useConsumptiveMaterials
