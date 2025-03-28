<header>
    <div class="header_nav">
        <div class="leftmenu">
                <a class="backbutton <% if URLSegment == 'home' %>backbutton--hidden<% end_if %>" onclick="window.history.back();">
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M24 40 8 24 24 8l2.1 2.1-12.4 12.4H40v3H13.7l12.4 12.4Z"/></svg>
                </a>
        </div>
        <h1 class="header_nav_title">$Top.Title</h1>
        <% if $CurrentUser %>
            <a class="userimage" href="$CurrentUser.ProfileLink" data-behaviour="open_personalnav">
                <div class="avatar_image">
                    <img src="$CurrentUser.getProfileImage(50)" alt="Avatar of $CurrentUser.Nickname">
                </div>
            </a>
        <% else %>
            <a class="userimage" href="$RegistrationPage.Link" aria-label="Login to page">
                <svg width="100%" height="100%" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                    <path fill="currentColor" d="M11.1,35.25C13.2,33.917 15.275,32.908 17.325,32.225C19.375,31.542 21.6,31.2 24,31.2C26.4,31.2 28.633,31.542 30.7,32.225C32.767,32.908 34.85,33.917 36.95,35.25C38.417,33.45 39.458,31.633 40.075,29.8C40.692,27.967 41,26.033 41,24C41,19.167 39.375,15.125 36.125,11.875C32.875,8.625 28.833,7 24,7C19.167,7 15.125,8.625 11.875,11.875C8.625,15.125 7,19.167 7,24C7,26.033 7.317,27.967 7.95,29.8C8.583,31.633 9.633,33.45 11.1,35.25ZM24,25.5C22.067,25.5 20.442,24.842 19.125,23.525C17.808,22.208 17.15,20.583 17.15,18.65C17.15,16.717 17.808,15.092 19.125,13.775C20.442,12.458 22.067,11.8 24,11.8C25.933,11.8 27.558,12.458 28.875,13.775C30.192,15.092 30.85,16.717 30.85,18.65C30.85,20.583 30.192,22.208 28.875,23.525C27.558,24.842 25.933,25.5 24,25.5ZM24,44C21.2,44 18.583,43.475 16.15,42.425C13.717,41.375 11.6,39.942 9.8,38.125C8,36.308 6.583,34.183 5.55,31.75C4.517,29.317 4,26.717 4,23.95C4,21.217 4.525,18.633 5.575,16.2C6.625,13.767 8.058,11.65 9.875,9.85C11.692,8.05 13.817,6.625 16.25,5.575C18.683,4.525 21.283,4 24.05,4C26.783,4 29.367,4.525 31.8,5.575C34.233,6.625 36.35,8.05 38.15,9.85C39.95,11.65 41.375,13.767 42.425,16.2C43.475,18.633 44,21.233 44,24C44,26.733 43.475,29.317 42.425,31.75C41.375,34.183 39.95,36.308 38.15,38.125C36.35,39.942 34.233,41.375 31.8,42.425C29.367,43.475 26.767,44 24,44Z" style="fill-rule:nonzero;"/>
                </svg>
            </a>
        <% end_if %>
    </div>
</header>

<% if not $CurrentUser %>
    <div class="login_note">
        <p class="centered">
            <a href="./home/login" class="button login_link">Log in</a> or <a href="$RegistrationPage.Link" class="button login_link">Register</a> to save your experiences.
        </p>
    </div>
<% end_if %>
