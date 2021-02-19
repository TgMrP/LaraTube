<template>
    <div class="col-md-8">
        <div
            class="card p-3 d-flex justify-content-center align-items-center"
            v-if="!selected"
        >
            <input
                type="file"
                name="videos"
                id="videos"
                ref="videos"
                style="display: none;"
                multiple
                @change="upload"
            />
            <svg
                class="text-danger"
                width="100px"
                heigth="100px"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                @click="selectFiles"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                />
            </svg>
            <p>Upload Videos</p>
        </div>

        <div class="card p-3" v-else>
            <div class="my-4" v-for="(video, key) in videos" :key="key">
                <div class="progress mb-3">
                    <div
                        class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar"
                        :style="
                            `width: ${video.percentage ||
                                progress[video.name]}%;`
                        "
                        aria-valuenow="50"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    >
                        {{
                            video.percentage
                                ? video.percentage === 100
                                    ? "Done"
                                    : "Processing"
                                : "Uploading"
                        }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div
                            v-if="!video.thumbnail"
                            class="d-flex justify-content-center align-items-center"
                            style="height: 180px; color: white; font-size: 18px; background: #808080;"
                        >
                            Loading thumbnail...
                        </div>
                        <img
                            v-else
                            :src="video.thumbnail"
                            style="width: 100%;"
                            alt=""
                        />
                    </div>

                    <div class="col-md-4">
                        <a
                            :href="`/videos/${video.id}`"
                            target="_blank"
                            v-if="video.percentage && video.percentage === 100"
                        >
                            {{ video.title }}
                        </a>
                        <h4 v-else class="text-center">
                            {{ video.title || video.name }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    data() {
        return {
            selected: false,
            videos: [],
            progress: {},
            uploads: [],
            intervals: {}
        };
    },
    methods: {
        selectFiles() {
            this.$refs.videos.click();
        },
        upload() {
            this.selected = true;
            this.videos = Array.from(this.$refs.videos.files);

            const uploaders = this.videos.map(video => {
                const form = new FormData();

                this.progress[video.name] = 0;

                form.append("video", video);
                form.append("title", video.name);

                return axios
                    .post(`/channels/${this.channel.id}/videos`, form, {
                        onUploadProgress: event => {
                            this.progress[video.name] = Math.ceil(
                                (event.loaded / event.total) * 100
                            );
                            this.$forceUpdate();
                        }
                    })
                    .then(({ data }) => {
                        this.uploads.push(data);
                    });
            });

            axios.all(uploaders).then(() => {
                this.videos = this.uploads;
                this.videos.forEach(video => {
                    this.intervals[video.id] = setInterval(() => {
                        axios.get(`/videos/${video.id}`).then(({ data }) => {
                            if (data.percentage === 100) {
                                clearInterval(this.intervals[video.id]);
                            }
                            this.videos = this.videos.map(v => {
                                if (v.id === data.id) {
                                    return data;
                                } else {
                                    return v;
                                }
                            });
                        });
                    }, 3000);
                });
            });
        }
    }
};
</script>

<style lang="scss" scoped></style>
