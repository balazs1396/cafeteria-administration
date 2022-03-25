import Vue from 'vue'

Vue.filter('toCurrency', function (value) {
  if (typeof value !== "number") {
    return value;
  }
  const formatter = new Intl.NumberFormat('hu-HU', {
    style: 'currency',
    currency: 'HUF'
  });
  return formatter.format(value);
});
export default {}
