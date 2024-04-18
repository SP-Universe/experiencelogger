<div class="section section--locationsoverview">
    <div class="section_content">
        <div class="search_bar" id="search-location-bar">
            <input type="password" style="display:none">
            <input autocomplete=off type="text" name="search" id="search-location" placeholder="Search for a place" />
        </div>
        <div class="location_list">
            <% include LocationCard %>
            <% loop $Locations %>
            <% end_loop %>
        </div>
    </div>
</div>
