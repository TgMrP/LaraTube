const player = videojs("video");

let startTime = null;
let viewLogged = false;
let timeView = null;
let pauseTime = 0;

player.on("pause", function() {
    pauseTime = new Date().getTime() - startTime;
});

player.on("play", function() {
    startTime = new Date().getTime() - pauseTime;
    console.log(startTime);
});

player.on("timeupdate", function() {
    const percentagePlayed = Math.ceil(
        (player.currentTime() / player.duration()) * 100
    );

    if (percentagePlayed >= 10 && !startTime) {
        startTime = new Date().getTime();
    } else if (startTime) {
        timeView = Math.ceil((new Date().getTime() - startTime) / 1000);
        const percentagePlayedReal = Math.ceil(
            (timeView / player.duration()) * 100
        );
        if (!viewLogged && percentagePlayedReal > 10) {
            axios.put("/videos/" + window.CURRENT_VIDEO);
            console.log("update");
            viewLogged = true;
        }
    }
});
