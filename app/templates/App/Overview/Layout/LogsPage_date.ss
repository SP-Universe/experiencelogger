<div class="section section--logspage">
    <h1>Your Logs:</h1>
    <label for="select_experience_type">Filter by Type</label>
    <select id="select_experience_type" data-behaviour="filter_logs">
        <option value="All">All</option>
        <% loop $getLogsForDay($Day, $Month, $Year).GroupedBy(ExperienceType) %>
            <option value="$ExperienceType">$ExperienceType</option>
        <% end_loop %>
    </select>
    <% loop $getLogsForDay($Day, $Month, $Year).GroupedBy(VisitDate) %>
        <div class="logs_date">
            <h4>$VisitDate <span>$Children.Count Experiences</span></h4>
            <% loop $Children %>
                <% include LogCard %>
            <% end_loop %>
        </div>
    <% end_loop %>
</div>
