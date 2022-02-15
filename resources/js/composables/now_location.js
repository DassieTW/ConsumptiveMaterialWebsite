import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function NowWeAt() {
    const urls = ref([]); // the names to show on the breadcrumb nav
    const errors = ref('');

    const router = useRouter();

    const getUrl = function () {
        let currentURL = window.location.pathname;
        const tempArr = currentURL.split("/");
        tempArr.shift(); // the first element in arry will be "", so remove it from array
        if (tempArr.length > 0) {
            tempArr[0] = '/' + tempArr[0];
        } // if

        for (let a = 1; a < tempArr.length; a++) {
            tempArr[a] = tempArr[a-1] + '/' + tempArr[a];
        } // for

        console.log(Lang.get("checking.page_name")); // test
        urls.value = tempArr;
    } // getUrl()

    const getPageNames = function () {
        let currentURL = window.location.pathname;
        const tempArr = currentURL.split("/");
        tempArr.shift(); // the first element in arry will be "", so remove it from array
        if( tempArr.length > 0 ) {
            let LangPage = "";
            if( tempArr[0] === "barcode" ) {
                LangPage = "barcodeGenerator";
            } // if
            else if ( tempArr[0] === "basic" ) {
                LangPage = "basicInfoLang";
            } // else if
            else if( tempArr[0] === "bu" ) {
                LangPage = "bupagelang";
            } // else if
            else if ( tempArr[0] === "call" ) {
                LangPage = "callpageLang" ;
            } // else if
            else if ( tempArr[0] === "checking" ) {
                LangPage = "checkInvLang";
            } // else if
            else if ( tempArr[0] === "inbound" ) {
                LangPage = "inboundpageLang";
            } // else if
            else if ( tempArr[0] === "member" ) {
                LangPage = "loginPageLang";
            } // else if
            else if ( tempArr[0] === "month" ) {
                LangPage = "monthlyPRpageLang";
            } // else if
            else if ( tempArr[0] === "obound" ) {
                LangPage = "oboundpageLang";
            } // else if
            else if ( tempArr[0] === "outbound" ) {
                LangPage = "outboundpageLang";
            } // else if
          
            for( let a = 1 ; a < tempArr.length ; a++ ) {
                
            } // for
        } // if

    } // getPageNames()

    return {
        urls,
        getUrl,
        errors
    } // return

} // NowWeAt