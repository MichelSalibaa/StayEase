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

            fetch("toggle_favorite.php?id=" + id)
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
    const modal = document.getElementById("bookingModal");

    if (openBtn) {
        openBtn.addEventListener("click", () => {
            modal.style.display = "flex";
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

});


