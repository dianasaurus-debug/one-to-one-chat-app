
require('./bootstrap');
window.Vue = require('vue');

import axios from 'axios';

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)
Vue.component('chat-component', require('./components/ChatComponent.vue').default);


const app = new Vue({
    el: '#app',
});
