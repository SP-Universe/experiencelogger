<div class="usercard">
    <div class="usercard_content">
        <div class="usercard_avatar">
            <img src="$getProfileImage(80)" alt="Avatar of $Nickname">
        </div>
        <div class="usercard_text">
            <p class="usercard_name">$Nickname</p>
            <p><% if $ProfilePrivacy == "Public" || $ProfilePrivacy == "Friends" %>($getLogs($ID).Count Logs)<% else %>(Private)<% end_if %></p>
        </div>
    </div>
</div>
