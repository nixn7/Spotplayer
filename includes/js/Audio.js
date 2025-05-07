// var currentPlaylist = Array();
var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;


function openPage(url) {
  if (timer != null) {
      clearTimeout(timer);
  }

  if (url.indexOf("?") == -1) {
      url = url + "?";
  }

  var encodedUrl = encodeURI(url);
  console.log(encodedUrl);
  $("#mainContent").load(encodedUrl);
  $("body").scrollTop(0);
  history.pushState(null, null, url);
}


function formatTime(seconds) {
  var time = Math.round(seconds);
  var minutes = Math.floor(time / 60);
  var seconds = time - minutes * 60;
  var extraZero;
  if (seconds < 10) {
    extraZero = "0";
  } else {
    extraZero = "";
  }
  return minutes + ":" + seconds.toString().padStart(2, "0");
}

function updateTimeProgressBar(audio) {
  $("#currentTime").text(formatTime(audio.currentTime));
  $("#remainingTime").text(formatTime(audio.duration - audio.currentTime));

  var progress = (audio.currentTime / audio.duration) * 100;
  $("#progress").css("width", progress + "%");
}

function updateVolumeBar(audio) {
  var volume = audio.volume * 100;
  $(".volumebarBg .volumeBar").css("width", progress + "%");
}

function openPage(url) {
  if (url.indexOf("?") == -1) {
    url = url + "?";
  }
  var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
  $("#mainContentContainer").load(encodedUrl);
  $("body").scrollTop(0);
  history.pushState(null, null, url);
}

function Audio() {
  this.currentlyPlaying;
  this.audio = document.createElement("audio");

  this.audio.addEventListener("ended", function () {
    nextSong();
  });

  this.audio.addEventListener("canplay", function () {
    var duration = formatTime(this.duration);
    $("#remainingTime").text(duration);
  });

  this.audio.addEventListener("timeupdate", function () {
    if (this.duration) {
      updateTimeProgressBar(this);
    }
  });

  this.audio.addEventListener("volumechange", function () {
    updateVolumeBar(this);
  });

  this.setTrack = function (track) {
    this.currentlyPlaying = track;
    this.audio.src = track.path;
    // this.audio.currentTime = 0;
    $("#progress").css("width", "0%");
  };

  this.play = function () {
    this.audio.play();
  };
  this.pause = function () {
    this.audio.pause();
  };

  this.setTime = function (seconds) {
    this.audio.currentTime = seconds;
  };
}
