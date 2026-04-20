/**
 * Simple Video Testimonial Slider CWS - Frontend JavaScript
 * Version: 1.0.0
 */

(function () {
  "use strict";

  // Set CSS custom properties
  document.documentElement.style.setProperty("--svts-nav-color", svtsData.navColor);
  document.documentElement.style.setProperty("--svts-nav-hover-color", svtsData.navHoverColor);
  document.documentElement.style.setProperty("--svts-nav-hover-icon-color", svtsData.navHoverIconColor);
  document.documentElement.style.setProperty("--svts-play-color", svtsData.playColor);
  document.documentElement.style.setProperty("--svts-play-icon-color", svtsData.playIconColor);

  document.addEventListener("DOMContentLoaded", function () {
    const section = document.querySelector(".svts-testimonial-section");
    const swiperContainer = document.querySelector(".svts-swiper");

    if (!section || !swiperContainer || typeof Swiper === "undefined") {
      console.warn("SVTS: Required elements not found or Swiper not loaded");
      return;
    }

    /* -------------------------------
       Initialize Swiper
    -------------------------------- */
    const swiper = new Swiper(".svts-swiper", {
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
      a11y: {
        prevSlideMessage: 'Previous testimonial',
        nextSlideMessage: 'Next testimonial',
        firstSlideMessage: 'This is the first testimonial',
        lastSlideMessage: 'This is the last testimonial',
      },
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },
    });

    const allVideos = section.querySelectorAll("video");

    /* -------------------------------
       Utility: Stop All Videos
    -------------------------------- */
    function stopAllVideos(reset = true) {
      allVideos.forEach((video) => {
        try {
          video.pause();
          if (reset) video.currentTime = 0;
          video.removeAttribute("controls");

          const slide = video.closest(".swiper-slide");
          if (!slide) return;

          const content = slide.querySelector(".svts-card-content");
          const playBtn = slide.querySelector(".svts-play-btn");

          if (content) {
            content.classList.remove("hidden");
            content.setAttribute('aria-hidden', 'false');
          }
          if (playBtn) {
            playBtn.style.display = "flex";
            playBtn.setAttribute('aria-label', 'Play video');
          }
        } catch (error) {
          console.warn("SVTS: Error stopping video", error);
        }
      });
    }

    /* -------------------------------
       Play Button Click Handler
    -------------------------------- */
    section.addEventListener("click", function (e) {
      const playBtn = e.target.closest(".svts-play-btn");
      if (!playBtn) return;

      e.preventDefault();

      const slide = playBtn.closest(".swiper-slide");
      const video = slide.querySelector("video");
      const content = slide.querySelector(".svts-card-content");

      if (!video) {
        console.warn("SVTS: Video element not found");
        return;
      }

      // Stop other videos first
      stopAllVideos(true);

      // Play current video
      const playPromise = video.play();
      if (playPromise !== undefined) {
        playPromise.then(() => {
          video.setAttribute("controls", true);
          playBtn.style.display = "none";
          playBtn.setAttribute('aria-label', 'Video playing');
          if (content) {
            content.classList.add("hidden");
            content.setAttribute('aria-hidden', 'true');
          }
        }).catch((error) => {
          console.warn("SVTS: Error playing video", error);
          // Fallback: show controls anyway
          video.setAttribute("controls", true);
          playBtn.style.display = "none";
          if (content) {
            content.classList.add("hidden");
            content.setAttribute('aria-hidden', 'true');
          }
        });
      }
    });

    /* -------------------------------
       Video Event Handlers
    -------------------------------- */
    allVideos.forEach((video) => {
      // Handle video pause
      video.addEventListener("pause", function () {
        const slide = video.closest(".swiper-slide");
        if (!slide) return;

        const content = slide.querySelector(".svts-card-content");
        const playBtn = slide.querySelector(".svts-play-btn");

        if (content) {
          content.classList.remove("hidden");
          content.setAttribute('aria-hidden', 'false');
        }
        if (playBtn) {
          playBtn.style.display = "flex";
          playBtn.setAttribute('aria-label', 'Play video');
        }

        video.removeAttribute("controls");
      });

      // Handle video end
      video.addEventListener("ended", function () {
        const slide = video.closest(".swiper-slide");
        if (!slide) return;

        const content = slide.querySelector(".svts-card-content");
        const playBtn = slide.querySelector(".svts-play-btn");

        if (content) {
          content.classList.remove("hidden");
          content.setAttribute('aria-hidden', 'false');
        }
        if (playBtn) {
          playBtn.style.display = "flex";
          playBtn.setAttribute('aria-label', 'Replay video');
        }

        video.removeAttribute("controls");
      });

      // Handle video errors
      video.addEventListener("error", function () {
        console.warn("SVTS: Video failed to load", video.src);
        const slide = video.closest(".swiper-slide");
        if (!slide) return;

        const playBtn = slide.querySelector(".svts-play-btn");
        if (playBtn) {
          playBtn.style.display = "none";
        }
      });
    });

    /* -------------------------------
       Stop Videos When Clicking Outside
    -------------------------------- */
    document.addEventListener("click", function (e) {
      if (!section.contains(e.target)) {
        stopAllVideos(false); // pause only, don't reset
      }
    });

    /* -------------------------------
       Stop Videos On Slide Change
    -------------------------------- */
    swiper.on("slideChange", function () {
      stopAllVideos(false); // pause only
    });

    /* -------------------------------
       Keyboard Navigation Support
    -------------------------------- */
    section.addEventListener("keydown", function (e) {
      // Space or Enter on play button
      if ((e.key === ' ' || e.key === 'Enter')) {
        const playBtn = e.target.closest(".svts-play-btn");
        if (playBtn) {
          e.preventDefault();
          playBtn.click();
        }
      }
    });

    /* -------------------------------
       Initialize Video Preload
    -------------------------------- */
    // Preload first video metadata for better UX
    if (allVideos.length > 0) {
      allVideos[0].preload = 'metadata';
    }

    console.log("SVTS: Video testimonial slider initialized successfully");
  });

  /* -------------------------------
     Utility: Check WebP Support (for future enhancements)
  -------------------------------- */
  function supportsWebP() {
    const canvas = document.createElement('canvas');
    return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
  }

})();
