@extends('flare::admin.sections.wrapper')

@section('page_title', 'Delete Gallery')

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">
            Delete {{ $gallery->name }}
        </h3>
    </div>
    <form action="" method="post">
        <div class="box-body">
            <div class="alert alert-danger no-margin">
                <i class="icon fa fa-exclamation-triangle"></i>
                @if ($gallery->trashed())
                    <strong>Are you sure you wish to permanently delete this gallery?</strong>
                    <p>
                        Once a gallery is permanently deleted it can no longer be recovered.
                    </p>
                @else
                    <strong>Are you sure you wish to trash this Gallery?</strong>
                    <p>
                        The gallery will be sent to the trash, where it can either be restored or deleted permanently.
                    </p>
                @endif 
            </div>
        </div>
        <div class="box-footer">
            {!! csrf_field() !!}
            <a href="{{ $moduleAdmin::currentUrl() }}" class="btn btn-default">
                Cancel
            </a>
            <button class="btn btn-danger" type="submit">
                <i class="fa fa-trash"></i>
                @if ($gallery->trashed())
                    Delete Gallery
                @else 
                    Trash Gallery
                @endif
            </button>
        </div>
    </form>
</div>

@stop
