<h2>Exclude from export</h2>
<div class="row">
    <div class="col-sm-6">
        <h3>Business addresses</h3>
        <form id="js-exclude-prefix-form" class="form-inline">
            {{ csrf_field() }}
            <input type="hidden" name="type" value="{{\App\Domain\Model\Exclude::PREFIX_EXCLUDE}}">
            <div class="form-group">
                <label class="sr-only">
                    New value
                </label>
                <div class="input-group">
                    <input type="text" class="form-control" name="value">
                    <div class="input-group-addon">@some.domain.com</div>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Send">
        </form>
        <table class="table">
            <tbody id="js-exclude-prefix-list">
            </tbody>
        </table>

    </div>
    <div class="col-sm-6">
        <h3>Domain</h3>
        <form id="js-exclude-suffix-form" class="form-inline">
            {{ csrf_field() }}
            <input type="hidden" name="type" value="{{\App\Domain\Model\Exclude::SUFFIX_EXCLUDE}}">
            <div class="form-group">
                <label class="sr-only">
                    New value
                </label>
                <div class="input-group">
                    <div class="input-group-addon">some_name@</div>
                    <input type="text" name="value" class="form-control">
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Send">
        </form>
        <table class="table">
            <tbody id="js-exclude-suffix-list">
            </tbody>
        </table>
    </div>
</div>
@section('scripts')
    @parent
    <script type="text/javascript">
        var excludesListUrl = "{{url()->route("excludes.list")}}",
                excludesCreateUrl = "{{url()->route("excludes.create")}}";
    </script>
    <script type="text/javascript" src="/js/custom/excludes.list.js"></script>
@endsection