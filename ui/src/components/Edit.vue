<template>
  <v-container>
    <v-form
      ref="form"
      v-model="valid"
      lazy-validation
    >
      <v-row>
        <v-col
          cols="6"
        >
          <v-alert
            dense
            type="info"
            color="grey"
          >
            Éves keret: {{ annualBudget | toCurrency }}

            <br>
            Aktuális évben felhasználható: {{ amountCanBeUsedActualYear | toCurrency }}
          </v-alert>
        </v-col>
        <v-col
          cols="6"
        >
          <v-alert
            dense
            :type="savingIsPossible ? 'success' : (amountCanBeUsedActualYear - amountAlreadyUsedInActualYear) > 0 ? 'warning' : 'error'"
          >
            Felhasznált: {{ amountAlreadyUsedInActualYear | toCurrency }}
            <br>
            <span v-if="remainingAnnualAmount >= 0">
            Fennmaradó összeg: {{ Math.abs(amountCanBeUsedActualYear - amountAlreadyUsedInActualYear) | toCurrency }}
          </span>
            <span v-else>
            Túllépte az éves keretét ekkora összegel: {{ Math.abs(amountCanBeUsedActualYear - amountAlreadyUsedInActualYear) | toCurrency }}
          </span>
          </v-alert>
        </v-col>
      </v-row>

      <v-select
        :items="months"
        v-model="calculateFromMonth"
        label="Kezdő hónap"
        class="mt-2"
        outlined
      ></v-select>

      <v-select
        v-model="selectedAccounts"
        :items="accounts"
        item-text="value"
        item-value="name"
        label="Cafeteria zsebek"
        hint="Kérjük válassza ki milyen alzsebekbe kívánja eloszta az éves keretösszegét"
        return-object
        multiple
        chips
        outlined
        persistent-hint
      ></v-select>
      <v-row class="mt-1">

        <v-col
          cols="4"
          sm="4"
          v-show="selectedAccounts[selectedAccounts.findIndex(account => { return account.name === 'accommodation'})]">
          <v-text-field
            v-model="accounts[accounts.findIndex(account => {return account.name === 'accommodation'})].annualValue"
            :rules="annualAccountRules"
            label="Szálláshely éves keret"
            type="number"
            outlined
          ></v-text-field>
        </v-col>

        <v-col
          cols="4"
          sm="4"
          v-show="selectedAccounts[selectedAccounts.findIndex(account => { return account.name === 'hospitality'})]">
          <v-text-field
            v-model="accounts[accounts.findIndex(account => {return account.name === 'hospitality'})].annualValue"
            :rules="annualAccountRules"
            label="Vendéglátás éves keret"
            type="number"
            outlined
          ></v-text-field>
        </v-col>

        <v-col
          cols="4"
          sm="4"
          v-show="selectedAccounts[selectedAccounts.findIndex(account => { return account.name === 'leisure'})]">
          <v-text-field
            v-model="accounts[accounts.findIndex(account => {return account.name === 'leisure'})].annualValue"
            :rules="annualAccountRules"
            label="Szabadidő éves keret"
            type="number"
            outlined
          ></v-text-field>
        </v-col>

      </v-row>

      <v-data-table
        dense
        :headers="headers"
        :items="annualDistribution"
        item-key="name"
        class="elevation-1"
        hide-default-footer
      ></v-data-table>

      <v-row class="mt-5">
        <v-col class="text-right">
          <v-btn
            color="primary"
            :disabled="!savingIsPossible"
            @click="save"
          >
            Mentés
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
import {validationMixin} from 'vuelidate'
import CafeteriaRepository from '@/repositories/CafeteriaRepository'
import {getMonths, getAccounts} from '@/helpers/helper'

export default {
  mixins: [validationMixin],
  name: 'Edit',
  data() {
    return {
      errors: {},
      annualBudget: 400000,
      calculateFromMonth: '',
      selectedAccounts: [],
      annualDistribution: [],
      defaultTableHeader: [{
        text: 'Zseb',
        value: 'account',
        align: 'start'
      }],
      headers: [],
      valid: true,
      annualAccountRules: [
        v => !!v || "A mező kitöltése kötelező",
        v => (v && v >= 0) || "Nem adhat meg negatív értéket",
        v => (v && v <= 200000) || "Nem lehet több mint 200 000",
      ]
    }
  },
  methods: {
    recalculateDistribution() {
      this.annualDistribution = []
      this.headers = [...this.defaultTableHeader]

      if (!this.$refs.form.validate()) {
        return
      }

      this.selectedAccounts.forEach((account) => {
        const distribution = {
          name: account.name,
          account: account.value,
        }

        const indexOfFromMonth = this.months.indexOf(this.calculateFromMonth)
        this.months.forEach((month, index) => {
          if (index < indexOfFromMonth) return

          //calculate headers
          if (this.headers.filter(e => e.text === month).length === 0) {
            this.headers.push({
              text: month,
              value: month
            })
          }

          // calculate distributions
          distribution[month] = 0

        })
        this.annualDistribution.push(distribution)
      })
      this.calculateMonthlyDistribution()
    },
    calculateMonthlyDistribution() {
      this.annualDistribution.forEach((distributionRow, distributedIndex) => {
        const distributedAnnualValue = this.selectedAccounts[this.selectedAccounts.findIndex(account => {
          return account.name === distributionRow.name
        })].annualValue

        const monthsNumber = 12 - this.months.indexOf(this.calculateFromMonth)

        for (const [key, value] of Object.entries(distributionRow)) {
          if (this.months.includes(key)) {
            this.annualDistribution[distributedIndex][key] = parseFloat(parseFloat(distributedAnnualValue / monthsNumber).toFixed(2))
          }
        }

      })
    },
    save() {
      const self = this
      self.$store.commit('SET_IS_LOADING', true);
      const accounts = this.accounts.filter(account => Number(account.annualValue) > 0)

      CafeteriaRepository.saveCafeteria({
        startMonth: this.months.indexOf(this.calculateFromMonth) + 1,
        accounts: accounts
      })
        .then(() => {
          self.errors = {}
          this.$router.push({name: 'home'})
        })
        .catch(error => {
          if (error.response.status === 422) {
            self.errors = error.response.data.errors
          } else {
            alert('some error occurred, sorry');
          }
          console.log(error)
        })
        .finally(() => {
          self.$store.commit('SET_IS_LOADING', false);
        })
    }
  },
  watch: {
    selectedAccounts: {
      handler: function () {
        this.recalculateDistribution()
      },
      deep: true
    },
    calculateFromMonth() {
      this.recalculateDistribution()
    },
  },
  computed: {
    amountCanBeUsedActualYear() {
      return this.annualBudget / 12 * (12 - this.months.indexOf(this.calculateFromMonth))
    },
    amountAlreadyUsedInActualYear() {
      return this.accounts.reduce((sum, account) => {
        return sum += Number(account.annualValue)
      }, 0)
    },
    remainingAnnualAmount() {
      return parseFloat(parseFloat(this.amountCanBeUsedActualYear - this.amountAlreadyUsedInActualYear).toFixed(2))
    },
    savingIsPossible() {
      return Math.round(this.remainingAnnualAmount) === 0 && this.$refs.form.validate()
    },
    months() {
      return getMonths()
    },
    accounts() {
      return getAccounts()
    }
  },
  mounted: function () {
    this.calculateFromMonth = this.months[0]
  }
}
</script>
