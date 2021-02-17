<template>
    <button
        type="button"
        class="btn"
        :class="{
            'btn-danger': subscribed,
            'btn-success': !subscribed,
            'btn-info': owner
        }"
        @click="toggleSubscription()"
    >
        {{ owner ? "Owner" : subscribed ? "Unsubscribe" : "Subscribe" }}
        {{ count }}
    </button>
</template>

<script>
import numeral from "numeral";

export default {
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        },
        initialSubscriptions: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            subscriptions: this.initialSubscriptions
        };
    },
    methods: {
        toggleSubscription() {
            if (!__auth()) {
                return alert("Please login to subscribe!");
            }
            if (this.owner) {
                return alert("You cannot subscribe to your channel.");
            }

            if (this.subscribed) {
                axios
                    .delete(
                        `/channels/${this.channel.id}/subscription/${this.subscription.id}`
                    )
                    .then(() => {
                        this.subscriptions = this.subscriptions.filter(
                            s => s.id !== this.subscription.id
                        );
                    });
            } else {
                axios
                    .post(`/channels/${this.channel.id}/subscription`)
                    .then(response => {
                        this.subscriptions.push(response.data);
                    });
            }
        }
    },
    computed: {
        subscribed() {
            if (!__auth()) return false;

            return !!this.subscription;
        },
        subscription() {
            if (!__auth()) return null;
            return this.subscriptions.find(
                subscription => subscription.user_id === __auth().id
            );
        },
        owner() {
            if (__auth() && this.channel.user_id === __auth().id) return true;
            return false;
        },
        count() {
            return numeral(this.subscriptions.length).format("0a");
        }
    }
};
</script>
