import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useInboundListSearch() {
    const mats = ref("");
    const router = useRouter();
    const getMats = async () => {
        let getDB = await axios.post('/getCurrentDB');
        let inboundclient = sessionStorage.getItem("inboundclient");
        let inboundisn = sessionStorage.getItem("inboundisn");
        let inboundlist = sessionStorage.getItem("inboundlist");
        let inboundcheck = sessionStorage.getItem("inboundcheck");
        let inboundbegin = sessionStorage.getItem("inboundbegin");
        let inboundend = sessionStorage.getItem("inboundend");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/inbound/search', {
                DB: getDB.data,
                inboundclient: inboundclient,
                inboundisn: inboundisn,
                inboundlist: inboundlist,
                inboundcheck: inboundcheck,
                inboundbegin: inboundbegin,
                inboundend: inboundend
            });

            mats.value = JSON.stringify(response.data);
            // console.log( JSON.parse(mats.value)); // test
        } catch (e) {
            console.log(e); // test
        } // try catch
    } // get mats

    const deleteRows = async (inputArray) => {
        let getDB = await axios.post('/getCurrentDB');
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/inbound/delete', {
                DB: getDB.data,
                list: JSON.stringify(inputArray.list),
                isn: JSON.stringify(inputArray.isn),
                amount: JSON.stringify(inputArray.amount),
                position: JSON.stringify(inputArray.position),
                inpeople: JSON.stringify(inputArray.inpeople),
                inreason: JSON.stringify(inputArray.inreason),
                inboundtime: JSON.stringify(inputArray.intime),
            });

            // console.log(response); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            // console.log(e); // test
            return e;
        } // try catch
    } // deleteRows

    return {
        mats,
        getMats,
        deleteRows
    } // return
} // useConsumptiveMaterials
