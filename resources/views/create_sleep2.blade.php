@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="panel-body">
            <div class="row">
                {!! Form::open(array('action' => 'SleepController2@store', 'enctype' => 'multipart/form-data')) !!}
                <div class="col-md-9">
                    <div class="form-group">
                        <?php $date = date("Y-m-d"); ?>
                        {!! Form::label('start-date', 'Дата начала события') !!}
                        {!! Form::date('start-date', $value = $date, $attributes = ['class' => 'form-control top-style', 'name' => 'start-date']) !!}
                    </div>
                    <div class="form-group">
                        <?php $date = date("Y-m-d"); ?>
                        {!! Form::label('end-date', 'Дата конца события') !!}
                        {!! Form::date('end-date', $value = $date, $attributes = ['class' => 'form-control top-style', 'name' => 'end-date']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('start-time', 'Время начала события') !!}
                        {!! Form::time('start-time', $value = '23:00', $attributes = ['class' => 'form-control top-style', 'name' => 'start-time']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('end-time', 'Время конца события') !!}
                        {!! Form::time('end-time', $value = '23:00', $attributes = ['class' => 'form-control top-style', 'name' => 'end-time']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('event-category', 'Категория события') !!}
                        {!! Form::text('event-category', $value = '1', $attributes = ['class' => 'form-control top-style', 'name' => 'event-category']) !!}
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