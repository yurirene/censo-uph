<div class="row">
    <div class="col-md-3 col-sm-6 mt-3">
        <div class="form-group{{ $errors->has('aci[uph_repassaram]') ? ' has-error' : '' }}">
        {!! Form::label('aci[uph_repassaram]', 'Quantidade de UPHs que fizeram o repasse da ACI para as Federações') !!}
        {!! Form::text('aci[uph_repassaram]', isset($formulario) ? null : $estrutura_sinodal['quantidade_uph_repasse'], ['readonly' => true,'id' => 'aci[uph_repassaram]', 'class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('aci[uph_repassaram]') }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mt-3">
        <div class="form-group{{ $errors->has('aci[uph_nao_repassaram]') ? ' has-error' : '' }}">
        {!! Form::label('aci[uph_nao_repassaram]', 'Quantidade de UPHs que não fizeram o repasse da ACI para as Federações') !!}
        {!! Form::text('aci[uph_nao_repassaram]', isset($formulario) ? null : $estrutura_sinodal['quantidade_uph_sem_repasse'], ['readonly' => true,'class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('aci[uph_nao_repassaram]') }}</small>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mt-3">
        <div class="form-group{{ $errors->has('aci[federacao_repassaram]') ? ' has-error' : '' }}">
        {!! Form::label('aci[federacao_repassaram]', 'Quantidade de Federações que fizeram o repasse da ACI para a Sinodal') !!}
        {!! Form::text('aci[federacao_repassaram]', isset($formulario) ? null : $estrutura_sinodal['federacao_nro_repasse'], ['id' => 'aci[federacao_repassaram]', 'class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('aci[federacao_repassaram]') }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mt-3">
        <div class="form-group{{ $errors->has('aci[federacao_nao_repassaram]') ? ' has-error' : '' }}">
        {!! Form::label('aci[federacao_nao_repassaram]', 'Quantidade de Federações que não fizeram o repasse da ACI para a Sinodal') !!}
        {!! Form::text('aci[federacao_nao_repassaram]', isset($formulario) ? null : $estrutura_sinodal['federacao_nro_sem_repasse'], ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('aci[federacao_nao_repassaram]') }}</small>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-6 mt-3">
        <div class="form-group{{ $errors->has('aci[repasse_sinodal]') ? ' has-error' : '' }}">
            {!! Form::label('aci[repasse]', 'A Sinodal fez o repasse da ACI para a CNM?') !!}
            {!! Form::select('aci[repasse]',['S' => 'Sim', 'N' => 'Não'], isset($formulario) ? null : 'N', ['id' => 'aci[repasse]', 'class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('aci[repasse]') }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mt-3">
        <div class="form-group{{ $errors->has('aci[valor_repassado]') ? ' has-error' : '' }}">
        {!! Form::label('aci[valor_repassado]', 'Valor do repasse da ACI para a CNM') !!}
        {!! Form::text('aci[valor_repassado]', isset($formulario) ? null : 0, ['class' => 'form-control isMoney', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('aci[valor_repassado]') }}</small>
        </div>
    </div>
</div>
