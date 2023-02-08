<div class="section section--profile profile">
    <div class="section_content">

        <% if $CurrentUser %>
            <h1>Your Profile</h1>
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
                <p><span>Birthdate:</span> $CurrentUser.DateOfBirth<p>
                    <div class="profile_privacy">
                        <p>Profile-Privacy:</P>
                        <% if $CurrentUser.ProfilePrivacy == "Private" %><img src="../_resources/app/client/icons/lock.svg" alt="Private Profile"/><p>Private</p><% end_if %>
                        <% if $CurrentUser.ProfilePrivacy == "Friends" %><img src="../_resources/app/client/icons/friends.svg" alt="Friends Profile"/><p>Friends</p><% end_if %>
                        <% if $CurrentUser.ProfilePrivacy == "Public" %><img src="../_resources/app/client/icons/lock_open.svg" alt="Public Profile"/><p>Public</p><% end_if %>
                    </div>
                <a class="button profile_edit_button" data-behaviour="profile_edit_button">Edit Profile</a>
            </div>

            <div class="profile_settings edit">
                $EditForm
                <a class="button profile_edit_button" data-behaviour="profile_canceledit_button">Cancel edit</a>
            </div>


            <h3 class="profile_section_headline">Your friends:</h3>
            <div class="profile_friendslist">
                <% loop $Friends %>
                    <div class="profile_friend">
                        <div class="avatar_image">
                            <img src="$getProfileImage(200)" alt="Avatar of $Nickname">
                        </div>
                        <p><% if $ProfilePrivacy == "Public" || $ProfilePrivacy == "Friends" %>($getLogs($ID).Count Logs)<% else %>(Private)<% end_if %></p>
                        <p class="friend_name">$Nickname</p>
                    </div>
                <% end_loop %>
            </div>

            <h3 class="profile_section_headline">Your Logs:</h3>
            <div class="profile_loglist">
                <p>Logged Experiences: $Logs.Count</p>
                <% loop $Logs.GroupedBy(VisitDate) %>
                    <div class="logs_date">
                        <h4>$VisitDate <span>$Children.Count Experiences</span></h4>
                        <% loop $Children %>
                            <% include LogCard %>
                        <% end_loop %>
                    </div>
                <% end_loop %>
            </div>
        <% else %>
            <div class="login_note">
                <p class="centered">
                    <a href="Security/login" class="button login_link">Log in</a> or <a href="$RegistrationPage.Link" class="button login_link">Register</a> to see your profile.
                </p>
            </div>
        <% end_if %>
    </div>
</div>


<script>
    function changeProfile(){
        var profiledisplay = document.getElementById("profileSettings");
        profiledisplay.classList.toggle("edit");
    }
</script>
