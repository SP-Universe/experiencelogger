<div class="section section--locationsoverview">
    <div class="section_content">
        <div class="search_bar" id="search-location-bar">
            <input type="text" name="search" id="search-location" placeholder="Search for a place" />
        </div>
        <div class="location_list">
            <% loop $Locations %>
                <a href="$Top.Link('location')/$FormattedName" class="location_entry">
                    <div class="location_entry_image" style="background-image: url($Image.FocusFill(200,200).Url)">
                    </div>
                    <div class="location_entry_content">
                        <h2 class="location_title">$Title</h2>
                        <h4>$Type.Title</h4>
                        <p>$Address</p>
                        <p>$Experiences.Count Experiences</p>
                    </div>
                </a>
            <% end_loop %>
        </div>
    </div>
</div>
