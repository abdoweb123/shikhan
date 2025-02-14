
@if(!empty($errors) || session('error') || session('success') || session('message'))
    <section class="notify">
        @if(count($errors) > 0)
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" style="text-align: center;">
                                {{ $error }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" style="text-align: center;">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success" style="text-align: center;">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(session('message'))
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info" style="text-align: center;">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (session('confirmation'))
            <div class="alert alert-info" role="alert" style="text-align: center;">
                {!! session('confirmation') !!}
            </div>
        @endif
    </section>
@endif
