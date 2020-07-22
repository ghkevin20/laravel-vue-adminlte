import store  from '../store';
import axios from 'axios';

export default {
     authCheck(){
        return axios.post('/api/check').then(response => {
            alert('alert');

            if(response.data.authenticated){
                store.dispatch('authenticate');
                store.dispatch('setUser',response.data.data);
            }else{
                store.dispatch('disprove');
            }
        })
        .catch(error => {
            alert('error');

            store.dispatch('disprove');
            store.dispatch('unsetUser');
        });
    }
}
