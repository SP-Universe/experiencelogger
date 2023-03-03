<div class="section section--locationsoverview">
    <div class="section_content">
        <div class="search_bar" id="search-location-bar">
            <input type="text" name="search" id="search-location" placeholder="Search for a place" />
        </div>
        <div class="location_list">
            <% loop $Locations %>
                <% include LocationCard TopScope=$Top  %>
            <% end_loop %>
        </div>
    </div>
</div>
