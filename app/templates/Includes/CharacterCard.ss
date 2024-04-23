<div class="character_card data--loading state-{$State}" data-behaviour="charactercard">
    <div class="characterdata">
        $JSONCode.Raw
    </div>
    <a href="" class="character_entry">

        <div class="character_entry_image">
            <% if $PhotoGalleryImages.First %>
                <img src="$PhotoGalleryImages.First.Image.FocusFill(200,200).Url" alt="$Title" />
            <% end_if %>
        </div>
        <div class="character_entry_content">
            <h2 class="character_title"> Loading ... </h2>
        </div>
    </a>

    <% if not $ShowLogButton %>
        <% if $LoggedIn %>
            <% if $LogEntries.Count > 0 %>
                <div class="experience_logging">
                    <a class="logging_link notnew" href="$AddLogLink">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/></svg>
                    </a>
                    <p class="logcount">$LogEntries.Count</p>
                </div>
            <% else %>
                <div class="experience_logging">
                    <a class="logging_link" href="$AddLogLink">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/></svg>
                    </a>
                </div>
            <% end_if %>
        <% end_if %>
    <% end_if %>
</div>
