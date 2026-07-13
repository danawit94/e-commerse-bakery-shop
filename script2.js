document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".popular-slider-track");
    const leftArrow = document.querySelector(".left-arrow");
    const rightArrow = document.querySelector(".right-arrow");

    if (track && leftArrow && rightArrow) {
        // Scroll right when clicking the right chevron arrow
        rightArrow.addEventListener("click", function () {
            track.scrollBy({ left: 300, behavior: "smooth" });
        });

        // Scroll left when clicking the left chevron arrow
        leftArrow.addEventListener("click", function () {
            track.scrollBy({ left: -300, behavior: "smooth" });
        });
    }
});
