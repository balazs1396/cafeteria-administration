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

      <v-row class="mt-1">

        <v-col
          cols="4"
          sm="4"
        >
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
        >
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
        >
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
        <v-col class="left">
          <download-csv
            :data="annualDistribution">
            <v-btn color="primary">
              CSV letöltése
            </v-btn>

          </download-csv>
        </v-col>
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
import {getMonths} from '@/helpers/helper'

export default {
  mixins: [validationMixin],
  name: 'Edit',
  data() {
    return {
      errors: {},
      annualBudget: 400000,
      calculateFromMonth: null,
      valid: true,
      annualAccountRules: [
        v => !!v || "A mező kitöltése kötelező",
        v => (v && v >= 0) || "Nem adhat meg negatív értéket",
        v => (v && v <= 200000) || "Nem lehet több mint 200 000",
      ],
      accounts: []
    }
  },
  methods: {
    save() {
      const self = this
      self.$store.commit('SET_IS_LOADING', true);
      // const accounts = this.accounts.filter(account => Number(account.annualValue) > 0)

      CafeteriaRepository.saveCafeteria({
        startMonth: this.months.indexOf(this.calculateFromMonth) + 1,
        accounts: this.accounts
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
    headers() {
      const headers = [{
        text: 'Zseb',
        value: 'account',
        align: 'start'
      }];
      const indexOfFromMonth = this.months.indexOf(this.calculateFromMonth)
      this.months.slice(indexOfFromMonth).forEach(month => {
        headers.push({
          text: month,
          value: month
        })
      })

      return headers
    },
    annualDistribution() {
      this.accounts
      const distributions = []
      const monthsNumber = 12 - this.months.indexOf(this.calculateFromMonth)

      this.accounts.forEach((account) => {
        const costs = {}
        this.headers.forEach(month => {
          costs[month.value] = parseFloat(parseFloat(account.annualValue / monthsNumber).toFixed(2))
        })

        distributions.push({
          ...costs,
          ...{
            account: account.value,
          },
        })

      })

      return distributions
    },
    months() {
      return getMonths()
    }
  },
  mounted: function () {
//TODO seed it from backend
    this.accounts = [
      {
        name: 'accommodation',
        value: 'Szálláshely',
        annualValue: "0"
      },
      {
        name: 'hospitality',
        value: 'Vendéglátás',
        annualValue: "0"
      },
      {
        name: 'leisure',
        value: 'Szabadidő',
        annualValue: "0"
      }
    ]
    const self = this
    self.$store.commit('SET_IS_LOADING', true)
    CafeteriaRepository.getCafeteria()
      .then(response => {
          self.calculateFromMonth = self.months[response.data.start_month - 1]
          response.data.accounts.forEach(account => {
            const index = self.accounts.findIndex(tmpAccount => tmpAccount.name === account.name)
            self.accounts[index].annualValue = String(account.pivot.annual_value)
          })
        }
      )
      .finally(() => {
        self.$store.commit('SET_IS_LOADING', false)
      })
  }
}
</script>
