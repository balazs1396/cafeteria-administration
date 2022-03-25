import axios from 'axios'

const apiUrl = process.env.API_URL

axios.defaults.baseURL = 'http://localhost:8081/api/'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.post['Accept'] = 'application/json'
axios.defaults.headers.get['Accept'] = 'application/json'

export default axios
