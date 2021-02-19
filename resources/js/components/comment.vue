<template>
    <div class="media my-2">
        <!-- <img
                src="https://picsum.photos/id/42/200/200"
                alt=""
                width="30"
                height="30"
                class="rounded-circle mr-3"
            /> -->
        <avatar :username="comment.user.name" :size="30" class="mr-3"></avatar>
        <div class="media-body">
            <h6 class="mt-0">
                {{ comment.user.name }}
            </h6>
            <small>
                {{ comment.body }}
            </small>

            <div class="d-flex align-items-center">
                <votes
                    :default-votes="comment.votes"
                    :entity-id="comment.id"
                    :entity-owner="comment.user.id"
                ></votes>
                <button
                    @click="addingReplay = !addingReplay"
                    class="btn btn-sm ml-2"
                    :class="{
                        'btn-default': !addingReplay,
                        'btn-danger': addingReplay
                    }"
                >
                    {{ addingReplay ? "Cancel" : "Add Replay" }}
                </button>
            </div>

            <div v-if="addingReplay" class="form-inline my-4 w-full">
                <input
                    v-model="body"
                    type="text"
                    class="form-control form-control-sm w-75"
                />
                <button @click="addReply" class="btn btn-sm btn-primary ml-2">
                    <small>Add reply</small>
                </button>
            </div>

            <replies ref="replies" :comment="comment"></replies>
        </div>
    </div>
</template>

<script>
import Replies from "./replies.vue";

export default {
    props: {
        comment: {
            type: Object,
            required: true
        },
        video: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            addingReplay: false,
            body: ""
        };
    },
    components: {
        Replies
    },
    methods: {
        addReply() {
            if (!this.body) return;
            axios
                .post(`/comments/${this.video.id}`, {
                    body: this.body,
                    comment_id: this.comment.id
                })
                .then(({ data }) => {
                    this.$refs.replies.addReply(data);
                    this.body = "";
                    this.addingReplay = false;
                });
        }
    }
};
</script>

<style lang="scss" scoped></style>
