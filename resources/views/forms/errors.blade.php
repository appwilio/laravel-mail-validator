@if(count($errors)>0)
    <div class="alert alert-danger" role="alert">Ошибка</div>
    @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{$error}}
        </div>
    @endforeach
@endif