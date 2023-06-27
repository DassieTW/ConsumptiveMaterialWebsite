import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useOboundPickRecord() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');

        let oboundpickrecordclient = sessionStorage.getItem("oboundpickrecordclient");
        let oboundpickrecordproduction = sessionStorage.getItem("oboundpickrecordproduction");
        let oboundpickrecordisn = sessionStorage.getItem("oboundpickrecordisn");
        let oboundpickrecordcheck = sessionStorage.getItem("oboundpickrecordcheck");
        let oboundpickrecordbegin = sessionStorage.getItem("oboundpickrecordbegin");
        let oboundpickrecordend = sessionStorage.getItem("oboundpickrecordend");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/obound/picklistsearch', {
                DB: getDB.data,
                oboundpickrecordclient: oboundpickrecordclient,
                oboundpickrecordproduction: oboundpickrecordproduction,
                oboundpickrecordisn: oboundpickrecordisn,
                oboundpickrecordcheck: oboundpickrecordcheck,
                oboundpickrecordbegin: oboundpickrecordbegin,
                oboundpickrecordend: oboundpickrecordend
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
} // useOutboundPickRecord
