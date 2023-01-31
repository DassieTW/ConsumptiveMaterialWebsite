import { ref, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useConsumptiveMaterials() {
    const news = ref("");
    const errors = ref("");
    const router = useRouter();

    const getNews = async () => {
        errors.value = "";
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test
        axios.interceptors.request.use(
            function (config) {
                // do sth before request is sent
                return config; // this config does nothing for us atm
            },
            function (error) {
                // do sth with request error
                console.log(error); // test
                return Promise.reject(error);
            }
        );

        try {
            let response = await axios.post("/api/news", {
                DB: await axios.post("/getCurrentDB")
            });

            news.value = JSON.stringify(response.data);
            // console.log(response.data); // test
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + "  ";
            } // for each errors

            console.log(errors.value); // test
        } // try catch
    }; // get mats

    return {
        news,
        getNews,
    }; // return
} // useConsumptiveMaterials
