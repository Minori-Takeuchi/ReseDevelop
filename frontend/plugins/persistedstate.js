import createPersistedState from 'vuex-persistedstate'

export default ({ store }) => {
  createPersistedState({
    key: 'share_App',
    storage: window.sessionStorage
  })(store)
}