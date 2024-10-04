<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>LyricLens</title>
        <link rel="stylesheet" href="styles/style.css" />
        <link
            rel="stylesheet"
            href="assets/vendors/styles/icon-font.min.css"
            type="text/css"
            media="all"
        />
    </head>
    <body>
        <nav class="nav">
            <div class="content">
                <div class="logo">LyricLens</div>
                <div class="side">
                    <i class="fa fa-bars" id="toggleSidebarBtn"></i>
                </div>
            </div>
        </nav>

        <section class="main-content">
            <div class="search_results" id="searchResults"></div>

            <div class="search_query">
                <div class="input_field">
                    <input
                        type="text"
                        name="query"
                        id="input"
                        placeholder="Describe your music lyrics"
                    />
                    <div id="searchBtn" class="mic-icon">
                        <i class="icon-copy dw dw-paper-plane1"></i>
                    </div>
                </div>
            </div>
            <div id="loader" style="display:none" class="loader">
                <div
                    class="dot-spinner"
                    style="
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    "
                >
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                </div>
            </div>
        </section>
        <script src="js/script.js"></script>
    </body>
</html>
