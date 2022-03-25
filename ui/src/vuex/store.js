import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    isLoading: false,
  },
  getters: {
    isLoading: state => {
      return state.isLoading
    },
  },
  mutations: {
    SET_IS_LOADING(state, value) {
      state.isLoading = value
    },
  },
})
