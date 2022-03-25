<template>
  <div>
    <v-container>
      <h3>Kezdő hónap.: {{ fromMonth }} </h3>
    </v-container>

    <v-simple-table>
      <template v-slot:default>
        <thead>
        <tr>
          <th class="text-left">
            Zseb
          </th>
          <th class="text-left">
            Összeg
          </th>
        </tr>
        </thead>
        <tbody>
        <tr
          v-for="account in accounts"
          :key="account.name"
        >
          <td>{{ getVisibleName(account.name) }}</td>
          <td>{{ account.annualValue | toCurrency }}</td>
        </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>

<script>
import {getMonths, getVisibleName} from '@/helpers/helper'

export default {
  name: 'DataTable',
  props: ['calculateFromMonth', 'accounts'],
  data() {
    return {}
  },
  methods: {
    getVisibleName(name) {
      return getVisibleName(name)
    }
  },
  computed: {
    months() {
      return getMonths()[this.calculateFromMonth]
    },
    fromMonth() {
      return getMonths()[this.calculateFromMonth]
    }
  }
}
</script>
