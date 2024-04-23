require("./bootstrap");

import { createApp } from "vue";
import PolarisVue from "@ownego/polaris-vue";
import "@ownego/polaris-vue/dist/style.css";
import router from "./router";
import App from "./components/App.vue";

const app = createApp(App);
app.use(PolarisVue);
app.use(router);
app.mount("#app");
