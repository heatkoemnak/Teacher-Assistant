/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from "@/plugins";

// Components
import App from "./App.vue";
import store from "@/store/index";

// Composables
import { createApp } from "vue";
import apiClient from "./axios";

const token = localStorage.getItem("token");
if (token) {
  apiClient.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}
const app = createApp(App);
app.use(store);

registerPlugins(app);

app.mount("#app");
