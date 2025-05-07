<section #id="navTopBar" class="flex items-center justify-center w-full mt-1 bg-black ">

  <div #id="topWrapper" class="flex items-center justify-between w-full gap-2">

    <div #id="brand_left" class="">
      <button #id="spotify_button" class="">
        <img src="includes/assets/icons/Spotify.svg" class="ml-4" alt="spotify_icon" />
      </button>
    </div>

    <div #id="top_center" class="relative flex items-center justify-center gap-2">
      <div #id="home " class="bg-[#1f1f1f] hover:bg-[#2a2a2a] m-auto p-[12px] rounded-full transition duration-300 ">
        <a href="#">
          <img src="includes/assets/icons/home.svg" alt="" />
        </a>
      </div>
      <!-- SEARCH BAR  -->
      <img src="includes/assets/icons/Search Icon.svg" class="absolute cursor-pointer inset-x-16 h-9 w-9"
        for="searchbox" alt="" />
      <input type="text" id="searchbox" name="searchbox" placeholder="What do you want to play?"
        class="text-white font-cirbook font-semibold placeholder:font-cirbo placeholder:font-semibold placeholder:text-[14px] placeholder-[#b3b3b3] bg-[#1f1f1f] h-[48px] w-[475px] rounded-full py-[12px] px-12 m-auto hover:bg-[#2a2a2a] transition duration-300  " />
    </div>

    <!-- PROFILE  -->
    <div class="bg-[#1f1f1f] hover:bg-[#2a2a2a] flex p-[10px] items-center rounded-full mr-2 transition duration-300  ">

      <span class="mx-2 font-extrabold font-spmix text-[#1db945] "><?php echo $userLoggedIn ?></span>
      <button>
        <img src="includes/assets/icons/Spotify.svg" alt="user_Profile_picture">
      </button>
    </div>

  </div>
</section>