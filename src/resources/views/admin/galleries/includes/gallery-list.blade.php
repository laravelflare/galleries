<div class="box-body no-padding">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Name
                </th>
                <th>
                    Images
                </th>
                <th>
                    Created On
                </th>
                <th>
                    Updated At
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @if ($galleries->count() > 0)    
            @foreach($galleries as $gallery)    
                <tr>
                    <td>
                        {{ $gallery->id }}
                    </td>
                    <td>
                        {{ $gallery->name }}
                    </td>
                    <td>
                        {{ $gallery->images()->count() }}
                    </td>
                    <td>
                        {{ $gallery->created_at->diffForHumans() }}
                    </td>
                    <td>
                        {{ $gallery->updated_at->diffForHumans() }}
                    </td>
                    <td style="width: 1%; white-space:nowrap">
                        @if (!$gallery->trashed())
                        <a class="btn btn-success btn-xs" href="{{ $gallery->link }}">
                            <i class="fa fa-eye"></i>
                            View
                        </a>
                        @endif
                        <a class="btn btn-primary btn-xs" href="{{ $moduleAdmin::currentUrl('edit/'.$gallery->id) }}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>
                        @if ($gallery->trashed())
                        <a class="btn btn-info btn-xs" href="{{ $moduleAdmin::currentUrl('restore/'.$gallery->id) }}">
                            <i class="fa fa-undo"></i>
                            Restore
                        </a>
                        @else
                        <a class="btn btn-warning btn-xs" href="{{ $moduleAdmin::currentUrl('clone/'.$gallery->id) }}">
                            <i class="fa fa-clone"></i>
                            Clone
                        </a>
                        @endif
                        <a class="btn btn-danger btn-xs" href="{{ $moduleAdmin::currentUrl('delete/'.$gallery->id) }}">
                            <i class="fa fa-trash"></i>
                            @if ($gallery->trashed())
                                Delete
                            @else 
                                Trash
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
        @else 
            <tr>
                <td colspan="6">
                    No Galleries Found
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>