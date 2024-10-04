/*Js script test examples for fetching the json data*/
document.getElementById("searchBtn").addEventListener("click", function () {
    //html elements
    const query = document.getElementById("input").value.trim();
    const searchResults = document.getElementById("searchResults");
    const loader = document.getElementById("loader");

    if (query !== "") {
        // Show loader by adding the active class doesn't matter tho you can
       // delete 
        loader.classList.add("active");
        searchResults.innerHTML = ""; // Clear previous results

        // Send AJAX request to PHP script
        fetch("controllers/fetch.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                query: query
            })
        })
            .then(response => response.text())
            .then(data => {
                // Hide loader by removing the active class
                loader.classList.remove("active");

                // Display the search results in the HTML structure
                searchResults.innerHTML = data;
            })
            .catch(error => {
                console.error("Error:", error);
                loader.classList.remove("active");
                searchResults.innerHTML =
                    "<p>Error fetching the data. Please try again.</p>";
            });
    } else {
        alert("Please enter a search query.");
    }
});
//and that's it you can use this basic structure to map out your front end