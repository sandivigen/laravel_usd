@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="panel-body">
            <div class="row">
                {!! Form::open(array('action' => 'SleepController@store', 'enctype' => 'multipart/form-data')) !!}
                <div class="col-md-9">
                    <div class="form-group">
                        <?php $date = date("Y-m-dphp artisan make:migration create_article_table --create=articles"); ?>
                        {!! Form::label('bad-time', 'Дата') !!}
                        {!! Form::date('bad-time', $value = $date, $attributes = ['class' => 'form-control top-style', 'name' => 'bad-time']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('in-bed', 'Лег') !!}
                        {!! Form::time('in-bed', $value = '23:00', $attributes = ['class' => 'form-control top-style', 'name' => 'in-bed']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('asleep', 'Уснул') !!}
                        {!! Form::time('asleep', $value = '23:00', $attributes = ['class' => 'form-control top-style', 'name' => 'asleep']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('awoke', 'Проснулся') !!}
                        {!! Form::time('awoke', $value = '06:00', $attributes = ['class' => 'form-control top-style', 'name' => 'awoke']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('get-up', 'Поднялся') !!}
                        {!! Form::time('get-up', $value = '06:00', $attributes = ['class' => 'form-control top-style', 'name' => 'get-up']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Опубликовать', $attributes = ['class' => 'btn btn-custom']) !!}
                        {!! Form::close() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


