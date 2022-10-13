import axios from "axios";
import { testing } from "laravel-mix/src/Log";

export default {

    CheckByCode(code) {
        // console.log('error code', error)
        if (code == 401) {
            // Check Auth
            Swal.fire({
                title: "Session Time Out!",
                text: "Please, Log in agian!",
                type: "error"
            }).then(function() {
                window.location.href = "/login";
            });
        } else if (code == 500) {
             // Check Auth
            Swal.fire({
                title: "Somthing going wrong !",
                text: "Please, try agian later!",
                type: "warning"
            }).then(function() {
                window.location.href = "/";
            });
        }
    },

    testing(val) {
        console.log('auth function ok'+val)
    }

    


    //  LOGGEDIN() { 
    //     axios.interceptors.response.use(function (response) {
    //         // Any status code that lie within the range of 2xx cause this function to trigger
    //         // Do something with response data
    //         return response;
    //         }, function (error) {
    //         // Any status codes that falls outside the range of 2xx cause this function to trigger
    //         // Do something with response error
    //         if(error.response.status === 403) {
    //             // redirect to login page
    //             window.location.href = "/login";
    //         }
    //         return Promise.reject(error);
    //     })
    //     // return 'Auth test ok'
    // },




    // LOGGEDIN() { 
    //     axios.get('/auth_check').then(response => {
    //         //console.log('auth-response: ', response.data)
    //         if (!response.data) {
    //             window.location.href = "/"
    //         }
    //     }).catch(error => {
    //         console.log(error)
    //     })
    //     // return 'Auth test ok'
    // },

    // LOGGEDIN() { 
    //     setTimeout(() => {
    //         axios.get('/auth_check').then(response => {
    //             //console.log('auth-response: ', response.data)
    //             if (!response.data) {
    //                 window.location.href = "/"
    //             }

    //         }).catch(error => {
    //             console.log(error)
    //         })
            
    //     }, 5000);
    //     // return 'Auth test ok'
    // },

    

}



