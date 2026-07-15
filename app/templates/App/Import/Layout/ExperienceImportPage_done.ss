<div class="section section--import">
    <div class="section_content">
        <h1>Import complete</h1>
        <p class="import_intro">Place: $Summary.locationTitle</p>

        <div class="import_donelist">
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.created</span>
                <span class="import_donelist_label">Created</span>
            </div>
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.skipped</span>
                <span class="import_donelist_label">Skipped (opted out)</span>
            </div>
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.autoFilled</span>
                <span class="import_donelist_label">Auto-filled</span>
            </div>
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.skippedAutoFills</span>
                <span class="import_donelist_label">Auto-fill skipped</span>
            </div>
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.applied</span>
                <span class="import_donelist_label">Conflicts: new value applied</span>
            </div>
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.kept</span>
                <span class="import_donelist_label">Conflicts: existing value kept</span>
            </div>
            <div class="import_donelist_item">
                <span class="import_donelist_value">$Summary.markedDefunct</span>
                <span class="import_donelist_label">Marked as defunct</span>
            </div>
        </div>

        <a href="$Link" class="button">Start another import</a>
    </div>
</div>
