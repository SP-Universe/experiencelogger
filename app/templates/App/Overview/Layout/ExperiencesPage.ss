<div class="section section--allexperiencesoverview">
    <div class="section_content">
        <div class="location_experiences">
            <div class="search_bar" id="search-experience-bar">
                <input type="text" name="search" id="search-experience" placeholder="Search for a experience" />
                <a class="advancedfilters_toggle"><svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M22 40q-.85 0-1.425-.575Q20 38.85 20 38V26L8.05 10.75q-.7-.85-.2-1.8Q8.35 8 9.4 8h29.2q1.05 0 1.55.95t-.2 1.8L28 26v12q0 .85-.575 1.425Q26.85 40 26 40Z"/></svg>
                </a>
            </div>
            <div class="advancedfilters">
                <div class="experiences_filter">
                    <p>Type: </p>
                    <% loop Types %>
                        <a class="filterbutton" data-filter="$Title">$Title</a>
                    <% end_loop %>
                </div>
            </div>

            <div class="experience_list">
                <% loop $Experiences %>
                    <% include ExperienceCard %>
                <% end_loop %>
            </div>
        </div>
    </div>
</div>
