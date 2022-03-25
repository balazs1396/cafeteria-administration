<template>
  <v-container>
    <div
      v-if="startMonth.length === 0 || accounts.length === 0">
      <v-card
        class="d-flex align-center justify-center pa-4 mx-auto"
        outlined
      >
        <div>
          <h3>Úgy tűnik még nem rendelkezett a cafetériájáról,
            <router-link to="/edit">
              <v-btn
                elevation="2"
                depressed
                color="primary"
              >
                IDE
              </v-btn>
            </router-link>
            kattintva megteheti azt!
          </h3>
        </div>
      </v-card>
    </div>
    <div v-else>
      <data-table
      :calculateFromMonth="startMonth"
      :accounts="accounts"
      >
      </data-table>
    </div>
  </v-container>
</template>

<script>
import CafeteriaRepository from '@/repositories/CafeteriaRepository'
import DataTable from "./DataTable";
export default {
  name: 'Home',
  components: {
    DataTable
  },
  data() {
    return {
      startMonth: '',
      accounts: [],
      gridView: true
    }
  },
  mounted() {
    const self = this
    self.$store.commit('SET_IS_LOADING', true)
    CafeteriaRepository.getCafeteria()
      .then(response => {
          self.startMonth = response.data.start_month - 1
          response.data.accounts.forEach(account => {
            self.accounts.push({
              name: account.name,
              value: 'TESZT',
              annualValue: account.pivot.annual_value
            })
          })
        }
      )
    .finally(() => {
      self.$store.commit('SET_IS_LOADING', false)
    })
  }
}
</script>
