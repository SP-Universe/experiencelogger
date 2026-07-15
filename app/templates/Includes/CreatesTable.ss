<% if $Creates %>
    <div class="import_block import_block--creates">
        <h2>Will be created <span class="import_count">{$Creates.Count}</span></h2>
        <p class="import_intro">Check "Skip" for any attraction you don't want to import.</p>
        <table class="import_creates_table">
            <thead>
                <tr>
                    <th class="import_creates_col--title">Attraction</th>
                    <th class="import_creates_col--meta">Details</th>
                    <th class="import_creates_col--action">Skip import</th>
                </tr>
            </thead>
            <tbody>
                <% loop $Creates %>
                    <tr>
                        <td data-label="Attraction" class="import_creates_col--title">$Title</td>
                        <td data-label="Details" class="import_creates_col--meta">$FieldCount fields<% if $TrainCount %>, $TrainCount trains<% end_if %></td>
                        <td data-label="Skip import" class="import_creates_col--action">
                            <label class="import_toggle">
                                <input type="checkbox" name="skipCreate_{$Index}" value="1">
                                <span>Skip</span>
                            </label>
                        </td>
                    </tr>
                <% end_loop %>
            </tbody>
        </table>
    </div>
<% end_if %>
