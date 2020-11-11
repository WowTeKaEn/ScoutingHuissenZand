import Vue from 'vue';
import './plugins/axios';
import App from './App.vue';
import router from './router';
import LoadScript from 'vue-plugin-load-script';
import VuePreview from 'vue-preview'


import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';


require('@/assets/css/all.min.css');
import { formatDate } from "@fullcalendar/core";
import nlLocale from "@fullcalendar/core/locales/nl";


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
  },
  getFormattedDate(start,end) {
    return (
      "Van " +
      formatDate(start, {
        month: "long",
        year: "numeric",
        day: "numeric",
        locale: nlLocale
      }) +
      " tot " +
      formatDate(end, {
        month: "long",
        year: "numeric",
        day: "numeric",
        locale: nlLocale
      })
    );
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
