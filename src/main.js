import Vue from 'vue';
import './plugins/axios';
import App from './App.vue';
import router from './router';
import LoadScript from 'vue-plugin-load-script';
import VuePreview from 'vue-preview'


import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';


require('@/assets/css/all.min.css');



Vue.mixin({
  methods: {
    toastObject: str => {
      return {
        title: str,
      autoHideDelay: 5000,
      appendToast: true
      }
    }
  }
})

Vue.use(VuePreview)
Vue.use(LoadScript);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.config.productionTip = false;

export default new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
