<div class="section section--logspage">
    <h1>All of your Logs:</h1>
    <label for="select_experience_type">Filter by Type</label>
    <select id="select_experience_type" data-behaviour="filter_logs">
        <option value="All">All</option>
        <% loop $getLogs().GroupedBy(ExperienceType) %>
            <option value="$ExperienceType">$ExperienceType</option>
        <% end_loop %>
    </select>
    <% loop $Logs().GroupedBy(VisitDate) %>
        <div class="logs_date">

            <h4>$VisitDate <span>$Children.Count <% if $Children.Count > 1 %>Experiences<% else %>Experience<% end_if %></span></h4>
            <% loop $Children %>
                <% include LogCard %>
            <% end_loop %>
        </div>
    <% end_loop %>
</div>
