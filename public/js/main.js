// main.js
import { initSidebar } from "./sidebar.js";

let loaderInterval;

const navToggle = document.querySelector('[aria-controls="primary-side"]');

navToggle.addEventListener("click", () => {
    const navOpened = navToggle.getAttribute("aria-expanded");

    if (navOpened === "false") {
        navToggle.setAttribute("aria-expanded", true);
    } else {
        navToggle.setAttribute("aria-expanded", false);
    }
    console.log(navOpened);
});

const resizeObserver = new ResizeObserver((entries) => {
    document.body.classList.add("resizing");

    requestAnimationFrame(() => {
        document.body.classList.remove("resizing");
    });
});

resizeObserver.observe(document.body);

export function showLoader() {
    const loader = document.querySelector(".loader");
    if (!loader) return;

    loader.style.display = "block";
    loader.style.width = "0%";

    let progress = 0;
    loaderInterval = setInterval(() => {
        if (progress < 90) {
            progress += Math.random() * 10;
            loader.style.width = `${progress}%`;
        }
    }, 100);
}

export function hideLoader() {
    const loader = document.querySelector(".loader");
    if (!loader) return;

    clearInterval(loaderInterval);
    loader.style.width = "100%";

    setTimeout(() => {
        loader.style.display = "none";
        loader.style.width = "0%";
    }, 300);
}

export async function withLoader(asyncFunc) {
    try {
        showLoader();
        return await asyncFunc();
    } finally {
        hideLoader();
    }
}

// Initial load
document.addEventListener("DOMContentLoaded", () => {
    showLoader();
    initSidebar(); // handles sidebar + initial highlight
    initAjaxPagination();

    window.rebindAjaxPagination = initAjaxPagination;

    function initAjaxPagination() {
        const contentDiv = document.getElementById("main-content");

        document
            .querySelectorAll('a.pagination-link[data-ajax="true"]')
            .forEach((link) => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const url = this.href;

                    showLoader();
                    fetch(url, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                        },
                    })
                        .then((res) => res.text())
                        .then((html) => {
                            contentDiv.innerHTML = html;

                            // Rebind pagination links after new content is loaded
                            if (
                                typeof window.rebindAjaxPagination ===
                                "function"
                            ) {
                                window.rebindAjaxPagination();
                            }

                            // Optional: Update URL without reload
                            history.pushState(null, "", url);
                        })
                        .catch((err) => {
                            contentDiv.innerHTML = `<div class="text-red-600">⚠️ Error: ${err.message}</div>`;
                        });
                });
                hideLoader();
            });
    }
});

window.addEventListener("load", () => {
    hideLoader();
});

export function showAjaxMessage(message, type = "success", timeout = 5000) {
    {
        const div = document.getElementById("ajax-response-message");
        if (!div) return;

        div.className = `ajax-response-message alert alert-${type}`;
        div.innerHTML = message; // 👈 Allows HTML
        div.hidden = false;

        setTimeout(() => {
            div.hidden = true;
        }, timeout);
    }
}

export function ajaxNavigate(url, targetSelector = "#main-content") {
    const target = document.querySelector(targetSelector);

    if (!target || !url) return;

    fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((res) => {
            if (!res.ok) throw new Error("Network error");
            return res.text();
        })
        .then((html) => {
            target.innerHTML = html;
            history.pushState(null, "", url);
            // Re-init any dynamic JS (like pagination or toggles)
            if (typeof window.rebindAjaxPagination === "function") {
                window.rebindAjaxPagination();
            }
        })
        .catch((err) => {
            target.innerHTML = `<div class="text-red-600">⚠️ ${err.message}</div>`;
        });
}

document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (e) {
        const link = e.target.closest("a[data-ajax-link]");
        if (!link) return;

        e.preventDefault();
        const url = link.getAttribute("href");
        ajaxNavigate(url); // Uses default target: #main-content
    });
});
