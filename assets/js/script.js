document.documentElement.style.setProperty("--nav-color", vtsData.navColor);
document.documentElement.style.setProperty("--play-color", vtsData.playColor);

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const section = document.querySelector(".svts-testimonial-section");
    const swiperContainer = document.querySelector(".mySwiper");

    if (!section || !swiperContainer || typeof Swiper === "undefined") return;

    /* -------------------------------
       Initialize Swiper
    -------------------------------- */
    const swiper = new Swiper(".mySwiper", {
      slidesPerView: 1.2,
      spaceBetween: 20,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
        1200: { slidesPerView: 4 },
      },
      watchSlidesProgress: true,
    });

    const allVideos = section.querySelectorAll("video");

    /* -------------------------------
       Utility: Stop All Videos
    -------------------------------- */
    function stopAllVideos(reset = true) {
      allVideos.forEach((video) => {
        video.pause();
        if (reset) video.currentTime = 0;
        video.removeAttribute("controls");

        const slide = video.closest(".swiper-slide");
        if (!slide) return;

        const content = slide.querySelector(".svts-card-content");
        const playBtn = slide.querySelector(".svts-play-btn");

        if (content) content.classList.remove("hidden");
        if (playBtn) playBtn.style.display = "flex";
      });
    }

    /* -------------------------------
       Play Button Click
    -------------------------------- */
    section.addEventListener("click", function (e) {
      const playBtn = e.target.closest(".svts-play-btn");
      if (!playBtn) return;

      const slide = playBtn.closest(".swiper-slide");
      const video = slide.querySelector("video");
      const content = slide.querySelector(".svts-card-content");

      if (!video) return;

      stopAllVideos(true); // stop others

      video.play();
      video.setAttribute("controls", true);

      playBtn.style.display = "none";
      if (content) content.classList.add("hidden");
    });

    /* -------------------------------
       Handle Video Pause
    -------------------------------- */
    allVideos.forEach((video) => {
      video.addEventListener("pause", function () {
        const slide = video.closest(".swiper-slide");
        if (!slide) return;

        const content = slide.querySelector(".svts-card-content");
        const playBtn = slide.querySelector(".svts-play-btn");

        if (content) content.classList.remove("hidden");
        if (playBtn) playBtn.style.display = "flex";

        video.removeAttribute("controls");
      });
    });

    /* -------------------------------
       Stop When Clicking Outside Section
    -------------------------------- */
    document.addEventListener("click", function (e) {
      if (!section.contains(e.target)) {
        stopAllVideos(false); // pause only, don't reset
      }
    });

    /* -------------------------------
       Stop On Slide Change
    -------------------------------- */
    swiper.on("slideChange", function () {
      stopAllVideos(false); // pause only
    });
  });
})();
