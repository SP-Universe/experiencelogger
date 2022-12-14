<% with $Experience %>
    <div class="section section--seatchart">
        <div class="section_content">
            <h1>Seatchart of $Title</h1>

            <% if $ExperienceTrains.Count > 1 %>
                <div class="train_selector">
                    <select name="traindropdown" id="traindropdown" onchange="change_train(this)">
                        <option value="-1">Select a <% if $CustomTrainType %>$CustomTrainType<% else %><% if $Traintype != "None" %>$Up.Traintype<% else %>Thing<% end_if %><% end_if %></option>
                        <% loop $ExperienceTrains() %>
                            <option value="$SortOrder"><% if $Up.CustomTrainType %>$Up.CustomTrainType <% else %><% if $Up.Traintype != "None" %>$Up.Traintype: <% end_if %><% end_if %>$Title</option>
                        <% end_loop %>
                    </select>
                </div>
            <% else %>
                <div class="train_selector hidden">
                    <select name="traindropdown" id="traindropdown" onchange="change_train(this)">
                        <% loop $ExperienceTrains() %>
                            <option value="$SortOrder"><% if $Up.CustomTrainType %>$Up.CustomTrainType <% else %><% if $Up.Traintype != "None" %>$Up.Traintype: <% end_if %><% end_if %>$Title</option>
                        <% end_loop %>
                    </select>
                </div>
            <% end_if %>

            <% include TrainVisualizer PageController=$Top, PlaceOrientation=$SeatOrientation %>

            <% loop $Up.Logs.GroupedBy(ExperienceID).Filter("ExperienceID", $ID) %>
                <h4>$Children.Count Logs</h4>
                <% loop $Children %>
                    <% include LogCard %>
                <% end_loop %>
            <% end_loop %>
        </div>
    </div>
<% end_with %>

<script>
    const trainselector = document.querySelector('.train_visualizer');
    const trains = trainselector.querySelectorAll('.train');

    function change_train(e){
        if(trainselector && trains){
            trains.forEach(train => {
                train.classList.remove('active');
                if(train.getAttribute('data-train') == e.value){
                    train.classList.add('active');
                }
            });
        }
    }
</script>
