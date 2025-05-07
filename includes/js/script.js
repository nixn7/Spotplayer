$(document)
  .ajaxStart(function () {
    console.log("AJAX request started");
  })
  .ajaxSuccess(function (event, xhr, settings) {
    console.log("AJAX request completed successfully", settings.url);
  })
  .ajaxError(function (event, xhr, settings) {
    console.log("AJAX request failed", settings.url);
  });

$(window).scroll(function () {
  hideOptionsMenu();
});

function createPlaylists() {
  document.getElementById("playlistModal").classList.remove("hidden");

  document.getElementById("playlistModal").onclick = function () {
    document.getElementById("playlistModal").classList.add("hidden");
  };
}

function deletePlaylist(playlistId) {
  document.getElementById("deletePlaylistModal").classList.remove("hidden");

  document.getElementById("cancelDelete").onclick = function () {
    document.getElementById("deletePlaylistModal").classList.add("hidden");
  };

  document.getElementById("confirmDelete").onclick = function () {
    $.post("includes/handlers/ajax/deletePlaylist.php", {
      playlistId: playlistId,
    })
      .done(function (response) {
        if (response.trim() !== "") {
          alert(response);
        } else {
          openPage("index.php");
        }

        document.getElementById("deletePlaylistModal").classList.add("hidden");
      })
      .fail(function () {
        alert("An error occurred while deleting the playlist.");

        document.getElementById("deletePlaylistModal").classList.add("hidden");
      });
  };
}

function closeModal() {
  document.getElementById("playlistModal").classList.add("hidden");
}

function submitPlaylist() {
  const playlistName = document.getElementById("playlistName").value.trim();
  const formData = new FormData();
  formData.append("playlistName", playlistName);

  // Add file if exists
  const coverPhoto = document.getElementById("coverPhoto").files[0];
  if (coverPhoto) formData.append("coverPhoto", coverPhoto);

  // Show loading state
  const submitBtn = document.querySelector("#playlistModal [type='submit']");
  submitBtn.disabled = true;
  submitBtn.textContent = "Creating...";

  fetch("/process_createPlaylist.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Show Tailwind notification
        showNotification(data.message);
        closeModal();

        // Optional: Reset form
        document.getElementById("playlistName").value = "";
      } else {
        alert(data.message || "Error creating playlist");
      }
    })
    .catch((error) => {
      alert("Network error: " + error.message);
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.textContent = "Create";
    });
}

function updateCover(input) {
  if (!input.files[0]) return;

  const formData = new FormData();
  formData.append("playlistId", "<?php echo $playlistId; ?>");
  formData.append("coverPhoto", input.files[0]);

  fetch("includes/handlers/Ajax/updateCover.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.querySelector("#leftSection img").src =
          data.newPath + "?t=" + Date.now();
        showNotification("Cover updated!");
      } else {
        showNotification("Error: " + data.error, "error");
      }
    });
}

function hideOptionsMenu() {
  var menu = $(".optionsMenu");
  menu.css("opacity", "0");
  menu.css("transform", "translateY(2px)");

  setTimeout(function () {
    menu.addClass("hidden");
  }, 200);
}

$(document).on("change", "select", function (e) {
  e.preventDefault();

  var playlistId = $(this).val();
  var songId = $(".optionsMenu .songId").val();

  console.log("PlaylistId:", playlistId);
  console.log("SongId:", songId);

  if (!playlistId || playlistId == "") {
    return;
  }

  $.post("includes/handlers/Ajax/addToPlaylist.php", {
    playlistId: playlistId,
    songId: songId,
  })
    .done(function (response) {
      if (response.trim() !== "") {
        showNotification(response, "error");
      } else {
        showNotification("Added to playlist successfully!", "success");
      }
      hideOptionsMenu();
      $(this).val("");
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX error:", textStatus, errorThrown);
      showNotification("Failed to add to playlist", "error");
    });
});

function showOptionsMenu(button) {
  var songId = $(button).prev(".songId").val();

  $(".optionsMenu .songId").val(songId);

  var menu = $(".optionsMenu");
  menu.css({
    top: $(button).offset().top + "px",
    left: $(button).offset().left + "px",
  });
  menu.removeClass("hidden");
  menu.css("opacity", "1");
  menu.css("transform", "translateY(0)");

  $menu.removeClass("hidden");
  setTimeout(() => {
    $menu.removeClass("opacity-0 translate-y-2");
  }, 10);

  const clickHandler = (e) => {
    if (
      !$menu.is(e.target) &&
      !$(e.target).closest($menu).length &&
      e.target !== button
    ) {
      hideOptionsMenu();
      document.removeEventListener("click", clickHandler);
    }
  };

  document.addEventListener("click", clickHandler);
  event.stopPropagation();
}

function showNotification(message, type) {
  if ($("#notification").length === 0) {
    $("body").append(
      '<div id="notification" class="fixed top-5 right-5 p-4 rounded-lg shadow-lg transition-all duration-300 opacity-0 transform translate-y-[-20px] z-50"></div>'
    );
  }

  var bgColor = type === "error" ? "bg-red-500" : "bg-green-500";

  $("#notification")
    .removeClass("bg-red-500 bg-green-500")
    .addClass(bgColor)
    .text(message)
    .css("opacity", "1")
    .css("transform", "translateY(0)");

  setTimeout(function () {
    $("#notification")
      .css("opacity", "0")
      .css("transform", "translateY(-20px)");
  }, 3000);
}

$(document).ready(function () {
  //  REVERB EFFECT
  $("#reverbToggle").change(function () {
    let isEnabled = $(this).prop("checked");
    audioElement.toggleReverb(isEnabled);
  });

  //  STEREO WIDHTH EFFECT

  $("#stereoToggle").change(function () {
    let isEnabled = $(this).prop("checked");
    audioElement.toggleStereoWidth(isEnabled);
  });

  // 🎚 Speed Toggle
  $("#speedToggle").change(function () {
    let newSpeed = $(this).prop("checked") ? 1.25 : 1.0;
    audioElement.setSpeed(newSpeed);
  });
});


