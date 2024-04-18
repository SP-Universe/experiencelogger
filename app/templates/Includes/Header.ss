<header>
    <div class="header_nav">
        <a href="$Top.HomepageLink" class="nav_brand">
            <img width="80px" height="80px" src="_resources/app/client/icons/ExperienceLogger-Symbol.svg" alt="Logo of Experience Logger">
        </a>
    </div>
</header>

<div class="section section--navigation">
    <ul class="nav_menu">
        <% loop $Menu(1) %>
            <% if $MenuPosition == "main" %>

                <li class="nav_link<% if $LinkOrSection == "section" %> nav_link--active<% end_if %>">
                    <a href="$Link">
                        <% if $MenuIcon %>
                            <img alt="Menuicon for $MenuTitle" class="menuicon" src="$MenuIcon.Url"/>
                        <% end_if %>
                        <p>$MenuTitle</p>
                    </a>
                </li>

            <% end_if %>
        <% end_loop %>
    </ul>
</div>
