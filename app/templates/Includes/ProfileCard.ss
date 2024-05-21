<% with $UserProfile %>
    <div class="profile_card <% if $HasPremium %>profile_card--premium<% end_if %>" data-behavior="card">
        <div class="profile_card_head">
            <h2 class="profile_card_head_nickname">$Displayname</h2>
            <div class="avatar_image profile_card_head_image <% if $HasPremium %>premium<% end_if %>">
                <img src="$getProfileImage(200)" alt="Avatar of $Nickname">
                <% if $HasPremium %>
                    <div class="profile_card_head_premium">
                        <svg fill="#fff" width="100%" height="100%" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="m11.65 44 3.25-14.05L4 20.5l14.4-1.25L24 6l5.6 13.25L44 20.5l-10.9 9.45L36.35 44 24 36.55Z"/></svg>
                    </div>
                <% end_if %>
            </div>
        </div>
        <div class="profile_card_body">
            <p><b>Registered since:</b> $Created.Format("dd.MM.YYYY")<p>
            <p><b>Last logged at:</b> $LastLogDate.Format("dd.MM.YYYY")<p>
            <p>$CoasterCount.Count Unique Coasters</p>
            <p>$LoggedParksCount Places</p>
            <p>$Logs.Count Logs</p>
        </div>
        <div class="profile_card_footer">
            <div class="profile_privacy">
                <p>Profile-Privacy:</P>
                <% if $ProfilePrivacy == "Private" %><img src="../_resources/app/client/icons/lock.svg" alt="Private Profile"/><p>Private</p><% end_if %>
                <% if $ProfilePrivacy == "Friends" %><img src="../_resources/app/client/icons/friends.svg" alt="Friends Profile"/><p>Friends</p><% end_if %>
                <% if $ProfilePrivacy == "Public" %><img src="../_resources/app/client/icons/lock_open.svg" alt="Public Profile"/><p>Public</p><% end_if %>
            </div>
            <div class="profile_card_footer_buttons">
                <a href="$StatisticsLink" class="profile_card_footer_button">Statistics</a>
                <!--<a class="profile_card_footer_button">Achievements</a>-->
                <a class="profile_card_footer_button" data-behaviour="share_button" data-link="$Top.AbsoluteLink">Share</a>
            </div>
        </div>
        <div class="profile_card_username">
            <p class="profile_card_username_text">@$Nickname</p>
        </div>

        <span class="background_design"></span>
        <span class="background_design"></span>
        <span class="background_design"></span>
        <span class="background_design top"></span>
        <span class="background_design top"></span>
        <span class="background_design top"></span>
    <div class="glow"></div>
    </div>
<% end_with %>
