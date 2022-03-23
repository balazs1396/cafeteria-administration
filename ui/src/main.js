import Vue from 'vue'
import AppContainer from './App'
import router from './router'
import currency from './filters/currency'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'



Vue.use(Vuetify)
Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  render: h => h(AppContainer),
  vuetify: new Vuetify(),
  currency
})
