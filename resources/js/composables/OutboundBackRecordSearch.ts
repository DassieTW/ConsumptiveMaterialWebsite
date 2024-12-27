import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useOutboundBackRecord() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let backrecordclient = sessionStorage.getItem("backrecordclient");
        let backrecordproduction = sessionStorage.getItem("backrecordproduction");
        let backrecordsend = sessionStorage.getItem("backrecordsend");
        let backrecordisn = sessionStorage.getItem("backrecordisn");
        let backrecordbegin = sessionStorage.getItem("backrecordbegin");
        let backrecordend = sessionStorage.getItem("backrecordend");

        try {
            let response = await axios.post('/api/outbound/backrecord', {
                DB: getDB.data,
                backrecordclient: backrecordclient,
                backrecordproduction: backrecordproduction,
                backrecordsend: backrecordsend,
                backrecordisn: backrecordisn,
                backrecordbegin: backrecordbegin,
                backrecordend: backrecordend
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
} // useOutboundBackRecord
