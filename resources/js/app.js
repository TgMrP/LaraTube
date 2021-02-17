require("./bootstrap");

window.Vue = require("vue").default;

Vue.component(
    "subscribe-button",
    require("./components/subscribe-button.vue").default
);

Vue.component(
    "channel-upload",
    require("./components/channel-upload.vue").default
);

const app = new Vue({
    el: "#app"
});
