<h2>Imported files</h2>
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
           Import Status
        </th>
        <th>
            Validation status
        </th>
        <th>
            Emails
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