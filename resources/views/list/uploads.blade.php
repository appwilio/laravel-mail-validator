<table class="table">
    <thead>
    <tr>
        <th></th>
        <th>
            File name
        </th>
        <th>
            Uploaded at
        </th>
        <th>
            Finished
        </th>
        <th>
            Last Update
        </th>
    </tr>
    </thead>
    <tbody id="js-uploads-list">
    </tbody>
</table>
@section('scripts')
    @parent
    <script type="text/javascript">
        var uploadsListUrl = "{{url()->route("upload.list")}}";
    </script>
    <script type="text/javascript" src="/js/custom/uploads.list.js"></script>
@endsection