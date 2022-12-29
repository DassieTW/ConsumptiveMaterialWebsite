import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useOboundISNSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();
    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let oboundisn = sessionStorage.getItem("oboundisn");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test
        axios.interceptors.request.use(function (config) {
            // do sth before request is sent
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return config; // this config does nothing for us atm
        }, function (error) {
            // do sth with request error
            console.log(error); // test
            return Promise.reject(error);
        });

        try {
            let response = await axios.post('/api/obound/isnsearch', {
                DB: getDB.data,
                oboundisn: oboundisn,
            });

            $('body').loadingModal('hide');
            $('body').loadingModal('destroy');
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
