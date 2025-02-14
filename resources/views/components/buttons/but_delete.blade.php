<form method="POST" action="{{ $link }}" >
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-danger btn-sm pull-left confirm-delete">
        <i class="fa fa-trash"></i>@isset($butTitle) {{ $butTitle}} @endisset
    </button>
</form>
