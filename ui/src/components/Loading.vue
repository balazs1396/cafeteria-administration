<template>
  <div class="vld-parent">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             color="#1976d2"
             :width="100"
             :height="100"
             :is-full-page="true">

    </loading>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  components: {
    Loading
  },
  computed: {
    isLoading() {
      return this.$store.getters.isLoading
    }
  }
};
</script>
