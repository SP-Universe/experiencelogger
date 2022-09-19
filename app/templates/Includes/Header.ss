<header>
    <div class="header_nav">
        <a href="" class="nav_brand">
            <img src="_resources/app/client/icons/ExperienceLogger-Symbol.svg">
        </a>
        <ul class="nav_menu">
            <% loop $Menu(1) %>
                <% if $MenuPosition != "footer" %>
                    <a href="$Link">
                        <li class="nav_link<% if $LinkOrSection == "section" %> nav_link--active<% end_if %>">
                            $MenuTitle
                        </li>
                    </a>
                <% end_if %>
            <% end_loop %>
        </ul>
        <div class="nav_button" data-behaviour="toggle-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
            <div class="bar4"></div>
        </div>
    </div>
</header>
