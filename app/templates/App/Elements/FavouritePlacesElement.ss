<div class="section section--favouriteplaces">
    <div class="section_content">
        <% if $CurrentUser %>
            <div class="favouriteplaces_intro">
                <% if $CurrentUser %>
                    <h2 class="favouriteplaces_intro_title">{$CurrentUser.FirstName}'s favourite places</h2>
                <% else %>
                    <h2 class="favouriteplaces_intro_title">Your favourite places</h2>
                <% end_if %>
            </div>

            <% if $CurrentUser.FavouritePlaces.Count <= 0 %>
                <p class="favouriteplaces_intro_text">You don't have any favourite places yet. You can add your favourite places to your profile by clicking the star. This way you can easily find them again.</p>
            <% else %>
                <div class="location_list">
                    <% loop $CurrentUser.FavouritePlaces %>
                        <div class="location_entry_wrap">
                            <a href="$Link" class="location_entry">
                                <div class="location_entry_image">
                                    <% if $Image %>
                                        <img src="$Image.FocusFill(600,200).Url" alt="$Image.Title" />
                                    <% else_if $Logo %>
                                        <img src="$Logo.FocusFill(600,200).Url" alt="$Logo.Title" />
                                    <% end_if %>
                                </div>
                                <div class="location_entry_content">
                                    <h2 class="location_title">$Title</h2>
                                    <h3>$Type.Title</h3>
                                    <p>$Address</p>
                                    <p>$Experiences.Filter("State", "Active").Count Experiences
                                    <% if $Experiences.Filter("State", "Defunct").Count > 0 %> | $Experiences.Filter("State", "Defunct").Count Defunct<% end_if %>
                                    <% if $Experiences.Filter("State", "In Maintenance").Count > 0 %> | $Experiences.Filter("State", "In Maintenance").Count In Maintenance<% end_if %>
                                    <% if $Experiences.Filter("State", "Other").Count > 0 %> | $Experiences.Filter("State", "Other").Count Other<% end_if %></p>
                                    <% if $Top.CurrentUser %>
                                        <div class="progress_handler loading" data-behaviour="location_progress" data-locationid="$ID">
                                            <p class="location_progress_text">Loading...</p>
                                            <div class="location_progress">
                                                <div class="location_progress_bar" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    <% else %>
                                        <div class="progress_handler" data-behaviour="location_progress">
                                            <p class="location_progress_text">$Experiences.Filter("State", "Active").Count Experiences</p>
                                        </div>
                                    <% end_if %>
                                </div>
                            </a>
                        </div>
                    <% end_loop %>
                </div>
            <% end_if %>

        <% end_if %>
    </div>
</div>
