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
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let pickrecordclient = sessionStorage.getItem("pickrecordclient");
        let pickrecordproduction = sessionStorage.getItem("pickrecordproduction");
        let pickrecordsend = sessionStorage.getItem("pickrecordsend");
        let pickrecordisn = sessionStorage.getItem("pickrecordisn");
        let pickrecordcheck = sessionStorage.getItem("pickrecordcheck");
        let pickrecordbegin = sessionStorage.getItem("pickrecordbegin");
        let pickrecordend = sessionStorage.getItem("pickrecordend");

        try {
            const response = await axios.post('/api/outbound/pickrecord', {
                DB: getDB.data,
                pickrecordclient: pickrecordclient,
                pickrecordproduction: pickrecordproduction,
                pickrecordsend: pickrecordsend,
                pickrecordisn: pickrecordisn,
                pickrecordcheck: pickrecordcheck,
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

    return {
        mats,
        getMats
    } // return
} // useOutboundPickRecord
