<% with $Experience %>
    <div class="section section--experienceentry">
        <div class="section_content">
            <div class="experience_block <% if not $Image %>noimage<% end_if %>">
                <% if $PhotoGalleryImages %>
                    <div class="experiencegallery">
                        <div class="experiencegallery_slider" data-behaviour="slider">
                            <% loop PhotoGalleryImages %>
                                <div class="experienceimage">
                                    $Image.FocusFill(1000,400)
                                </div>
                            <% end_loop %>
                        </div>
                    </div>
                <% else_if $Image %>
                    <div class="experienceimage">
                        $Image.FocusFill(1000,400)
                    </div>
                <% end_if %>
                <% if $CurrentUser %>
                    <div class="experience_log_button">
                        <div class="logcount">
                            <p>$CurrentUser.LogCount($ID) Counts</p>
                            <% if $LatestLog %>
                                <p>Latest log: $LatestLog.FormattedDate</p>
                            <% else %>
                                <p>Log your first time!</p>
                            <% end_if %>
                        </div>
                        <a class="logging_link" href="$AddLogLink">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/></svg>
                        </a>
                    </div>
                <% end_if %>
                <h1>$Title</h1>
                <h4>$Type.Title <% if $Area %>in $Area.Title<% end_if %></h4>
                $Description
                <div class="experience_buttons">
                    <% if $ExperienceLink %><a href="$ExperienceLink" class="experience_button" target="_blank">Official Page</a><% end_if %>
                    <% if $HasGeneralSeats && $ExperienceTrains.Count > 0 %><a class="experience_button" href="$Up.Link('seatchart')/$LinkTitle">Seatchart</a><% end_if %>
                </div>
            </div>
            <div class="experiencedata_list">
                <% loop $ExperienceData %>
                    <div class="experiencedata_title <% if $Type.IsLongText %>long<% end_if %>">
                        <% if $AlternativeTitle %>
                            <h2>$AlternativeTitle</h2>
                        <% else %>
                            <h2>$Type.Title</h2>
                        <% end_if %>
                    </div>
                    <div class="experiencedata_content <% if $Type.IsLongText %>long<% end_if %>">
                        <p>$Description</p>
                        <% if $MoreInfo %>
                            <a href="$MoreInfo" class="moreinfo">More Info</a>
                        <% end_if %>
                        <% if $Source %>
                            <% if $SourceLink %>
                                <a href="$SourceLink" class="source">Source: $Source</a>
                            <% else %>
                                <p class="source">Source: $Source</p>
                            <% end_if %>
                        <% end_if %>
                    </div>
                <% end_loop %>
            </div>
        </div>
    </div>
<% end_with %>
