<div class="row">
    <div class="col-md-3 col-sm-6 mt-3 d-flex flex-column justify-content-end form-group">
        <div class="form-group{{ $errors->has('estrutura[uph_organizada]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[uph_organizada]', 'Quantidade de UPHs organizadas na Confederação Sinodal') !!}
        {!! Form::number('estrutura[uph_organizada]',isset($formulario) ? null : $estrutura_sinodal['quantidade_uph'], ['readonly' => true, 'id' => 'estrutura[uph_organizada]', 'class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[uph_organizada]') }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mt-3 d-flex flex-column justify-content-end form-group">
        <div class="form-group{{ $errors->has('estrutura[uph_nao_organizada]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[uph_nao_organizada]', 'Quantidade de igrejas sem UPHs organizadas') !!}
        {!! Form::number('estrutura[uph_nao_organizada]', isset($formulario) ? null : $estrutura_sinodal['quantidade_sem_uph'], ['readonly' => true,'class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[uph_nao_organizada]') }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mt-3 d-flex flex-column justify-content-end form-group">
        <div class="form-group{{ $errors->has('estrutura[federacao_organizada]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[federacao_organizada]', 'Quantidade de Federações organizadas na Confederação Sinodal') !!}
        {!! Form::number('estrutura[federacao_organizada]',isset($formulario) ? null : $estrutura_sinodal['quantidade_federacoes'], ['readonly' => true, 'id' => 'estrutura[federacao_organizada]', 'class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[federacao_organizada]') }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mt-3 d-flex flex-column justify-content-end form-group">
        <div class="form-group{{ $errors->has('estrutura[federacao_nao_organizada]') ? ' has-error' : '' }}">
        {!! Form::label('estrutura[federacao_nao_organizada]', 'Quantidade de Presbitérios sem Federações organizadas') !!}
        {!! Form::number('estrutura[federacao_nao_organizada]', isset($formulario) ? null : $estrutura_sinodal['quantidade_sem_federacao'], ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('estrutura[federacao_nao_organizada]') }}</small>
        </div>
    </div>
</div>
