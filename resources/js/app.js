import './bootstrap';
import {createApp} from "vue/dist/vue.esm-bundler.js";
import Calculator from "./components/Calculator.vue";

const app = createApp();

app.component("Calculator",Calculator)
app.mount("#app");
