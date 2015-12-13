<div class="box box-default">
    <div class="box-header with-border">
        <i class="fa fa-file-code-o"></i>
        <h3 class="box-title">Gallery Settings</h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Template:</label>
            <input type="text" id="template"
                class="form-control"
                name="template"
                placeholder="Page Template (dot notation)"
                value="{{ old('template', $gallery->template) }}">
        </div>
    </div>
    <div class="box-footer">
        <div class="form-group">
            <label>Homepage:</label>
            <input type="checkbox" id="homepage"
                name="homepage"
                value="1"
                @if($gallery->slug && $gallery->slug->path == '') checked="checked" @endif
            ">
            <p>
                Sets the gallery as the homepage <br> and removes its slug.
            </p>
        </div>
        
    </div>
</div>