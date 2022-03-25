import Repository from '@/repositories/Base'

export default {

  saveCafeteria(details) {
    return Repository.post('cafeteria', details)
  },

  getCafeteria() {
    return Repository.get('cafeteria')
  }
}
