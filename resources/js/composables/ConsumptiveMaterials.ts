import { ref, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
// import { loadingModal } from "../../../public/js/jquery.loadingModal.ts";

export default function useConsumptiveMaterials() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let lookInTargets = sessionStorage.getItem("lookInTargets");
        let lookInType = sessionStorage.getItem("lookInType");
        sessionStorage.removeItem("lookInTargets");
        sessionStorage.removeItem("lookInType");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test
        try {
            axios.interceptors.request.use(function () {
                // do sth before request is sent
                $("body").loadingModal({
                    text: "Loading...",
                    animation: "circle",
                });
            }, function (error) {
                // do sth with request error
                console.log(error); // test
                return Promise.reject(error);
            });

            let response = await axios.post('/api/basic/mats', { DB: getDB.data, LookInTargets: lookInTargets, LookInType: lookInType });

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
