<div class="section section--MainNav">
    <ul class="nav_menu">
        <% loop $Menu(1) %>
            <% if $MenuPosition == "main" %>

                <li class="nav_link_wrap">
                    <a href="$Link" class="nav_link<% if $LinkOrSection == "section" %> nav_link--active<% end_if %>">
                        <% if $MenuIcon %>
                            <img alt="Menuicon for $MenuTitle" class="nav_link_menuicon" src="$MenuIcon.Url"/>
                        <% end_if %>
                        <p>$MenuTitle</p>
                    </a>
                </li>
            <% end_if %>
        <% end_loop %>

        <li class="nav_link_wrap nav_link--moremenu" data-behaviour="moremenu--toggle">
            <div class="nav_link">
                <img src="_resources/app/client/icons/Navigation/NavMore.svg" alt="Menuicon for More" class="nav_link_menuicon" />
                <p>More</p>
            </div>
        </li>
    </ul>
</div>

<div class="section section--MoreMenu">
    <ul class="more_menu">
        <% loop $Menu(1) %>
            <% if $MenuPosition == "more" %>
                <li class="more_menu_link_wrap">
                    <a href="$Link" class="more_menu_link<% if $LinkOrSection == 'section' %> more_menu_link--active<% end_if %>">
                        $MenuTitle
                    </a>
                </li>
            <% end_if %>
        <% end_loop %>
    </ul>
</div>
