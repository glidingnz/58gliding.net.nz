<form action="waypoints/upload" method="post" enctype="multipart/form-data">
    {!! Modal::start($modal) !!}
    <div class="form-group row">
        <label for="waypoints" class="col-sm-4 col-form-label">Select File To Upload</label>
        <div class="col-sm-6 col-form-label">
            <input type="file" name="waypoints">
        </div>
    </div>
    {{ csrf_field() }}
    @can('waypoint-admin')
    {!! Modal::end() !!}
    @endcan
</form>