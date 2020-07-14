import Vue from 'vue';
import './plugins/axios';
import App from './App.vue';
import router from './router';
import LoadScript from 'vue-plugin-load-script';
import VueGallery from 'vue-gallery'




import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';


require('@/assets/css/all.min.css');





Vue.component('VGallery', VueGallery)
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(LoadScript);
Vue.config.productionTip = false;

new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
