class AlertSystem {
    /**
     * Initializes the AlertSystem by selecting DOM elements for the alert,
     * its components, and setting up an event listener on the close button
     * to hide the alert when clicked.
     */
    constructor() {
        this.$alert = $("#alert-info");
        this.$box = $("#alert-box");
        this.$icon = $("#alert-icon");
        this.$message = $("#alert-message");
        this.$close = $("#alert-close");

        // Event: klik tombol close
        this.$close.on("click", () => this.hide());
    }

    /**
     * Returns the style object for the given alert type.
     * If the type is not found, it defaults to "info".
     * The style object contains the tailwindcss classnames for the background, text, and ring of the alert.
     * @param {string} type The type of alert to get the style for
     * @returns {object} The style object with the classnames for bg, text, and ring
     */
    getAlertStyle(type) {
        const styles = {
            success: {
                bg: "bg-green-100",
                text: "text-green-800",
                ring: "ring-green-300",
                icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM17.4571 9.45711L11 15.9142L6.79289 11.7071L8.20711 10.2929L11 13.0858L16.0429 8.04289L17.4571 9.45711Z"></path></svg>`,
            },
            error: {
                bg: "bg-red-100",
                text: "text-red-800",
                ring: "ring-red-300",
                icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 10.5858L9.17157 7.75736L7.75736 9.17157L10.5858 12L7.75736 14.8284L9.17157 16.2426L12 13.4142L14.8284 16.2426L16.2426 14.8284L13.4142 12L16.2426 9.17157L14.8284 7.75736L12 10.5858Z"></path></svg>`,
            },
            warning: {
                bg: "bg-yellow-100",
                text: "text-yellow-800",
                ring: "ring-yellow-300",
                icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 15H13V17H11V15ZM11 7H13V13H11V7Z"></path></svg>`,
            },
            info: {
                bg: "bg-blue-100",
                text: "text-blue-800",
                ring: "ring-blue-300",
                icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 7H13V9H11V7ZM11 11H13V17H11V11Z"></path></svg>`,
            },
            getting: {
                bg: "bg-blue-100",
                text: "text-blue-800",
                ring: "ring-blue-300",
                icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C12.5523 2 13 2.44772 13 3V6C13 6.55228 12.5523 7 12 7C11.4477 7 11 6.55228 11 6V3C11 2.44772 11.4477 2 12 2ZM12 17C12.5523 17 13 17.4477 13 18V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V18C11 17.4477 11.4477 17 12 17ZM22 12C22 12.5523 21.5523 13 21 13H18C17.4477 13 17 12.5523 17 12C17 11.4477 17.4477 11 18 11H21C21.5523 11 22 11.4477 22 12ZM7 12C7 12.5523 6.55228 13 6 13H3C2.44772 13 2 12.5523 2 12C2 11.4477 2.44772 11 3 11H6C6.55228 11 7 11.4477 7 12ZM19.0711 19.0711C18.6805 19.4616 18.0474 19.4616 17.6569 19.0711L15.5355 16.9497C15.145 16.5592 15.145 15.9261 15.5355 15.5355C15.9261 15.145 16.5592 15.145 16.9497 15.5355L19.0711 17.6569C19.4616 18.0474 19.4616 18.6805 19.0711 19.0711ZM8.46447 8.46447C8.07394 8.85499 7.44078 8.85499 7.05025 8.46447L4.92893 6.34315C4.53841 5.95262 4.53841 5.31946 4.92893 4.92893C5.31946 4.53841 5.95262 4.53841 6.34315 4.92893L8.46447 7.05025C8.85499 7.44078 8.85499 8.07394 8.46447 8.46447ZM4.92893 19.0711C4.53841 18.6805 4.53841 18.0474 4.92893 17.6569L7.05025 15.5355C7.44078 15.145 8.07394 15.145 8.46447 15.5355C8.85499 15.9261 8.85499 16.5592 8.46447 16.9497L6.34315 19.0711C5.95262 19.4616 5.31946 19.4616 4.92893 19.0711ZM15.5355 8.46447C15.145 8.07394 15.145 7.44078 15.5355 7.05025L17.6569 4.92893C18.0474 4.53841 18.6805 4.53841 19.0711 4.92893C19.4616 5.31946 19.4616 5.95262 19.0711 6.34315L16.9497 8.46447C16.5592 8.85499 15.9261 8.85499 15.5355 8.46447Z"></path></svg>`,
            },
        };

        return styles[type] || styles["info"];
    }

    /**
     * Displays an alert message with the specified type, message, and duration.
     *
     * @param {string} type - The type of alert (e.g., "success", "error", "warning", "info").
     * @param {string} message - The message to display in the alert.
     * @param {number} [duration=3000] - The duration (in milliseconds) for which the alert is visible before auto-hiding.
     */
    show(type, message, duration = 3000) {
        const style = this.getAlertStyle(type);

        // Set isi pesan dan icon
        this.$message.text(message);
        this.$icon.html(style.icon);

        // Set warna dan style alert
        this.$box.removeClass().addClass(`flex items-center gap-3 rounded-lg px-4 py-3 text-sm shadow-lg ring-1 ${style.bg} ${style.text} ${style.ring}`);

        // Tampilkan alert (state awal hidden)
        this.$alert.removeClass("hidden").addClass("opacity-0 -translate-y-5");

        // Trigger animasi in dengan delay 1 frame
        setTimeout(() => {
            this.$alert.removeClass("opacity-0 -translate-y-5").addClass("opacity-100 translate-y-0");
        }, 10);

        // Auto hide jika ada durasi
        clearTimeout(this.hideTimeout);
        this.hideTimeout = setTimeout(() => this.hide(), duration);
    }

    /**
     * Hides the alert by transitioning its opacity and position,
     * and then adding the "hidden" class after a delay to complete the animation.
     */
    hide() {
        this.$alert.removeClass("opacity-100 translate-y-0").addClass("opacity-0 -translate-y-5");
        setTimeout(() => {
            this.$alert.addClass("hidden");
        }, 600);
    }
}

// Inisialisasi object alert
const myAlert = new AlertSystem();

/**
 * Toggle visibility of a panel and its toggle button.
 *
 * @param {string} panelId - ID of the panel to toggle.
 * @param {Element} toggleButton - The button to toggle the panel.
 */
function togglePanel(panelId, toggleButton) {
    const panel = $("#" + panelId);
    panel.toggleClass("hidden");
    $(toggleButton).toggleClass("active");
    panel
        .find("button.close-panel")
        .off("click")
        .on("click", function () {
            panel.addClass("hidden");
            $(toggleButton).removeClass("active");
        });
}

$("#search-toggle").click(function (e) {
    togglePanel("searchPanel", this);
});
$("#layer-toggle").click(function (e) {
    togglePanel("layerPanel", this);
});
