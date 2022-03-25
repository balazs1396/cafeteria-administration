import Vue from 'vue'
import AppContainer from './App'
import router from './router'
import currency from './filters/currency'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import store from './vuex/store'
import JsonCSV from 'vue-json-csv'

Vue.component('downloadCsv', JsonCSV)
Vue.use(Vuetify)
Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  render: h => h(AppContainer),
  vuetify: new Vuetify(),
  currency,
  store
})
