<div class="section section--favouriteplaces">
    <div class="section_content">
        <% if $CurrentUser %>
            <div class="favouriteplaces_intro">
                <h2 class="favouriteplaces_intro_title">{$CurrentUser.FirstName}'s Favourite places</h2>
            </div>

            <% if $CurrentUser.FavouritePlaces.Count <= 0 %>
                <p class="favouriteplaces_intro_text">You don't have any favourite places yet. You can add your favourite places to your profile by clicking the star. This way you can easily find them again.</p>
            <% else %>
                <div class="location_list">
                    <% loop $CurrentUser.FavouritePlaces %>
                        <div class="location_entry_wrap">
                            <a href="$Link" class="location_entry">
                                <div class="location_entry_image" style="background-image: url($Image.FocusFill(200,200).Url)"></div>
                                <div class="location_entry_content">
                                    <h2 class="location_title">$Title</h2>
                                    <h4>$Type.Title</h4>
                                    <p>$Address</p>
                                    <p>$Experiences.Filter("State", "Active").Count Experiences
                                        <% if $Experiences.Filter("State", "Defunct").Count > 0 %> | $Experiences.Filter("State", "Defunct").Count Defunct<% end_if %>
                                        <% if $Experiences.Filter("State", "In Maintenance").Count > 0 %> | $Experiences.Filter("State", "In Maintenance").Count In Maintenance<% end_if %>
                                        <% if $Experiences.Filter("State", "Other").Count > 0 %> | $Experiences.Filter("State", "Other").Count Other<% end_if %></p>
                                </div>

                            </a>
                        </div>
                    <% end_loop %>
                </div>
            <% end_if %>

        <% end_if %>
    </div>
</div>
