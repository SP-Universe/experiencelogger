<div class="section section--dashboard">
    <div class="section_content">
        <h1 class="dashboard_title">$Title</h1>
        $Content
    </div>
</div>

<% if $CurrentUser %>
    <div class="section section--favouriteplaces">
        <div class="section_content">
            <div class="favouriteplaces_intro">
                <h2 class="favouriteplaces_intro_title">Your favourite places</h2>
            </div>

            <% if $CurrentUser.FavouritePlaces.Count <= 0 %>
                <p class="favouriteplaces_intro_text">You don't have any favourite places yet. You can add your favourite places to your profile by clicking the star. This way you can easily find them again.</p>
            <% else %>
                <div class="location_list">
                    <% loop $CurrentUser.FavouritePlaces %>
                        <div class="location_entry_wrap">
                            <a href="$Link" class="location_entry">
                                <div class="location_entry_background">
                                    <% if $Image %>
                                        <img class="location_entry_background_image" src="$Image.FocusFill(600,200).Url" alt="$Image.Title" />
                                    <% end_if %>
                                    <% if $Type.Icon %>
                                        <img class="location_entry_background_icon" src="$Type.Icon.FocusFill(200,200).Url" alt="$Type.Title" />
                                    <% end_if %>
                                </div>
                                <div class="location_entry_content">
                                    <div class="location_entry_content_text">
                                        <h2 class="location_title">$Title</h2>
                                        <h3 class="location_type">$Type.Title</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <% end_loop %>
                </div>
            <% end_if %>
        </div>
    </div>

    <div class="section section--lastloggedexperiences">
        <div class="section_content">
            <h2>Your last logged Experiences</h2>
            <% if $CurrentUser %>
                <% if $LastLogged %>
                    <% loop $LastLogged %>
                        <% include LogCard %>
                    <% end_loop %>
                <% else %>
                    <p>You don't have any logged experiences yet.</p>
                <% end_if %>
            <% else %>
                <p>You need to be logged in to see your last logged experiences. <a href="$ProfilePage.Link">Login ></a></p>
            <% end_if %>
        </div>
    </div>
<% end_if %>

<% if $News.Count > 0 %>
    <div class="section section--news">
        <div class="section_content">
            <h2 class="news_title">Experiencelogger News</h2>
            <div class="news_list">
                <% loop $News %>
                    <a href="$Link" class="news_entry">
                        <% if $Image %>
                            <img class="news_entry_image" src="$Image.FocusFill(600,400).Url" alt="$Image.Title" />
                        <% else %>
                            <div class="news_entry_image"></div>
                        <% end_if %>
                        <div class="news_entry_text">
                            <h3 class="news_entry_text_title">$Title</h3>
                            <h4 class="news_entry_text_date">$FormattedDate | $FormattedCategories</h4>
                            <p>$ShortDescription</p>
                        </div>
                    </a>
                <% end_loop %>
            </div>
            <a href="$AllNewsLink" class="button">All news</a>
        </div>
    </div>
<% end_if %>

