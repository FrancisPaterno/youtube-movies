import Vue from 'vue';
import Vuex from 'vuex';
import youtube from './module/youtube';

Vue.use(Vuex);

export default new Vuex.Store({modules:{youtube}});