import Vue from 'vue';
import './plugins/axios';
import App from './App.vue';
import router from './router';
import LoadScript from 'vue-plugin-load-script';
import AirbnbStyleDatepicker from 'vue-airbnb-style-datepicker'
import 'vue-airbnb-style-datepicker/dist/vue-airbnb-style-datepicker.min.css'


require('@/assets/css/smoothproducts.css');
require('@/assets/css/all.min.css');
require('@/assets/bootstrap/css/bootstrap.min.css');
require('bootstrap-vue/dist/bootstrap-vue.css');

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(LoadScript);
Vue.use(AirbnbStyleDatepicker, {
  days:[
    "Maandag",
    "Dinsdag",
    "Woensdag",
    "Donderdag",
    "Vrijdag",
    "Zaterdag",
    "Zondag"
  ],
  daysShort:[
    "ma", "di", "wo", "do", "vr", "za", "zo"
  ],
  monthNames:[
    "Januari",
    "Februari",
    "Maart",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Augustus",
    "September",
    "Oktober",
    "November",
    "December"
  ],
  texts: {
    apply: 'Oke',
    cancel: 'Annuleren',
    keyboardShortcuts: 'Keyboard Shortcuts',
  },
  colors: {
    selected: '#3eb058',
    inRange: '#8BCF9B',
    selectedText: '#fff',
    text: '#3eb058',
    inRangeBorder: '#3EB059',
    disabled: '#fff',
    hoveredInRange: '#77C78A'
  },




});
Vue.config.productionTip = false;

new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
