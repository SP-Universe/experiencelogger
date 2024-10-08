<div class="section section--profile profile">
    <div class="section_content">

        <% if $Error %>
            <div class="error">
                <p>$Error</p>
            </div>
        <% end_if %>

        <% if not $CurrentUser %>
            <div class="login_note">
                <p class="centered">
                    <a href="./home/login" class="button login_link">Log in</a> or <a href="$RegistrationPage.Link" class="button login_link">Register</a> to see any profiles.
                </p>
            </div>
        <% else_if $UserProfile.Nickname == $CurrentUser.Nickname %>

            <div class="profile_settings live" id="profileSettings">
                <% cached %><% include ProfileCard %><% end_cached %>
            </div>

            <div class="profile_settings edit">
                $EditForm
                <a class="button profile_edit_button" data-behaviour="profile_canceledit_button">Cancel edit</a>
            </div>

            <div class="profile_actions">
                <a class="button profile_edit_button" data-behaviour="profile_edit_button">Edit Profile</a>
            </div>

            <!-- Friend Requests -->
            <% if $UserProfile.FriendRequests.Count > 0 %>
                <div class="profile_friendrequests">
                    <h3 class="profile_section_headline">$UserProfile.FriendRequests.Count new <% if $UserProfile.FriendRequests.Count > 1 %> friendrequests:<% else %> friendrequest:<% end_if %></h3>
                    <div class="profile_friendrequests_list">
                        <% loop $UserProfile.FriendRequests %>
                            <div class="usercard wide">
                                <div class="usercard_wrap">
                                    <div class="usercard_content">
                                        <div class="usercard_content_avatar">
                                            <img src="$Requester.getProfileImage(150)" class="<% if $HasPremium %>premium<% end_if %>" alt="Avatar of $Nickname">
                                            <% if $HasPremium %>
                                                <div class="usercard_premium">
                                                    <svg fill="#fff" width="100%" height="100%" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="m11.65 44 3.25-14.05L4 20.5l14.4-1.25L24 6l5.6 13.25L44 20.5l-10.9 9.45L36.35 44 24 36.55Z"/></svg>
                                                </div>
                                            <% end_if %>
                                        </div>
                                        <div class="usercard_text">
                                            <p class="usercard_name">@$Requester.Nickname</p>
                                            <p>wants to ride with you!</p>
                                            <div class="usercard_actions">
                                                <a href="$Top.Link('acceptfriend')/$ID" class="button usercard_action_button">Accept</a>
                                                <a href="$Top.Link('declinefriend')/$ID" class="button usercard_action_button">Decline</a>
                                                <% if $Requester.ProfilePrivacy == "Public" || $Requester.ProfilePrivacy == "Friends" %>
                                                    <a href="$Top.Link()/user/$Requester.Nickname" class="button usercard_action_button">View</a>
                                                <% else %>
                                                    <p>(Private)</p>
                                                <% end_if %>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <% end_loop %>
                    </div>
                </div>
            <% end_if %>

            <% if $UserProfile.PendingFriendRequests.Count > 0 %>
                <div class="profile_friendrequests">
                    <h3 class="profile_section_headline">$UserProfile.PendingFriendRequests.Count pending <% if $UserProfile.PendingFriendRequests.Count > 1 %> friendrequests:<% else %> friendrequest:<% end_if %></h1>
                    <div class="profile_friendrequests_list">
                        <% loop $UserProfile.PendingFriendRequests %>
                            <div class="usercard">
                                <div class="usercard_wrap">
                                    <div class="usercard_content">
                                        <div class="usercard_content_avatar">
                                            <img src="$Requestee.getProfileImage(150)" class="<% if $Requestee.HasPremium %>premium<% end_if %>" alt="Avatar of $Nickname">
                                            <% if $Requestee.HasPremium %>
                                                <div class="usercard_premium">
                                                    <svg fill="#fff" width="100%" height="100%" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="m11.65 44 3.25-14.05L4 20.5l14.4-1.25L24 6l5.6 13.25L44 20.5l-10.9 9.45L36.35 44 24 36.55Z"/></svg>
                                                </div>
                                            <% end_if %>
                                        </div>
                                        <div class="usercard_text">
                                            <p class="usercard_name">$Requestee.Nickname</p>
                                            <div class="usercard_actions">
                                                <a href="$Top.Link('declinefriend')/$ID" class="button usercard_action_button">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <% end_loop %>
                    </div>
                </div>
            <% end_if %>
            <!-- End Friend Requests -->

            <% cached %>
                <h3 class="profile_section_headline">Your friends:</h3>
                <div class="profile_friendslist">
                    <% loop $UserProfile.getFriends %>
                        <% include UserCard HideFriendAdd=true %>
                    <% end_loop %>
                </div>
            <% end_cached %>

            <a href="$Top.Link('memberlist')" class="button centered profile_addfriend_button">Add new Friend</a>

        <% else %>
            <% if $UserProfile.ProfilePrivacy == "Private" %>
                <h1>Profile is private</h1>
                <p>This User doesn't share its profile.</p>
            <% else_if $UserProfile.ProfilePrivacy == "Friends" %>
                <% if $UserProfile.IsFriendWithCurrentUser %>
                    <div class="profile_settings live" id="profileSettings">
                        <% include ProfileCard %>
                    </div>
                <% else %>
                    <h1>Profile is private</h1>
                    <p>This User shares its profile only with friends.</p>
                <% end_if %>
            <% else %>
                <div class="profile_settings live" id="profileSettings">
                    <% include ProfileCard %>
                </div>
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
