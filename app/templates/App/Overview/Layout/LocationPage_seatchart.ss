<% with $Experience %>
    <div class="section section--experience_seatchart">
        <div class="section_content">
            <h1>Seats and Logs of $Title</h1>
            <h2>Seatchart</h2>
                <% if $ExperienceTrains.Count > 0 %>
                    <% if $ExperienceTrains.Count > 1 %>
                        <div class="train_selector">
                            <select name="traindropdown" id="traindropdown" onchange="change_train(this)">
                                <option value="-1">Select a <% if $CustomTrainType != "" %>$CustomTrainType<% else %><% if $Traintype != "None" %>$Traintype<% else %>Thing<% end_if %><% end_if %></option>
                                <% loop $ExperienceTrains() %>
                                    <option value="$SortOrder"><% if $Up.CustomTrainType != "" %>$Up.CustomTrainType: <% else %><% if $Up.Traintype != "None" %>$Up.Traintype: <% end_if %><% end_if %>$Title</option>
                                <% end_loop %>
                            </select>
                        </div>
                    <% else %>
                        <div class="train_selector hidden">
                            <select name="traindropdown" id="traindropdown" onchange="change_train(this)">
                                <% loop $ExperienceTrains() %>
                                    <option value="$SortOrder"><% if $Up.CustomTrainType != "" %>$Up.CustomTrainType <% else %><% if $Up.Traintype != "None" %>$Up.Traintype: <% end_if %><% end_if %>$Title</option>
                                <% end_loop %>
                            </select>
                        </div>
                    <% end_if %>

                    <% include TrainVisualizer PageController=$Top, PlaceOrientation=$SeatOrientation %>
                <% else_if $HasSeats %>
                    <p>There is currently no seatchart available for this experience.</p>
                <% else %>
                    <p>This experience has no Seats.</p>
                <% end_if %>

            <hr/>

            <% if $Variants %>
                <h2>Variants</h2>
                <% loop $Logs.GroupedBy('Variant') %>
                    <% if $Variant %>
                        <p><b>$Pos - $Variant:</b> $Children.Count Logs ($Top.getPercent($Up.Up.Logs.Count, $Children.Count))</p>
                    <% else %>
                        <p><b>$Pos</b><i> - No Variant set:</i> $Children.Count Logs ($Top.getPercent($Up.Up.Logs.Count, $Children.Count))</p>
                    <% end_if %>
                <% end_loop %>

                <div class="statsbar">
                    <% loop $Logs.GroupedBy('Variant') %>
                        <div class="statsbar_item" style="width: $Top.getPercent($Up.Up.Logs.Count, $Children.Count);">$Pos<span> - $Top.getPercent($Up.Up.Logs.Count, $Children.Count)</span></div>
                    <% end_loop %>
                </div>

                <hr/>
            <% end_if %>

            <% if $Versions %>
                <h2>Versions</h2>
                <% loop $Logs.GroupedBy('Version') %>
                    <% if $Version %>
                        <p><b>$Pos - $Version:</b> $Children.Count Logs ($Top.getPercent($Up.Up.Logs.Count, $Children.Count))</p>
                    <% else %>
                        <p><b>$Pos</b><i> - No Version set:</i> $Children.Count Logs ($Top.getPercent($Up.Up.Logs.Count, $Children.Count))</p>
                    <% end_if %>
                <% end_loop %>

                <div class="statsbar">
                    <% loop $Logs.GroupedBy('Version') %>
                        <div class="statsbar_item" style="width: $Top.getPercent($Up.Up.Logs.Count, $Children.Count);">$Pos<span> - $Top.getPercent($Up.Up.Logs.Count, $Children.Count)</span></div>
                    <% end_loop %>
                </div>

                <hr/>
            <% end_if %>

            <h2>Logs</h2>
            <% if $Logs.Count > 0 %>
                <h3>$Logs.Count Logs</h3>
                <% loop $Logs %>
                    <% include LogCard %>
                <% end_loop %>
            <% else %>
                <p>You have not yet logged this experience.</p>
            <% end_if %>
        </div>
    </div>
<% end_with %>

<script>
    const trainselector = document.querySelector('.train_visualizer');
    const entrance = trainselector.querySelectorAll('.entrance');
    const trains = trainselector.querySelectorAll('.train');

    function change_train(e){
        change_entrance(e);
        if(trainselector && trains){
            trains.forEach(train => {
                train.classList.remove('active');
                if(train.getAttribute('data-train') == e.value){
                    train.classList.add('active');
                }
            });
        }
    }

    function change_entrance(e){
        entrance.forEach(entrance => {
            if(e.value == -1){
                entrance.classList.remove('active');
            } else {
                entrance.classList.add('active');
            }
        });
    }
</script>
