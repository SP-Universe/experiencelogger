<% with $Experience %>
    <div class="section section--experienceentry">
        <div class="section_content">
        <div class="experience_block <% if not $Image %>noimage<% end_if %>">
                <a class="backbutton" onclick="window.history.back();">Zurück</a>
                <% if $PhotoGalleryImages %>
                    <div class="experiencegallery">
                        <div class="experiencegallery_slider" data-behaviour="slider">
                        <% loop PhotoGalleryImages %>
                            <div class="experienceimage">
                                $Image.FocusFill(1000,600)
                            </div>
                        <% end_loop %>
                    </div>
                    </div>
                <% else_if $Image %>
                    <div class="experienceimage">
                        $Image.FocusFill(1000,600)
                    </div>
                <% else %>
                    <br>
                <% end_if %>
                <h1>$Title</h1>
                <h4>$Type.Title <% if $Area %>in $Area.Title<% end_if %></h4>
                $Description
                <div class="experience_buttons">
                    <% if $ExperienceLink %><a href="$ExperienceLink" class="experience_button" target="_blank">Official Page</a><% end_if %>
                    <% if $HasGeneralSeats && $SortedTrains %><a class="experience_button" href="$Up.Link('seatchart')/$ID">Seatchart</a><% end_if %>
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
