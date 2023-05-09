<div class="experience_card data--loading state-{$State}" data-behaviour="experiencecard">
    <div class="experiencedata">
        $JSONCode.Raw
    </div>
    <a href="" class="experience_entry">

        <div class="experience_entry_image" style="background-image: url($PhotoGalleryImages.First.Image.FocusFill(200,200).Url)"></div>
        <div class="experience_entry_content">
            <h2 class="experience_title"> Loading ... </h2>
            <h4 class="experience_type" data-filter="" data-status="">...</h4>
            <p class="experience_distance" data-behaviour="distance" data-loc="$Coordinates"></p>
        </div>
    </a>

    <% if not $ShowLogButton %>
        <% if $CurrentUser %>
            <% if $CurrentUser.LogCount($ID) > 0 %>
                <div class="experience_logging">
                    <a class="logging_link notnew" href="$AddLogLink">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/></svg>
                    </a>
                    <p class="logcount">$CurrentUser.LogCount($ID)</p>
                </div>
            <% else %>
                <div class="experience_logging">
                    <a class="logging_link" href="$AddLogLink">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/></svg>
                    </a>
                </div>
            <% end_if %>
            <div class="experience_menu">
                <a class="experience_menu_symbol" data-behaviour="popup-open" data-popupid="$ID">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>

                <div class="experience_menu_popup" data-behaviour="popup" data-popupid="$ID">
                    <div class="popup_inner">
                        <a class="popup_closingbutton" data-behaviour="popup-close" data-popupid="$ID"></a>
                        <h2>Settings for $Title <span>in $Parent.Title</span></h2>
                        <p>Coming soon</p>
                    </div>
                </div>
            </div>
        <% end_if %>
    <% end_if %>
</div>
