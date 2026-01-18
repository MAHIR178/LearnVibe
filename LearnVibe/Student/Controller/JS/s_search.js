// s_search.js - AJAX search functionality for course dashboard

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    console.log("s_search.js loaded");
    console.log("Search input found:", searchInput);
    console.log("Search results container found:", searchResults);

    if (!searchInput || !searchResults) {
        console.warn('Search elements not found on this page');
        return;
    }

    let timer = null;
    const SEARCH_URL = window.location.pathname;
    console.log("Search URL:", SEARCH_URL);

    searchInput.addEventListener("input", () => {
        console.log("Input event triggered");
        clearTimeout(timer);

        const q = searchInput.value.trim();
        console.log("Search query:", q);

        if (q === "") {
            searchResults.style.display = "none";
            searchResults.innerHTML = "";
            return;
        }

        timer = setTimeout(() => {
            console.log("Fetching results for:", q);
            const url = SEARCH_URL + "?search_query=" + encodeURIComponent(q);
            console.log("Fetch URL:", url);

            fetch(url)
                .then(r => {
                    console.log("Response received:", r.status);
                    return r.json();
                })
                .then(data => {
                    console.log("Data received:", data);
                    searchResults.innerHTML = "";

                    if (data.length === 0) {
                        const noResults = document.createElement("div");
                        noResults.className = "no-results";
                        noResults.textContent = "No courses found";
                        searchResults.appendChild(noResults);
                    } else {
                        data.forEach(course => {
                            console.log("Processing course:", course);
                            const div = document.createElement("div");
                            div.className = "search-result-item";

                            // Only show title (without file count)
                            div.textContent = course.title;

                            div.onclick = () => {
                                console.log("Clicked on course:", course.slug);
                                window.location.href = "../../Instructor/View/course_files.php?course=" + encodeURIComponent(course.slug);
                            };

                            searchResults.appendChild(div);
                        });
                    }

                    searchResults.style.display = "block";
                    console.log("Displayed results:", data.length);
                })
                .catch((error) => {
                    console.error("Fetch error:", error);
                    searchResults.style.display = "none";
                });
        }, 250);
    });

    // hide results when clicking outside
    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = "none";
        }
    });
});