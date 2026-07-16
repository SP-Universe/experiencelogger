<% if $Creates %>
    <div class="import_block import_block--creates">
        <h2>Will be created <span class="import_count">{$Creates.Count}</span></h2>
        <p class="import_intro">Click an attraction's name to see and skip individual fields. Check "Skip this whole attraction" to leave one out entirely.</p>
        <% loop $Creates %>
            <div class="import_create">
                <div class="import_create_top">
                    <details class="import_create_details">
                        <summary class="import_create_summary">
                            <span class="import_create_title">$Title</span>
                            <span class="import_create_meta">$FieldCount fields<% if $TrainCount %>, $TrainCount trains<% end_if %></span>
                        </summary>
                        <% if $Fields %>
                            <table class="import_create_fields_table">
                                <thead>
                                    <tr>
                                        <th class="import_create_fields_col--field">Field</th>
                                        <th class="import_create_fields_col--value">Value</th>
                                        <th class="import_create_fields_col--action">Skip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <% loop $Fields %>
                                        <tr>
                                            <td data-label="Field" class="import_create_fields_col--field">$FieldLabel</td>
                                            <td data-label="Value" class="import_create_fields_col--value">$Value</td>
                                            <td data-label="Skip" class="import_create_fields_col--action">
                                                <label class="import_toggle">
                                                    <input type="checkbox" name="skipCreateField_{$Index}_{$FieldIndex}" value="1">
                                                    <span>Skip</span>
                                                </label>
                                            </td>
                                        </tr>
                                    <% end_loop %>
                                </tbody>
                            </table>
                        <% end_if %>
                    </details>
                    <label class="import_toggle import_toggle--attraction">
                        <input type="checkbox" name="skipCreate_{$Index}" value="1">
                        <span>Skip whole attraction</span>
                    </label>
                </div>
            </div>
        <% end_loop %>
    </div>
<% end_if %>
