import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useUserSearch() {
    const users = ref("");
    const errors = ref("");
    const staffs = ref("");
    const router = useRouter();

    const getUsers = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let user = await axios.post('/getCurrentUser');

        try {
            let response = await axios.post('/api/member/getUsers', {
                DB: getDB.data,
                CurrentUser: user
            });

            // console.log(JSON.stringify(response.data)); // test
            users.value = JSON.stringify(response.data);
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getUsers

    const getStaffs = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // let user = await axios.post('/getCurrentUser');

        try {
            let response = await axios.post('/api/member/getStaffs', {
                DB: getDB.data,
            });

            staffs.value = JSON.stringify(response.data);
            // console.log(JSON.stringify(response.data)); // test
            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } catch (e) {
            console.log(e); // test
            return e;
        } // try catch
    } // getStaffs

    return {
        users,
        staffs,
        getUsers,
        getStaffs
    } // return
} // useConsumptiveMaterials
