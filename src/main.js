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
    },
    throwResponse(response,call,callElse){
        if (response.status !== 200) {
          call(response);
          this.$bvToast.toast("Unknown", this.toastObject("Error"));
        }else{
          if(callElse){
            callElse(response)
          }
        }
    },
    throwError(err,call){
        if(call){
          call(err);
        }
        this.$bvToast.toast(err.response.data.message, this.toastObject("Error"));
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
