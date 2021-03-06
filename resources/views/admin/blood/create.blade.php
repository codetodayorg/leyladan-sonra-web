@extends('admin.layouts.app')

@section('title', 'Kan Bağışçısı Ekle')

@section('header')
    <section class="content-header">
        <h1>
            Kan Bağışçısı Ekle
            <small>Bu sayfadan sisteme kan bağışçısı ekleyebilirsiniz</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-home"></i> Anasayfa</a>
            </li>
            <li>
                <a href="{{ route('admin.blood.index') }}">Bağışçılar</a>
            </li>
            <li class="active">Bağışçı Ekle</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">İşlemi tamamlamak için yanında
                        <strong class="text-danger">*</strong> bulunan alanları doldurmanız gerekiyor.</h4>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['method' => 'POST', 'route' => 'admin.blood.store', 'class' => 'form-horizontal']) !!}
                <div class="box-body">
                    <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                        {!! Form::label('mobile', 'Telefon Numarası *', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('mobile', null, ['class' => 'form-control mobile', 'required' => 'required']) !!}
                            <small class="text-danger">{{ $errors->first('mobile') }}</small>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        {!! Form::label('city', 'Şehir *', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('city', citiesToSelect(), null, ['class' => 'form-control select2', 'required' => 'required', 'placeholder'
                            => 'Şehir seçiniz']) !!}
                            <small class="text-danger">{{ $errors->first('city') }}</small>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('blood_type') ? ' has-error' : '' }}">
                        {!! Form::label('blood_type', 'Kan Grubu *', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                <label class="btn btn-ls @if(old('blood_type') == 'A') active @endif">
                                    {!! Form::radio('blood_type', 'A', null) !!} A
                                </label>
                                <label class="btn btn-ls @if(old('blood_type') == 'B') active @endif">
                                    {!! Form::radio('blood_type', 'B', null) !!} B
                                </label>
                                <label class="btn btn-ls @if(old('blood_type') == 'AB') active @endif">
                                    {!! Form::radio('blood_type', 'AB', null) !!} AB
                                </label>
                                <label class="btn btn-ls @if(old('blood_type') == '0') active @endif">
                                    {!! Form::radio('blood_type', '0', null) !!} 0
                                </label>
                            </div>
                            <small class="text-danger">{{ $errors->first('blood_type') }}</small>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('rh') ? ' has-error' : '' }}">
                        {!! Form::label('rh', 'RH *', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                <label class="btn btn-ls @if(old('rh') == '1') active @endif">
                                    {!! Form::radio('rh', '1', null) !!} Pozitif
                                </label>
                                <label class="btn btn-ls @if(old('rh') == '0') active @endif">
                                    {!! Form::radio('rh', '0', null) !!} Negatif
                                </label>
                            </div>
                            <small class="text-danger">{{ $errors->first('rh') }}</small>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <a href="{{ route('admin.blood.index') }}" class="btn btn-danger">Geri</a>
                    {!! Form::submit("Ekle", ['class' => 'btn btn-success pull-right']) !!}
                </div>
                <!-- /.box-footer -->
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection