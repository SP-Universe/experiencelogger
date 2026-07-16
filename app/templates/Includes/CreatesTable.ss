<% if $Creates %>
    <div class="import_block import_block--creates">
        <h2>Will be created <span class="import_count">{$Creates.Count}</span></h2>
        <p class="import_intro">Check "Skip" for any field you don't want to import, or "Skip whole attraction" to leave one out entirely.</p>
        <table class="import_creates_table">
            <thead>
                <tr>
                    <th class="import_creates_col--field">Field</th>
                    <th class="import_creates_col--value">Value</th>
                    <th class="import_creates_col--action">Skip</th>
                </tr>
            </thead>
            <% loop $Creates %>
                <tbody class="import_creates_group">
                    <tr class="import_creates_heading">
                        <th>
                            $Title
                            <span class="import_creates_meta">$FieldCount fields</span>
                        </th>
                        <th class="import_creates_col--action" colspan="2">
                            <label class="import_toggle">
                                <input type="checkbox" name="skipCreate_{$Index}" value="1">
                                <span>Skip whole attraction</span>
                            </label>
                        </th>
                    </tr>
                    <% loop $Fields %>
                        <tr>
                            <td data-label="Field" class="import_creates_col--field">$FieldLabel</td>
                            <td data-label="Value" class="import_creates_col--value">$Value</td>
                            <td data-label="Skip" class="import_creates_col--action">
                                <label class="import_toggle">
                                    <input type="checkbox" name="skipCreateField_{$Index}_{$FieldIndex}" value="1">
                                    <span>Skip</span>
                                </label>
                            </td>
                        </tr>
                    <% end_loop %>
                </tbody>
            <% end_loop %>
        </table>
    </div>
<% end_if %>
