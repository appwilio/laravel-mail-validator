@extends('layout')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>
                id
            </th>
            <th>
                email
            </th>
            <th>
                validation
            </th>
        </tr>
        </thead>
        <tbody id="js-emails-list">
        </tbody>
    </table>
    <a href="#" id="js-prev"  class="btn btn-success">Prev</a>
    <a href="#" id="js-next" class="btn btn-success">Next</a>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        var paginate = "{{url()->route("email.paginate")}}"
    </script>
    <script type="text/javascript" src="/js/custom/email.list.js"></script>
@endsection