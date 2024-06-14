// store/index.js
import { createStore } from "vuex";
import { login, logout, getUser,register  } from "../auth";
import router from "../router";
export default createStore({
  state: {
    user: JSON.parse(localStorage.getItem("user")) || null,
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
  },
  actions: {
    async login({ commit }, credentials) {
      const data = await login(credentials);
      commit("setUser", data.user);
      if (data.user.role_id === 1) {
        router.push("/admin/dashboard");
      } else if (data.user.role_id === 2) {
        router.push("/user/dashboard");
      } else {
        router.push("/teacher/dashboard");
      }
    },
    async logout({ commit }) {
      await logout();
      commit("setUser", null);
    },
    async fetchUser({ commit }) {
      const user = await getUser();
      commit("setUser", user);
    },
    async register({ commit }, credentials) {
      const data = await register(credentials);
      commit("setUser", data.user);
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
});
