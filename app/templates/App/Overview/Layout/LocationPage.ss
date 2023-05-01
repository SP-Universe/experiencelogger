<div class="section section--locationsoverview">
    <div class="section_content">
        <div class="search_bar" id="search-location-bar">
            <input type="text" name="search" id="search-location" placeholder="Search for a place" />
        </div>
        <div class="location_list">
            <% loop $Locations %>
                <div class="location_entry_wrap">

                    <a href="$Link" class="location_entry">
                        <div class="location_entry_image" style="background-image: url($Image.FocusFill(200,200).Url)"></div>
                        <div class="location_entry_content">
                            <h2 class="location_title">$Title</h2>
                            <h3>$Type.Title</h3>
                            <p><% if $Top.CurrentUser %>$LocationProgress / <% end_if %>$Experiences.Filter("State", "Active").Count Experiences</p>
                            <div class="location_progress">
                                <div class="location_progress_bar" style="width: {$LocationProgressInPercent}%"></div>
                            </div>
                        </div>
                    </a>

                    <% if $Top.CurrentUser %>
                        <a href="$Top.Link('changeFavourite')/$LinkTitle" class="location_favouritemarker">
                            <% if $IsFavourite %>
                                <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="m24 41.95-2.05-1.85q-5.3-4.85-8.75-8.375-3.45-3.525-5.5-6.3T4.825 20.4Q4 18.15 4 15.85q0-4.5 3.025-7.525Q10.05 5.3 14.5 5.3q2.85 0 5.275 1.35Q22.2 8 24 10.55q2.1-2.7 4.45-3.975T33.5 5.3q4.45 0 7.475 3.025Q44 11.35 44 15.85q0 2.3-.825 4.55T40.3 25.425q-2.05 2.775-5.5 6.3T26.05 40.1Z"/></svg>
                            <% else %>
                                <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="m24 41.95-2.05-1.85q-5.3-4.85-8.75-8.375-3.45-3.525-5.5-6.3T4.825 20.4Q4 18.15 4 15.85q0-4.5 3.025-7.525Q10.05 5.3 14.5 5.3q2.85 0 5.275 1.35Q22.2 8 24 10.55q2.1-2.7 4.45-3.975T33.5 5.3q4.45 0 7.475 3.025Q44 11.35 44 15.85q0 2.3-.825 4.55T40.3 25.425q-2.05 2.775-5.5 6.3T26.05 40.1ZM24 38q5.05-4.65 8.325-7.975 3.275-3.325 5.2-5.825 1.925-2.5 2.7-4.45.775-1.95.775-3.9 0-3.3-2.1-5.425T33.5 8.3q-2.55 0-4.75 1.575T25.2 14.3h-2.45q-1.3-2.8-3.5-4.4-2.2-1.6-4.75-1.6-3.3 0-5.4 2.125Q7 12.55 7 15.85q0 1.95.775 3.925.775 1.975 2.7 4.5Q12.4 26.8 15.7 30.1 19 33.4 24 38Zm0-14.85Z"/></svg>
                            <% end_if %>
                        </a>
                    <% end_if %>
                </div>
            <% end_loop %>
        </div>
    </div>
</div>
