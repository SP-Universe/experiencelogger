<div class="section section--profile profile">
    <div class="section_content">

        <% if $CurrentUser %>
            <h1>Your Profile</h1>
            <div class="profile_settings" id="profileSettings">
                <div class="avatar">
                    <img src="$CurrentUser.Avatar.FocusFill(150,150).Url" alt="Avatar">
                </div>
                <br>
                <p><span>Nickname:</span> $CurrentUser.Nickname</p>
                <p><span>Full Name:</span> $CurrentUser.FirstName $CurrentUser.LastName</p>
                <p><span>Email:</span> $CurrentUser.Email</p>
                <p><span>Birthdate:</span> $CurrentUser.DateOfBirth<p>
                <a class="button" onClick="editProfile()">Edit Profile</a>
            </div>

            <h3 class="profile_section_headline">Your friends:</h3>
            <div class="profile_friendslist">
                <% loop $Friends %>
                    <div class="profile_friend">
                        <div class="profile_image">
                            <img src="$Avatar.FocusFill(100,100).Url" alt="$Name" />
                        </div>
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
