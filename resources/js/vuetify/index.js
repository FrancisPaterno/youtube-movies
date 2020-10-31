import Vue from 'vue';
import vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import Vuetify from 'vuetify';

Vue.use(Vuetify);

const options = {
    icons:{iconfont:'md'}
}

export default new Vuetify(options);
