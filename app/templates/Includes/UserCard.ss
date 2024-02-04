<div class="usercard">
    <div class="usercard_wrap">
        <div class="usercard_content">
            <div class="usercard_content_avatar">
                <img src="$getProfileImage(80)" alt="Avatar of $Nickname">
            </div>
            <div class="usercard_text">
                <p class="usercard_name">$Nickname</p>
                <p><% if $ProfilePrivacy == "Public" || $ProfilePrivacy == "Friends" %>($getLogs($ID).Count Logs)<% else %>(Private)<% end_if %></p>
            </div>
        </div>
        <div class="usercard_actions">
            <% if not $HideFriendAdd %><a href="$Top.Link('addfriend')" class="usercard_action_button">Add Friend</a><% end_if %>
            <% if $ProfilePrivacy == "Public" || $ProfilePrivacy == "Friends" %>
                <a href="$Top.Link()profile/user/$Nickname" class="usercard_action_button">View Profile</a>
            <% else %>
                <p>(Private)</p>
            <% end_if %>
        </div>
    </div>
</div>
