<!DOCTYPE html>
<html lang="en">
    <head>
        <% base_tag %>
        $MetaTags(false)
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charset="utf-8">
        <title>$Title - $SiteConfig.Title</title>
        <link rel="icon" type="image/png" sizes="32x32" href="_resources/app/client/src/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="_resources/app/client/src/images/favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">
        <link rel="mask-icon" href="_resources/app/client/src/images/safari-pinned-tab.svg" color="#266056">
        <meta name="msapplication-TileColor" content="#266056">
        <meta name="theme-color" content="#266056">
        <link rel="stylesheet" href="$Mix('/css/styles.min.css')">

        <!-- iOS Support -->
        <link rel="apple-touch-icon" sizes="180x180" href="_resources/app/client/src/images/apple-touch-icon.png">
        <meta name="apple-mobile-web-app-status-bar" content="#266056">
        <script src="./_resources/app/client/dist/js/jquery-3.6.2.min.js"></script>
    </head>
    <body class="<% if $Darkmode %>theme--dark<% end_if %>">
        <% include Header %>
        $Layout

        <div class="cookie_accept_prompt">
            <div class="cookie_accept_prompt__content">
                <p class="cookie_accept_prompt__text">For most of our features to work you need to enable cookies to stay logged in. We don't track any behaviour and don't use ads on this site.</p>
                <button class="cookie_accept_prompt__button" data-behaviour="cookie_accept_button">Accept functional cookies</button>
            </div>
        </div>

        <script src="$Mix('/js/main.js')"></script>
    </body>
</html>
