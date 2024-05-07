import { createStore } from "vuex";
import adminStore from "./admin";
import userStore from "./user";

const store = createStore({
  modules: {
    admin: adminStore,
    user: userStore,
  },
});

export default store;
