import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useOboundStockSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();
    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let oboundclient = sessionStorage.getItem("oboundstockclient");
        let oboundisn = sessionStorage.getItem("oboundstockisn");
        let oboundbound = sessionStorage.getItem("oboundstockbound");
        let oboundnogood = sessionStorage.getItem("oboundstocknogood");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/obound/searchstock', {
                DB: getDB.data,
                oboundclient: oboundclient,
                oboundisn: oboundisn,
                oboundbound:  oboundbound,
                oboundnogood: oboundnogood
            });

            mats.value = JSON.stringify(response.data);
            // console.log( JSON.parse(mats.value)); // test
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
