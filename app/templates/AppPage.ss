
<!DOCTYPE html>
<html lang="en">
    <head>
        <% base_tag %>
        $MetaTags(false)
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charset="utf-8">
        <title>ExperienceLogger</title>
        <meta name="description" content="Log all your experiences in one place, organize them and get insights over your visited places.">
        <link rel="icon" type="image/png" sizes="32x32" href="_resources/app/client/src/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="_resources/app/client/src/images/favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">
        <link rel="mask-icon" href="../../_resources/app/client/src/images/safari-pinned-tab.svg" color="#266056">
        <meta name="msapplication-TileColor" content="#266056">
        <meta name="theme-color" content="#266056">
        <link rel="stylesheet" href="$Mix('/css/styles.min.css')">

        <!-- iOS Support -->
        <link rel="apple-touch-icon" sizes="180x180" href="_resources/app/client/src/images/apple-touch-icon.png">
        <meta name="apple-mobile-web-app-status-bar" content="#266056">
    </head>
    <body>
        <div id="react-entry" <% if $CurrentUser %>data-loggedin="true"<% end_if %>></div>
        <script src="$Mix('/js/main.js')"></script>
    </body>
</html>