<template>
  <v-container>
    <h4>Éves keret: {{ annualBudget | toCurrency }}</h4>
    <h4>Felhasználható: {{ annualBudget / 12 * (12 - months.indexOf(calculateFromMonth)) | toCurrency }}</h4>

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
        v-if="selectedAccounts[selectedAccounts.findIndex(account => { return account.name === 'accommodation'})]">
        <v-text-field
          v-model="accounts[accounts.findIndex(account => {return account.name === 'accommodation'})].maxValue"
          label="Szálláshely éves keret"
          type="number"
          outlined
        ></v-text-field>
      </v-col>

      <v-col
        cols="4"
        sm="4"
        v-if="selectedAccounts[selectedAccounts.findIndex(account => { return account.name === 'hospitality'})]">
        <v-text-field
          v-model="accounts[accounts.findIndex(account => {return account.name === 'hospitality'})].maxValue"
          label="Vendéglátás éves keret"
          type="number"
          outlined
        ></v-text-field>
      </v-col>

      <v-col
        cols="4"
        sm="4"
        v-if="selectedAccounts[selectedAccounts.findIndex(account => { return account.name === 'leisure'})]">
        <v-text-field
          v-model="accounts[accounts.findIndex(account => {return account.name === 'leisure'})].maxValue"
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
  </v-container>
</template>

<script>
export default {
  name: 'Edit',
  data() {
    return {
      annualBudget: 400000,
      months: [
        'Január',
        'Február',
        'Március',
        'Április',
        'Május',
        'Június',
        'Július',
        'Augusztus',
        'Szeptember',
        'Október',
        'November',
        'December'
      ],
      calculateFromMonth: '',
      selectedAccounts: [],
      accounts: [
        {
          name: 'accommodation',
          value: 'Szálláshely',
          maxValue: 0
        },
        {
          name: 'hospitality',
          value: 'Vendéglátás',
          maxValue: 0
        },
        {
          name: 'leisure',
          value: 'Szabadidő',
          maxValue: 0
        },
      ],
      annualDistribution: [],
      defaultTableHeader: [{
        text: 'Zseb',
        value: 'account',
        align: 'start'
      }],
      headers: [],
    }
  },
  methods: {
    recalculateDistribution() {
      this.annualDistribution = []
      this.headers = [...this.defaultTableHeader]

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
        const distributedMaxValue = this.selectedAccounts[this.selectedAccounts.findIndex(account => {
          return account.name === distributionRow.name
        })].maxValue

        const monthsNumber = 12 - this.months.indexOf(this.calculateFromMonth)

        for (const [key, value] of Object.entries(distributionRow)) {
          if (this.months.includes(key)) {
            // this.annualDistribution[distributedIndex][key] = Number.parseFloat(distributedMaxValue / monthsNumber).toFixed(2)
            this.annualDistribution[distributedIndex][key] = Math.round(distributedMaxValue / monthsNumber)
          }
        }

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
  mounted: function () {
    this.calculateFromMonth = this.months[0]
  }
}
</script>
