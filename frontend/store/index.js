
export const state = () => ({
  // ユーザー認証
  user: '',
  loginTime: '',

  // 店舗検索
  areas: [],
  genres: [],

    areaData: '',
    genreData: '',
    shop_nameData: ''
})

export const mutations = {
  // ユーザー認証
  login(state, user) {
    state.user = user
  },
  setLoginTime(state, loginTime) {
    state.loginTime = loginTime
  },
  logout(state) {
    state.user = null
  },
  // 店舗検索
  setAreas(state, areas) {
    state.areas = areas
  },
  setGenres(state, genres) {
    state.genres = genres
  },
  setAreaData(state, areaData) {
    state.areaData = areaData
  },
  setGenreData(state, genreData) {
    state.genreData = genreData
  },
  setShopNameData(state, shop_nameData) {
    state.shop_nameData = shop_nameData
  },
}
// ユーザー認証
export const actions = {
  loginAction({ commit }, user) {
    const LOGIN_TIMEOUT = 3600 * 1000;
    const loginTime = new Date().getTime();
    commit('login', user);
    commit('setLoginTime', loginTime);
    setTimeout(() => {
      const lastLoginTime = this.state.loginTime;
      const currentTime = new Date().getTime();
      if (lastLoginTime && currentTime - lastLoginTime >= LOGIN_TIMEOUT) {
        alert('タイムアウトしました。再度ログインしてください')
        commit('logout'); 
      }
    }, LOGIN_TIMEOUT);
  },
}
