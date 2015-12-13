@extends('flare::admin.sections.wrapper')

@section('page_title', 'Edit Gallery')

@section('content')

<div class="row"> 
    <form action="" method="post">
        {!! csrf_field() !!}
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header with-border">
                    @include('flare::admin.galleries.includes.title-and-slug')
                </div>
                <div class="box-body">
                    @include('flare::admin.galleries.includes.manager')
                </div>
            </div>
        </div>

        <div class="col-md-3">
            @include('flare::admin.galleries.includes.publish')
            @include('flare::admin.galleries.includes.settings')
        </div>
    </form>
</div>

@stop
