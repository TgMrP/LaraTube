<template>
    <div class="my-1">
        <div class="media mt-3" v-for="reply in replies.data" :key="reply.id">
            <!-- <img
            src="https://picsum.photos/id/42/200/200"
            alt=""
            width="30"
            height="30"
            class="rounded-circle mr-3"
        /> -->
            <avatar
                :username="reply.user.name"
                :size="30"
                class="mr-3 my-3"
            ></avatar>
            <div class="media-body">
                <h6 class="mt-0">
                    {{ reply.user.name }}
                </h6>
                <small>
                    {{ reply.body }}
                </small>

                <votes
                    :default-votes="reply.votes"
                    :entity-id="reply.id"
                    :entity-owner="reply.user.id"
                ></votes>
            </div>
        </div>

        <div
            class="text-center"
            v-if="comment.repliesCount > 0 && replies.next_page_url"
        >
            <button
                @click="fetchReplies"
                class="btn btn-default border-1 btn-sm"
            >
                Load Replies
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        comment: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            replies: {
                data: [],
                next_page_url: `/comments/${this.comment.id}/replies`
            }
        };
    },
    methods: {
        fetchReplies() {
            axios.get(this.replies.next_page_url).then(({ data }) => {
                this.replies = {
                    ...data,
                    data: [...this.replies.data, ...data.data]
                };
            });
        },
        addReply(newReply) {
            this.replies = {
                ...this.replies,
                data: [newReply, ...this.replies.data]
            };
        }
    }
};
</script>

<style lang="scss" scoped></style>
