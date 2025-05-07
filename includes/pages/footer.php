<?php
$songQuery = mysqli_query($con, 'SELECT id FROM songs ORDER BY RAND() LIMIT 10');
$resultArray = [];

while ($row = mysqli_fetch_array($songQuery)) {
  array_push($resultArray, $row['id']);
}

$jsonArray = json_encode($resultArray, JSON_HEX_TAG);

?>
<link rel="stylesheet" href="includes/tailwind.css">
<script src="color-thief.umd.js"></script>
<script>
  $(document).ready(function () {
    var newPlaylist = <?php echo $jsonArray; ?>;
    audioElement = new Audio();
    setTrack(newPlaylist[0], newPlaylist, false);
    // updateVolumeProgressBar(audioElement.audio);

    // Set initial state of play/pause buttons
    $(".playButton").css("display", "block");
    $(".pauseButton").css("display", "none");

    // FX BUTTON SHOW / HIDE
    document.getElementById("effectsToggle").addEventListener("click", () => {
      const panel = document.getElementById("effectsPanel");
      panel.classList.toggle("hidden"); // Toggle visibility
    });

    // USER CLICK DRAG RESTRICTING / Change it to CSS.
    $("#playbar").on("mousedown touchstart mousemove touchmove", function (e) {
      if (!$(e.target).hasClass("volumeBar") && !$(e.target).closest(".volumebarBg").length) {
        e.preventDefault();
      }
    });

    // PROGRESS BAR
    $("#progressBg").mousedown(function () {
      mouseDown = true;
    });
    $("#progressBg").mousemove(function (e) {
      if (mouseDown == true) {
        timeFromOffset(e, this);
      }
    });
    $("#progressBg").mouseup(function (e) {
      timeFromOffset(e, this);
    });
    $("#progressBg").click(function (e) {
      timeFromOffset(e, this);
    });

    // VOLUME BAR
    $(".volumeBar").mousedown(function () {
      mouseDown = true;
    });
    $(".volumebarBg").mousemove(function (e) {
      if (mouseDown == true) {
        var percentage = e.offsetX / $(this).width();

        if (percentage >= 0 && percentage <= 1) {
          audioElement.audio.volume = percentage;
        }
      }
    });

    $(".volumebarBg").mouseup(function (e) {
      var percentage = e.offsetX / $(this).width();

      if (percentage >= 0 && percentage <= 1) {
        audioElement.audio.volume = percentage;
      }
    });
    $(".volumebarBg").click(function (e) {
      var percentage = e.offsetX / $(this).width();

      if (percentage >= 0 && percentage <= 1) {
        audioElement.audio.volume = percentage;
      }
    });

    $(document).mouseup(function () {
      mouseDown = false;
    })

    function timeFromOffset(mouse, progressBg) {
      var percentage = mouse.offsetX / $(progressBg).width() * 100;
      var seconds = audioElement.audio.duration * (percentage / 100);
      audioElement.setTime(seconds);
    }

    // Improved Speed Control - Directly use playbackRate instead of a non-existent setSpeed function
    document.getElementById("speedSlider").addEventListener("input", function (e) {
      let speed = parseFloat(e.target.value);
      audioElement.audio.playbackRate = speed;
      document.getElementById("speedValue").textContent = speed.toFixed(1) + "x";
    });

    // Initialize the speed display
    document.getElementById("speedValue").textContent = "1.0x";

    // Volume Control
    document.getElementById("volumeslider").addEventListener("input", function (e) {
      audioElement.volume = e.target.value; // Set volume (0 to 1)
    });

    // Reverb Simulation (Basic Echo Effect)
    document.getElementById("reverbToggle").addEventListener("change", function (e) {
      if (e.target.checked) {
        audioElement.addEventListener("timeupdate", applyEcho);
      } else {
        audioElement.removeEventListener("timeupdate", applyEcho);
      }
    });

    function applyEcho() {
      if (audioElement.currentTime > 0.5) {
        audioElement.currentTime -= 0.5; // Simulate echo by rewinding slightly
      }
    }

    // Speed Control - Make slider draggable with mouse events
    $("#speedSlider").mousedown(function () {
      mouseDown = true;
    });
    
    // Remove redundant speed slider mousemove and mouseup handlers
    $("#speedSlider").mousemove(function (e) {
      if (mouseDown == true) {
        var percentage = e.offsetX / $(this).width();
        var speed = 0.5 + (percentage * 1.5); // Convert 0-100% to 0.5-2.0
        
        if (speed >= 0.5 && speed <= 2.0) {
          speed = Math.round(speed * 10) / 10; // Round to 1 decimal place
          audioElement.audio.playbackRate = speed;
          document.getElementById("speedValue").textContent = speed.toFixed(1) + "x";
          updateSpeedProgressBar(speed); // Update visual indicator
        }
      }
    });
    
    $("#speedSlider").mouseup(function (e) {
      var percentage = e.offsetX / $(this).width();
      var speed = 0.5 + (percentage * 1.5); // Convert 0-100% to 0.5-2.0
      
      if (speed >= 0.5 && speed <= 2.0) {
        speed = Math.round(speed * 10) / 10; // Round to 1 decimal place
        audioElement.audio.playbackRate = speed;
        document.getElementById("speedValue").textContent = speed.toFixed(1) + "x";
        updateSpeedProgressBar(speed); // Update visual indicator
      }
    });
    
    // Ensure the input event handler is sufficient
    document.getElementById("speedSlider").addEventListener("input", function (e) {
      let speed = parseFloat(e.target.value);
      audioElement.audio.playbackRate = speed;
      document.getElementById("speedValue").textContent = speed.toFixed(1) + "x";
      updateSpeedProgressBar(speed); // Update visual indicator
    });

    // Function to update speed slider visual feedback
    function updateSpeedProgressBar(speed) {
      var percentage = ((speed - 0.5) / 1.5) * 100; // Convert 0.5-2.0 range to 0-100%
      $("#speedBarProgress").css({
        width: percentage + "%", // Update width
        left: "0", // Ensure alignment starts from the left
      });
    }
    
    // Initialize the speed display and progress bar
    updateSpeedProgressBar(1.0);

    // Refine speed slider input handler for full range interaction
    document.getElementById("speedSlider").addEventListener("input", function (e) {
      let speed = parseFloat(e.target.value);
      audioElement.audio.playbackRate = speed;
      document.getElementById("speedValue").textContent = speed.toFixed(1) + "x";
      updateSpeedProgressBar(speed); // Update visual indicator
    });

    // Ensure the progress bar spans the full range and aligns with the slider
    function updateSpeedProgressBar(speed) {
      var percentage = ((speed - 0.5) / 1.5) * 100; // Convert 0.5-2.0 range to 0-100%
      $("#speedBarProgress").css({
        width: percentage + "%", // Update width
      });
    }

    // Fix slider's draggable and clickable area
    $("#speedSlider").on("mousedown touchstart", function (e) {
      mouseDown = true;
    });

    $("#speedSlider").on("mousemove touchmove", function (e) {
      if (mouseDown) {
        var offsetX = e.offsetX || e.originalEvent.touches[0].pageX - $(this).offset().left;
        var percentage = offsetX / $(this).width();
        var speed = 0.5 + (percentage * 1.5); // Convert 0-100% to 0.5-2.0

        if (speed >= 0.5 && speed <= 2.0) {
          speed = Math.round(speed * 10) / 10; // Round to 1 decimal place
          audioElement.audio.playbackRate = speed;
          document.getElementById("speedValue").textContent = speed.toFixed(1) + "x";
          updateSpeedProgressBar(speed); // Update visual indicator
        }
      }
    });

    $(document).on("mouseup touchend", function () {
      mouseDown = false;
    });

    // Initialize the speed display and progress bar
    updateSpeedProgressBar(1.0);

  });

  // DOCUMENT READY END

  function prevSong() {
    if (audioElement.audio.currentTime >= 3 || currentIndex == 0) {
      audioElement.setTime(0);
    }
    else {
      currentIndex = currentIndex - 1;
      setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
    }
  };


  // NEXT SONGS FNC 
  function nextSong() {
    if (repeat == true) {
      audioElement.setTime(0);
      playSong();
      return;
    }
    if (currentIndex == currentPlaylist.length - 1) {
      currentIndex = 0;
    }
    else {
      currentIndex++;
    }
    var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
    setTrack(trackToPlay, currentPlaylist, true);
  }


  // REPEAT FUNCTION BOC
  function setRepeat() {
    repeat = !repeat;
    var imageName = repeat ? "repeat-active.svg" : "REPEAT.svg";
    $(".repeatButton img").attr("src", "includes/assets/icons/" + imageName);
  };

  // MUTE FUNCTION VOLUME 
  function setMute() {
    audioElement.audio.muted = !audioElement.audio.muted;
    var imageName = audioElement.audio.muted ? "muted.svg" : "SPEAKER.svg";
    $(".speakerButton img").attr("src", "includes/assets/icons/" + imageName);
  };

  // shffle FUNCTION 
  function setShuffle() {
    shuffle = !shuffle;
    var imageName = shuffle ? "shuffle-active.svg" : "shuffle.svg";
    $(".shuffleButton img").attr("src", "includes/assets/icons/" + imageName);

    if (shuffle == true) {
      shuffleArray(shufflePlaylist);
      currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
    else {
      currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
    }

  };

  function shuffleArray(a) {
    var j, x, i;
    for (i = a.length; i > 0; i--) {
      j = Math.floor(Math.random() * i);
      x = a[i - 1];
      a[i - 1] = a[j];
      a[j] = x;
    }
  }


  // UPDATING TRACK /S SET TRAKC 
  function setTrack(trackId, newPlaylist, play) {

    if (newPlaylist != currentPlaylist) {
      currentPlaylist = newPlaylist;
      shufflePlaylist = newPlaylist.slice();
      shuffleArray(shufflePlaylist);
    }

    if (shuffle == true) {
      currentIndex = shufflePlaylist.indexOf(trackId);
    }
    else {
      currentIndex = currentPlaylist.indexOf(trackId);
    }
    pauseSong();

    $.post("includes/handlers/Ajax/getSongJson.php", { songId: trackId }, function (data) {

      var track = JSON.parse(data);
      $(".trackName ").text(track.title)

      $.post("includes/handlers/Ajax/getArtistJson.php", { artistId: track.artist }, function (data) {
        var artist = JSON.parse(data);
        $(".artistName").text(artist.name);
      });

      $.post("includes/handlers/Ajax/getAlbumJson.php", { albumId: track.album }, function (data) {
        var album = JSON.parse(data);
        $(".artwork").attr("src", album.artworkPath);
      });

      audioElement.setTrack(track);
      audioElement.setTime(0);

      if (play) {
        playSong();
      }

    });
  };

  function playSong() {

    if (audioElement.audio.currentTime == 0) {
      $.post("includes/handlers/Ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
    }

    $(".playButton").hide();
    $(".pauseButton").show();
    audioElement.play();
  };

  function pauseSong() {
    $(".playButton").show();
    $(".pauseButton").hide();
    audioElement.pause();
  };

</script>


<!-- PLAYBAR  -->
<div id="playbar" class="min-h-[84px] w-full flex bottom-0 fixed px-2 items-center transition-all duration-1000"
  style="min-width: 600px; background:black ;">
  <div id="playWrapper" class="flex items-center justify-between w-full h-full ">

    <!-- LEFT SIDE  -->
    <div id="leftPlaybar" class="w-[20%] h-full flex p-2 ">
      <div id="leftWrapper" class="flex items-center w-full h-full gap-3 ">
        <div id="artwork" class="w-[70px] h-[70px] flex-shrink-0">
          <!-- ALBUM ARTWORK -->
          <img src="" class="artwork w-full h-full object-cover rounded-[4px]" alt="Album Cover">
        </div>
        <div id="songInfo" class="flex flex-col justify-center pr-2">
          <!-- SONG NAME -->
          <span class="trackName font-bold font-spmix text-[16px] text-white truncate" title="Song"></span>
          <!-- ARTIST NAME -->
          <span class="artistName font-bold font-cirbo text-[14px] text-[#b3b3b3] truncate" title="Artist"></span>
        </div>
      </div>
    </div>

    <!-- CENTER CONTROLS  -->
    <div id="centerPlaybar" class="flex w-[60%] h-full p-1 flex-col">

      <div id="centerControls" class="flex items-center justify-center w-full h-full gap-[18px] hover:">
        <button onclick="setShuffle()" class="shuffleButton hover:scale-110">
          <img src="includes/assets/icons/shuffle.svg" class="size-4" alt="next_button">
        </button>
        <button onclick="prevSong()" class="hover:scale-110"><img src="includes/assets/icons/PREVIOUS.svg"
            class="size-4 hover:fill-[#1db945]" alt="previous_button">
        </button>
        <button onclick="playSong()"
          class="playButton bg-white p-[6px] rounded-full transition-all duration-300 hover:animate-pulse hover:scale-110">
          <img src="includes/assets/icons/play_black.svg" class="size-[20px]" alt="play_button">
        </button>
        <button onclick="pauseSong()"
          class="pauseButton transition-all bg-white p-[6px] rounded-full duration-300 hover:animate-pulse hover:scale-110 hidden"
          style="">
          <img src="includes/assets/icons/pause_black.svg" class="size-[20px]" alt="pause_button">
        </button>
        <button onclick="nextSong()" class="hover:scale-110">
          <img src="includes/assets/icons/NEXT.svg" class="size-4" alt="next_button">
        </button>
        <button onclick="setRepeat()" class="repeatButton hover:scale-110">
          <img src="includes/assets/icons/REPEAT.svg" class="size-4" alt="repeat_button">
        </button>
      </div>


      <!-- PROGRESS BAR  -->
      <div id="progressBar" class="flex items-center justify-center h-full gap-3 ">

        <!-- CURRENT TIME -->
        <div><span id="currentTime" class="text-sm font-semibold font-spmix cursor-none"
            title="Current Time">0:00</span>
        </div>
        <div id="progressBg" class="w-2/4 h-[6px] rounded-full bg-[#2a2a2a] cursor-pointer ">
          <div id="progress"
            class="h-[6px] rounded-full bg-white hover:bg-[#1db945] cursor-pointer transition-all duration-200 ease-linear  ">
          </div>
        </div>

        <!-- DURATION  -->
        <div><span id="remainingTime" class="text-sm font-semibold font-spmix cursor-none" title="Total Time"></span>
        </div>
      </div>
    </div>

    <!-- RIGHT CONTROLS  -->
    <div id="rightPlaybar" class="w-[20%] h-full flex justify-center items-center  ">
      <div id="rightControls" class="flex gap-2 p-6">
        <!-- Volume Control Container -->
        <div class="relative group flex items-center">

          <!-- FX BUTTON SECTION -->
          <div class="relative">
            <button id="effectsToggle"
              class="p-2 mt-1 mr-1 hover:bg-[#2A2A2A] rounded-full hover:scale-110 duration-200 transition-all focus:outline-none">
              <img src="includes/assets/icons/Orange_arrow.svg" alt="Effects Toggle" class="w-5 h-5" />
            </button>

            <!-- Flyout Panel -->
            <div id="effectsPanel" style="width: 250px;" class="absolute p-4 bg-[#121212] text-white border border-[#2a2a2a] rounded-xl shadow-lg
              hidden bottom-[90%] left-1/2 -translate-x-1/2 mb-2 transition-all duration-300 ease-in-out opacity-100
              translate-y-0 effects-closing:opacity-0 effects-closing:translate-y-[-5px]">
              <form id="effectsForm" class="space-y-4">

                <!-- Effects section with Coming Soon overlay -->
                <div class="relative p-2">
                  <span class="text-[#1db945] font-bold text-sm py-2 rounded-md">Coming Soon</span>
                  <!-- Coming Soon overlay -->
                  <div class="absolute inset-0 bg-white opacity-20 flex items-center justify-center z-30 rounded-lg">
                  </div>
                
                  <!-- Reverb -->
                  <label for="reverbToggle" class="flex items-center justify-between text-sm cursor-not-allowed opacity-70">
                    <span class="font-semibold">Reverb</span>
                    <input type="checkbox" id="reverbToggle" name="effects" value="reverb" class="sr-only peer" disabled />
                    <span class="bg-[#2a2a2a] text-white px-2 py-1 rounded-md ml-2 peer-checked:bg-[#1db954]"></span>
                  </label>

                  <hr class="border-t-2 border-[#2a2a2a]" />

                  <!-- 3D Spatialization -->
                  <label for="stereoToggle" class="flex items-center justify-between text-sm cursor-not-allowed opacity-70">
                    <span class="font-semibold">3D Spatialization</span>
                    <input type="checkbox" id="stereoToggle" name="effects" value="stereo" class="sr-only peer" disabled />
                    <span class="bg-[#2a2a2a] text-white px-2 py-1 rounded-md ml-2 peer-checked:bg-[#1db954]"></span>
                  </label>
                </div>

                <hr class="border-t-2 border-[#2a2a2a]" />

                <!-- Speed control - simplified and improved -->
                <div class="flex flex-col w-full py-1">
                  <div class="flex items-center justify-between w-full mb-2">
                    <span class="text-sm font-semibold">Playback Speed</span>
                    <span id="speedValue" class="px-2 py-1 text-xs font-bold text-white bg-[#1db954] rounded-md">1.0x</span>
                  </div>
                  
                  
                  <div class="relative w-full h-5 group">
                    <!-- Progress indicator -->
                    <div id="speedBarProgress" class="absolute h-1 bg-[#1db954] rounded-full top-2 transition-all duration-150"></div>
                    
                    <input type="range" id="speedSlider" 
                      class="absolute h-5 mt-2 bg-transparent cursor-pointer focus:outline-none" 
                      min="0.5" max="2.0" step="0.1" value="1.0">
                  </div>
                  <div class="flex justify-between w-full mt-1">
                    <span class="text-[10px] text-[#b3b3b3]">0.5x</span>
                    <span class="text-[10px] text-[#b3b3b3]">1.0x</span>
                    <span class="text-[10px] text-[#b3b3b3]">2.0x</span>
                  </div>
                </div>

              </form>
            </div>

          </div>

          <!-- Speaker Icon -->
          <button onclick="setMute()"
            class="speakerButton p-2 mt-1  hover:scale-110 hover:bg-[#2a2a2a] rounded-full transition-colors duration-200"
            title="Mute" onclick="">
            <img src="includes/assets/icons/SPEAKER.svg" class="w-4 h-4" alt="mute_button">
          </button>

          <!-- Volume Slider -->
          <div class="volumebarBg  transition-opacity duration-200  ml-1 mt-1">
            <input type="range" id="volumeslider"
              class="volumeBar w-[100px] h-[6px] bg-[#2a2a2a] hover:bg-[#1db945] appearance-none transition-all duration-200 ease-linear rounded-full cursor-pointer"
              min="0" max="1" step="0.001" value="1">
          </div>
        </div>
        <!-- VOLUME CONTROL END  -->

      </div>
    </div>
    <!-- PLAYBAR END  -->
  </div>
  <!-- MAIN CONTAINER END  -->
</div>

<style>
  .truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
</script>
</body>

</html>