<div class="box-footer clearfix">
    <div class="pull-left">
        <a href="{{ $moduleAdmin::currentUrl('create') }}" class="btn btn-success">
            <i class="fa fa-photo"></i>
            Add Gallery
        </a>
    </div>

    @if ($galleries->hasPages())
        <div class="pull-right" style="margin-top: -20px; margin-bottom: -20px;">
            {!! $galleries->render() !!}
        </div>
    @endif
</div>