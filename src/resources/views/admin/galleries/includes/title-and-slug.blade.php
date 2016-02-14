<div class="form-group">
    <input type="text" id="name"
                class="form-control input-lg"
                name="name"
                placeholder="Enter Page Title"
                value="{{ old('name', $gallery->name) }}">
</div>
<div class="form-group no-margin">
    <div class="input-group">
        <span class="input-group-addon">
            <strong>
                Link:
            </strong>
            {{ url('') }}/
        </span>
        <input type="text" class="form-control" name="slug" value="{{ old('slug', $gallery->slug) }}" id="slug-modify-field" readonly>
        <span class="input-group-btn">
            <a href="#" class="btn btn-default btn-flat" id="slug-modify-button">
                Edit
            </a>
        </span>
        @if ($gallery->link && !$gallery->trashed())
        <span class="input-group-btn">
            <a href="{{ url($gallery->link) }}" class="btn btn-default btn-flat">
                View Page
            </a>
        </span>
        @endif
    </div>
</div>

@section('enqueued-js')
    <script>
    $(document).ready(function(){
        $('#slug-modify-button').click(function(e){
            e.preventDefault();

            $field = $('#slug-modify-field');

            if ($field.prop('readonly')) {
                $field.prop('readonly', false);
                $(this).text('Set');
                return;
            }

            $field.prop('readonly', true);
            $(this).text('Edit');
        });
    });
    </script>
@append