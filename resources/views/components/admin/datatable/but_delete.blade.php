<form method="POST" action="{{ $link }}">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-danger confirm-delete" style="margin: 5px;">
        <i class="fa fa-trash"></i>@isset($butTitle) {{ $butTitle}} @endisset
    </button>
</form>
