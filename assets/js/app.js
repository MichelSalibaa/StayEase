// assets/js/app.js

document.addEventListener("DOMContentLoaded", function () {
    /* -----------------------------------
       1) FAVORITES HEART TOGGLE
    ----------------------------------- */
    const heartButtons = document.querySelectorAll(".heart-btn");

    heartButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const el = this;

            if (!id) return;

            fetch("backend/toggle_favorite.php?id=" + id)
                .then(res => res.text())
                .then(data => {
                    if (data === "ADDED") {
                        el.textContent = "♥";
                    } else if (data === "REMOVED") {
                        el.textContent = "♡";
                    } else if (data === "LOGIN_REQUIRED") {
                        alert("You must log in to add favorites!");
                    }
                })
                .catch(err => console.error(err));
        });
    });


    /* -----------------------------------
       2) PRICE SLIDER LABELS
    ----------------------------------- */
    const minRange = document.getElementById("minPrice");
    const maxRange = document.getElementById("maxPrice");
    const minLabel = document.getElementById("minPriceLabel");
    const maxLabel = document.getElementById("maxPriceLabel");

    if (minRange && maxRange && minLabel && maxLabel) {
        const updatePriceLabels = () => {
            minLabel.textContent = `$${minRange.value}`;
            maxLabel.textContent = `$${maxRange.value}`;
        };

        minRange.addEventListener("input", updatePriceLabels);
        maxRange.addEventListener("input", updatePriceLabels);

        // initial values
        updatePriceLabels();
    }


    /* -----------------------------------
       3) ROOMS & BEDS COUNTERS
    ----------------------------------- */
    const counters = document.querySelectorAll(".counter");

    counters.forEach(counter => {
        const minus = counter.querySelector(".minus-btn");
        const plus = counter.querySelector(".plus-btn");
        const label = counter.querySelector(".count");

        if (!minus || !plus || !label) return;

        let value = 0;

        const updateLabel = () => {
            label.textContent = value === 0 ? "Any" : value;
        };

        minus.addEventListener("click", () => {
            if (value > 0) value--;
            updateLabel();
        });

        plus.addEventListener("click", () => {
            value++;
            updateLabel();
        });

        // initial text
        updateLabel();
    });

        /* -----------------------------------
    4) BLOCK BOOKING WHEN LOGGED OUT
    ----------------------------------- */
    const isLoggedIn = document.body.getAttribute("data-logged-in") === "1";

    document.querySelectorAll("a.book-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            if (!isLoggedIn) {
                e.preventDefault();
                alert("You must log in to book a property!");
            }
        });
    });

});

document.querySelectorAll(".filter-pill").forEach(pill => {
    pill.addEventListener("click", (e) => {
        e.preventDefault(); // stop form submitting while clicking
        const checkbox = pill.querySelector("input");
        checkbox.checked = !checkbox.checked;
        pill.classList.toggle("active");
    });
});
document.addEventListener("DOMContentLoaded", function () {

    // Booking popup
    const openBtn = document.getElementById("openBookingPopup");
    const closeBtn = document.getElementById("closeBookingPopup");
    const modal   = document.getElementById("bookingModal");

    if (openBtn && modal) {
        openBtn.addEventListener("click", () => {
            modal.style.display = "flex";
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", (e) => {
        if (modal && e.target === modal) modal.style.display = "none";
    });

    // ===========================
    //  DATE VALIDATION + BOOKINGS
    // ===========================
    const bookingForm = document.querySelector("#bookingModal form");
    const errorBox    = document.getElementById("bookingError");

    function overlapsBooked(checkIn, checkOut) {
        if (!Array.isArray(window.bookedRanges)) return false;

        const start = new Date(checkIn);
        const end   = new Date(checkOut);

        return window.bookedRanges.some(range => {
            const rStart = new Date(range.start);
            const rEnd   = new Date(range.end);

            return !(end <= rStart || start >= rEnd);
        });
    }

    if (bookingForm && errorBox) {
        bookingForm.addEventListener("submit", function (e) {
            const checkInEl  = document.getElementById("checkIn");
            const checkOutEl = document.getElementById("checkOut");

            const checkIn  = checkInEl.value;
            const checkOut = checkOutEl.value;

            if (checkOut <= checkIn) {
                e.preventDefault();
                errorBox.textContent = "Check-out date must be after check-in date.";
                errorBox.style.display = "block";
                return;
            }

            if (overlapsBooked(checkIn, checkOut)) {
                e.preventDefault();
                errorBox.textContent = "These dates are already booked. Choose different dates.";
                errorBox.style.display = "block";
                return;
            }

            errorBox.style.display = "none";
        });
    }

        // ===========================
    //  CUSTOM FILE UPLOAD DISPLAY
    // ===========================
    const fileInput = document.getElementById("mainImageInput");
    const fileNameText = document.getElementById("fileNameDisplay");

    if (fileInput && fileNameText) {
        fileInput.addEventListener("change", function () {
            const fileName = this.files.length > 0 ? this.files[0].name : "No file chosen";
            fileNameText.textContent = fileName;
        });
    }
});







