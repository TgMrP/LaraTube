require("./bootstrap");

window.Vue = require("vue").default;

Vue.config.ignoredElements = ["video-js"];

Vue.component(
    "subscribe-button",
    require("./components/subscribe-button.vue").default
);

Vue.component(
    "channel-upload",
    require("./components/channel-upload.vue").default
);

Vue.component("votes", require("./components/votes.vue").default);

Vue.component("comments", require("./components/comments.vue").default);
Vue.component("avatar", require("vue-avatar").default);

const app = new Vue({
    el: "#app"
});
