<table class="table">
    <thead>
    <tr>
        <th>
            id
        </th>
        <th>
            Validator
        </th>
        <th>
            Valid
        </th>
        <th>
            Not valid
        </th>
        <th>
            Pending
        </th>
    </tr>
    </thead>
    <tbody id="js-validators-list">
    </tbody>
</table>
@section('scripts')
    @parent
    <script type="text/javascript">
        var validatorsListUrl = "{{url()->route("validator.list")}}";
    </script>
    <script type="text/javascript" src="/js/custom/validators.list.js"></script>
@endsection