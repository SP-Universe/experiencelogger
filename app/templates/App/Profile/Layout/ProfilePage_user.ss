<div class="section section--profile profile">
    <div class="section_content">

        <% if not $CurrentUser %>
            <div class="login_note">
                <p class="centered">
                    <a href="Security/login" class="button login_link">Log in</a> or <a href="$RegistrationPage.Link" class="button login_link">Register</a> to see any profiles.
                </p>
            </div>
        <% else_if $UserProfile.Nickname == $CurrentUser.Nickname %>

            <h1>Hi, $UserProfile.Nickname!</h1>
            <div class="profile_settings live" id="profileSettings">
                <div class="avatar">
                    <div class="avatar_image">
                        <img src="$CurrentUser.getProfileImage(200)" alt="Avatar of $CurrentUser.Name">
                    </div>
                </div>
                <br>
                <p>$Logs.Count() Logs<p>
                <br>
                <p><span>Nickname:</span> $CurrentUser.Nickname</p>
                <p><span>Full Name:</span> $CurrentUser.FirstName $CurrentUser.LastName</p>
                <p><span>Email:</span> $CurrentUser.Email</p>
                <p><span>Birthdate:</span> $CurrentUser.DateOfBirth.Format("dd.MM.YYYY")<p>
                <p><span>Registered since:</span> $CurrentUser.Created.Format("dd.MM.YYYY")<p>
                <p><span>Last logged at:</span> $CurrentUser.LastLogDate.Format("dd.MM.YYYY")<p>
                <div class="profile_privacy">
                    <p>Profile-Privacy:</P>
                    <% if $CurrentUser.ProfilePrivacy == "Private" %><img src="../_resources/app/client/icons/lock.svg" alt="Private Profile"/><p>Private</p><% end_if %>
                    <% if $CurrentUser.ProfilePrivacy == "Friends" %><img src="../_resources/app/client/icons/friends.svg" alt="Friends Profile"/><p>Friends</p><% end_if %>
                    <% if $CurrentUser.ProfilePrivacy == "Public" %><img src="../_resources/app/client/icons/lock_open.svg" alt="Public Profile"/><p>Public</p><% end_if %>
                </div>
                <div class="profile_actions">
                    <% if $StatisticsLink %>
                        <a href="$StatisticsLink" class="button profile_statistics_button">Extended User Statistics</a>
                    <% end_if %>
                    <a class="button profile_edit_button" data-behaviour="button profile_edit_button">Edit Profile</a>
                </div>
            </div>

            <div class="profile_settings edit">
                $EditForm
                <a class="button profile_edit_button" data-behaviour="button profile_canceledit_button">Cancel edit</a>
            </div>

            <h3 class="profile_section_headline">Your friends:</h3>
            <div class="profile_friendslist">
                <% loop $Friends %>
                    <% include UserCard HideFriendAdd=true %>
                <% end_loop %>
            </div>

            <a href="$Top.Link('memberlist')" class="button profile_addfriend_button">Add new Friend</a>

        <% else %>
            <% if $UserProfile.ProfilePrivacy == "Private" && $UserProfile.Nickname != $CurrentUser.Nickname %>
                <h1>Profile is private</h1>
            <% end_if %>
        <% end_if %>
    </div>
</div>


<script>
    function changeProfile(){
        var profiledisplay = document.getElementById("profileSettings");
        profiledisplay.classList.toggle("edit");
    }
</script>
