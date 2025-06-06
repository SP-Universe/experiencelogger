<!DOCTYPE html>
<html lang="en">
    <head>
        <% base_tag %>
        $MetaTags(false)
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charset="utf-8">
        <title>$Title - $SiteConfig.Title</title>
        $ViteClient.RAW
        <link rel="stylesheet" href="$Vite('app/client/src/scss/main.scss')">

        <meta name="description" content="Log all your experiences in one place, organize them and get insights over your visited places.">
        <link rel="icon" type="image/png" sizes="32x32" href="_resources/app/client/src/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="_resources/app/client/src/images/favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">
        <link rel="mask-icon" href="_resources/app/client/src/images/safari-pinned-tab.svg" color="#266056">
        <meta name="msapplication-TileColor" content="#266056">
        <meta name="theme-color" content="#266056">

        <!-- iOS Support -->
        <link rel="apple-touch-icon" sizes="180x180" href="_resources/app/client/src/images/apple-touch-icon.png">
        <meta name="apple-mobile-web-app-status-bar" content="#266056">
        <%-- <script src="./_resources/app/client/dist/js/jquery-3.6.2.min.js"></script> --%>
    </head>
    <body class="theme--new">
        <div class="loading_screen">
            <div class="loading_screen_wave top">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                    <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                    <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
                </svg>
            </div>
            <div class="loading_screen_wrap">
                <% include XPLLogo %>
                <p class="loading_screen_text">$LoadingText</p>
            </div>
            <div class="loading_screen_wave bottom">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                    <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                    <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
                </svg>
            </div>
        </div>
        <% include Header %>
        $Layout

        <% include Navigation_MainNav %>

        <% if not $CurrentUser || $CurrentUser.HasAcceptedCookies %>
            <div class="cookie_accept_prompt <% if $HasAcceptedCookies %>hide<% end_if %>">
                <div class="cookie_accept_prompt__content">
                    <p class="cookie_accept_prompt__text">For most of our features to work you need to enable cookies to stay logged in. We don't track any behaviour and don't use ads on this site.</p>
                    <button class="cookie_accept_prompt__button" data-behaviour="cookie_accept_button">Accept functional cookies</button>
                </div>
            </div>
        <% end_if %>

        <script src="$Mix('/js/main.js')"></script>
        <script type="module" src="$Vite('app/client/src/js/main.js')"></script>
    </body>
</html>
