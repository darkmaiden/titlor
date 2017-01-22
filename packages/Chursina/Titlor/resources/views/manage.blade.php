@extends(config('titlor.views.layout'))

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="jumbotron">
                <h5><b>Доступные шаблоны URL</b></h5>
                <ul>
                    @foreach($availableUris as $uri)
                        <li>{{$uri}}</li>
                    @endforeach

                </ul>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(Session::has('errorMsg'))
                <div class="alert alert-danger">{{Session::get('errorMsg')}}</div>
            @endif
            @if(Session::has('successMessage'))
                <div class="alert alert-success">{{Session::get('successMessage')}}</div>
            @endif

            <form method="post" action="{{route('titlor.manage')}}">
                <div class="row">
                    <div class="col-xs-5">
                        <h5><b>Шаблон URL (/folder/page)</b></h5>
                    </div>
                    <div class="col-xs-7">
                        <h5><b>Title страницы</b></h5>
                    </div>
                </div>
                <div class="row">
                    @if(isset($data))
                        @foreach($data as $item)
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="uri{{$item['id']}}"
                                           value="{{$item['uri']}}" required/>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="title{{$item['id']}}"
                                           value="{{$item['title']}}" required/>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-xs-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="new_uri" value="" placeholder="Новая"/>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <input type="text" class="form-control" name="new_title" value=""/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ Session::token() }}"/>
                <button type="submit" class="btn btn-lg btn-success">Обновить</button>
            </form>
        </div>
    </div>
@endsection