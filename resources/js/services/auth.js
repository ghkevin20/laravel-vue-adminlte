import store from '../store';
import axios from 'axios';

export default {
    clear(){
        document.cookie = "XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

        store.dispatch('disprove');
        store.dispatch('unsetUser');
    },
    logout() {
        return axios.post('/api/logout')
            .then(response => {
                if(response.status === 200){
                    this.clear();
                }else{
                    console.log(response);
                }
            })
            .catch(error => {
                console.log(error);
            })
    },
    check() {
        return axios.post('/api/check')
            .then(response => {
                // alert('alert');
                if (response.data.authenticated) {
                    store.dispatch('authenticate');
                    store.dispatch('setUser', response.data.data.user);
                    store.dispatch('setRoles', response.data.data.roles);
                    store.dispatch('setPermissions', response.data.data.permissions);
                } else {
                    this.clear();
                }
            })
            .catch(error => {
                this.clear();
            });
    }
}
