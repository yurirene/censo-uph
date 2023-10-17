<div class="row">
    <div class="col-md-3 d-flex flex-column justify-content-end ">
        <div class="form-group{{ $errors->has('estrutura[uph_organizada]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[uph_organizada]', 'Quantidade de UPHs organizadas na Federação:') !!}
        {!! Form::number('estrutura[uph_organizada]', isset($formulario) ? null : $estrutura_federacao['quantidade_uphs'], ['class' => 'form-control', 'required' => 'required', 'readonly' => true]) !!}
        <small class="text-danger">{{ $errors->first('estrutura[uph_organizada]') }}</small>
        </div>
    </div>
    <div class="col-md-3 d-flex flex-column justify-content-end ">
        <div class="form-group{{ $errors->has('estrutura[uph_nao_organizada]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[uph_nao_organizada]', 'Quantidade de igrejas do Presbitério sem UPHs organizadas:') !!}
        {!! Form::number('estrutura[uph_nao_organizada]', isset($formulario) ? null : $estrutura_federacao['quantidade_sem_uph'], ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[uph_nao_organizada]') }}</small>
        </div>
    </div>
    <div class="col-md-3 d-flex flex-column justify-content-end ">
        <div class="form-group{{ $errors->has('estrutura[nro_repasse]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[nro_repasse]', 'Quantidade de UPHs que fizeram o repasse da ACI para a Federação:') !!}
        {!! Form::number('estrutura[nro_repasse]', isset($formulario) ? null : $estrutura_federacao['nro_repasse'], ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[nro_repasse]') }}</small>
        </div>
    </div>
    <div class="col-md-3 d-flex flex-column justify-content-end ">
        <div class="form-group{{ $errors->has('estrutura[nro_sem_repasse]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[nro_sem_repasse]', 'Quantidade de UPHs que NÃO fizeram o repasse da ACI para a Federação:') !!}
        {!! Form::number('estrutura[nro_sem_repasse]', isset($formulario) ? null : $estrutura_federacao['nro_sem_repasse'], ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[nro_sem_repasse]') }}</small>
        </div>
    </div>
</div>
