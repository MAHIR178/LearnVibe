// SIMPLE VERSION: search_courses.js
// This makes the search work without page reload

// Wait for page to load
document.addEventListener('DOMContentLoaded', startSearch);

function startSearch() {
    // Get the search input box
    const searchInput = document.getElementById("searchInput");
    const resultsDiv = document.getElementById("searchResults");
    
    // Check if elements exist
    if (!searchInput || !resultsDiv) return;
    
    // When user types
    searchInput.addEventListener("input", handleSearch);
    
    function handleSearch() {
        const searchText = searchInput.value.trim();
        
        // If empty, hide results
        if (!searchText) {
            resultsDiv.style.display = "none";
            return;
        }
        
        // Wait a bit before searching
        setTimeout(() => searchCourses(searchText), 300);
    }
    
    function searchCourses(query) {
        // Build the URL
        const url = window.location.pathname + "?search_query=" + encodeURIComponent(query);
        
        // Send AJAX request
        fetch(url)
            .then(response => response.json())
            .then(showResults)
            .catch(() => {
                resultsDiv.style.display = "none";
            });
    }
    
    function showResults(courses) {
        // Clear old results
        resultsDiv.innerHTML = "";
        
        if (courses.length === 0) {
            resultsDiv.innerHTML = '<div class="no-results">No courses found</div>';
        } else {
            courses.forEach(course => {
                const div = document.createElement("div");
                div.className = "search-result-item";
                div.textContent = course.title; // Just show title
                
                // Make clickable
                div.onclick = () => {
                    window.location.href = 
                        "../../Instructor/View/course_files.php?course=" + 
                        encodeURIComponent(course.slug);
                };
                
                resultsDiv.appendChild(div);
            });
        }
        
        // Show results
        resultsDiv.style.display = "block";
    }
    
    // Hide results when clicking elsewhere
    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = "none";
        }
    });
}