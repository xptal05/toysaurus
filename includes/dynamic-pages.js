
function updateURLParameter(param, value) {
    let url = new URL(window.location);
    url.searchParams.set(param, value);
    window.history.pushState({}, '', url);
}

// Function to switch pages without reload
function switchPage(section) {
    if (pages[section]) {
        document.getElementById('account-content').innerHTML = pages[section];
        history.pushState(null, "", "?section=" + section);
    } else {
        console.error("Page not found:", section);
    }
}

// Handle browser navigation (back/forward buttons)
window.addEventListener("popstate", function () {
    const params = new URLSearchParams(window.location.search);
    const subpage = params.get("section") || "info"; // Default to 'info'
    switchPage(subpage);
});
