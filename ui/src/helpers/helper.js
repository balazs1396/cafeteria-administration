const months = [
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
]

const accounts = [
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

export function getMonths() {
  return months
}

export function getAccounts() {
  return accounts
}

export function getVisibleName(name) {
  return getAccounts().filter(account => {
    return account.name === name
  })[0].value
}
