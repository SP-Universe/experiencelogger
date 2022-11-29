<div class="section section--profile profile">
    <div class="section_content">

        <h1>My Profile</h1>
        <h2>Hello $Nickname! How's it going?</h2>

        <h3>Your friends:</h3>
        <div class="profile_friendslist">
            <% loop $Friends %>
                <div class="profile_friend">
                    <div class="profile_image">
                        <img src="$Avatar.FocusFill(150,150).Url" alt="$Name" />
                    </div>
                    <h4>$Nickname</h4>
                </div>
            <% end_loop %>
        </div>

        <h3>Your Logs:</h3>
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
    </div>
</div>
