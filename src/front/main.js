import Vue from 'vue';
import PrimeVue from 'primevue/config';
import "primevue/resources/themes/lara-light-indigo/theme.css";
import "primevue/resources/primevue.min.css";
Vue.use(PrimeVue);

import Index from './Index.vue';


new Vue({
    render: h => h(Index),
  }).$mount('#wppool-projects');
