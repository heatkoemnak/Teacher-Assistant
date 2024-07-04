import { createStore } from "vuex";
import router from "../router";
import Axios from "../axios";

export default createStore({
  state: {
    user: JSON.parse(localStorage.getItem("user")) || null,
    errorMessage: null,
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    setErrorMessage(state, errorMessage) {
      state.errorMessage = errorMessage;
    },
    clearErrors(state) {
      state.errorMessage = null;
    },
  },
  actions: {
    async register({ commit }, credentials) {
      try {
        const response = await Axios.post("/admins/create", credentials);
        const { user } = response.data;
        commit("setUser", user);
        router.push({ name: "login" });
      } catch (error) {
        if (error.response) {
          switch (error.response.status) {
            case 422:
              commit("setErrorMessage", "Bad request. Field require.");
              break;
            case 401:
              commit("setErrorMessage", "Unauthorized. Please check your credentials.");
              break;
            case 403:
              commit("setErrorMessage", "Forbidden. You don't have permission to perform this action.");
              break;
            case 404:
              commit("setErrorMessage", "Not found. The requested resource was not found.");
              break;
            case 500:
              commit("setErrorMessage", "Internal server error. Please try again later.");
              break;
            default:
              commit("setErrorMessage", error.response.data.message || "An error occurred.");
          }
        } else {
          commit("setErrorMessage", "Network error. Please try again later.");
        }
      }
    },
    async login({ commit }, credentials) {
      try {
        const response = await Axios.post("/login", credentials);
        const { token, user } = response.data;
        console.log(response);
        if (token) {
          Axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
          localStorage.setItem("token", token);
          localStorage.setItem("user", JSON.stringify(user));
        }
        commit("setUser", user);
        if (user.role_id === 1) {
          router.push("/admin/dashboard");
        } else {
          router.push("/lay-teacher");
        }
      } catch (error) {
         if (error.response) {
          switch (error.response.status) {
            case 422:
              commit("setErrorMessage", "Bad request. Please check your input.");
              break;
            case 401:
              commit("setErrorMessage", "Unauthorized. Please check your credentials.");
              break;
            case 403:
              commit("setErrorMessage", "Forbidden. You don't have permission to perform this action.");
              break;
            case 404:
              commit("setErrorMessage", "Not found. The requested resource was not found.");
              break;
            case 500:
              commit("setErrorMessage", "Internal server error. Please try again later.");
              break;
            default:
              commit("setErrorMessage", error.response.data.message || "An error occurred.");
          }
        } else {
          commit("setErrorMessage", "Network error. Please try again later.");
        }
      }
    },
    async logout({ commit }) {
      try {
        await Axios.post("/auth/logout");
        delete Axios.defaults.headers.common["Authorization"];
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        commit("setUser", null);
        router.push({ name: "login" });
      } catch (error) {
        commit("setErrorMessage", "Logout failed.");
      }
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.user,
    errorMessage: (state) => state.errorMessage,
  },
});
