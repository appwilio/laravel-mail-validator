<h2>Exports files</h2>
<table class="table">
    <thead>
    <tr>
        <th></th>
        <th>
            File name
        </th>
        <th>
            Finished
        </th>
        <th>
            Last Update
        </th>
    </tr>
    </thead>
    <tbody id="js-exports-list">
    </tbody>
</table>
<button id="js-export-button" class="btn-primary btn" data-ajax-url="{{route("export.make")}}">New export</button>
{{--<a id="js-export-warning" class="btn-danger btn" href="#">Validation not finished yet</a>--}}
@section('scripts')
    @parent
    <script type="text/javascript">
        var exportsListUrl = "{{url()->route("export.list")}}",
            isPendingUrl = "{{url()->route("validator.pending")}}";
    </script>
    <script type="text/javascript" src="/js/custom/exports.list.js"></script>
@endsection