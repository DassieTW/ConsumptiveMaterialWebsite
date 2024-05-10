import {
    reactive,
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useUserSearch() {
    const users = ref("");
    const current_user = ref("");
    const errors = ref("");
    const staffs = ref("");
    const db_list = reactive([]);
    const router = useRouter();

    const getCurrentUser = async () => {
        let user = await axios.post('/getCurrentUser');
        current_user.value = user.data;
        // console.log(current_user.value); // test
    } // getCurrentUser

    const getDBList = async () => {
        let dbs = await axios.post('/getDBList');
        Object.assign(db_list, dbs.data.data);
        db_list.shift(); // remove the default db
    } // getDBList

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
        current_user,
        db_list,
        getUsers,
        getStaffs,
        getCurrentUser,
        getDBList
    } // return
} // useConsumptiveMaterials
