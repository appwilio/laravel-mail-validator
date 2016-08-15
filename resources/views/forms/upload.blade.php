<div class="row">
    @include("forms.errors")
</div>
<div class="row">
    <form method="post" enctype="multipart/form-data" role="form">
        {{ csrf_field() }}
        <div class="controls">
            <div class="row">
                <div class="form-group">
                    <label class="control-label">Upload csv file</label>
                    <input type="file" name="file">
                </div>
            </div>
            <div class="row">
                <input type="submit" class="btn btn-success" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </div>
    </form>
</div>