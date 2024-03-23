<div class="usercard">
    <div class="usercard_wrap">
        <div class="usercard_content">
            <div class="usercard_content_avatar">
                <img src="$getProfileImage(80)" class="<% if $HasPremium %>premium<% end_if %>" alt="Avatar of $Nickname">
                <% if $HasPremium %>
                    <div class="usercard_premium">
                        <svg fill="#fff" width="100%" height="100%" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="m11.65 44 3.25-14.05L4 20.5l14.4-1.25L24 6l5.6 13.25L44 20.5l-10.9 9.45L36.35 44 24 36.55Z"/></svg>
                    </div>
                <% end_if %>
            </div>
            <div class="usercard_text">
                <p class="usercard_name">$Nickname</p>
                <p><% if $ProfilePrivacy == "Public" || $ProfilePrivacy == "Friends" %>($getLogs($ID).Count Logs | $LoggedParksCount Places)<% else %>(Private)<% end_if %></p>
            </div>
        </div>
        <div class="usercard_actions">
            <% if not $HideFriendAdd %><a href="$Top.Link()profile/requestnewfriend/$ID" class="usercard_action_button">Add Friend</a><% end_if %>
            <% if $ProfilePrivacy == "Public" || $ProfilePrivacy == "Friends" %>
                <a href="$Top.Link()profile/user/$Nickname" class="button usercard_action_button">View Profile</a>
            <% else %>
                <p>(Private)</p>
            <% end_if %>
        </div>
    </div>
</div>
