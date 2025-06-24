// sidebar.js
import { withLoader } from "./main.js";

export function initSidebar() {
    const contentDiv = document.getElementById("main-content");
    let currentPath = window.location.pathname + window.location.search;

    // Setup navigation link click handlers
    document.querySelectorAll(".links a[data-url]").forEach((link) => {
        const url = link.getAttribute("data-url");
        if (!url) return;

        link.addEventListener("click", (e) => {
            e.preventDefault();

            // Don't re-fetch if the URL matches the current path
            if (url === currentPath) return;

            withLoader(async () => {
                const res = await fetch(url, {
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                });
                if (!res.ok) throw new Error("Network error");
                const html = await res.text();
                contentDiv.innerHTML = html;
                history.pushState(null, "", url);
                currentPath = url;
                setActiveLink(link);
            }).catch((err) => {
                contentDiv.innerHTML = `<div class="text-red-600">⚠️ Failed to load content: ${err.message}</div>`;
            });
        });
    });

    // Initial link highlight on page load
    highlightCurrentLink(currentPath);
}

// --- Helper functions ---

function setActiveLink(clickedLink) {
    document
        .querySelectorAll(".links li")
        .forEach((li) => li.classList.remove("active"));
    clickedLink.closest("li")?.classList.add("active");
}

function highlightCurrentLink(path) {
    const links = Array.from(document.querySelectorAll(".links a[data-url]"));

    const activeLink = links.find((link) => {
        try {
            const fullUrl = new URL(
                link.getAttribute("data-url"),
                window.location.origin
            );

            const linkPath = fullUrl.pathname + fullUrl.search;
            return linkPath === path;
        } catch {
            return false;
        }
    });

    if (activeLink) {
        setActiveLink(activeLink);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const toggleLinks = document.querySelectorAll(".toggle-sub-menu");

    toggleLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const submenu =
                this.closest(".list-menu").querySelector(".sub-menu");
            const icon = this.closest(".list-menu").querySelector("a i");

            submenu.classList.toggle("open");
        });
    });
});
