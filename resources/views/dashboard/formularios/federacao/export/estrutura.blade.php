<table  width="100%">
    <tr>
        <td>UPHs Organizadas</td>
        <td align="right"><span class="badge bg-primary ">{{ $formulario->estrutura['uph_organizada'] }}</span></td>
    </tr>
    <tr>
        <td>UPHs não Organizadas</td>
        <td align="right"><span class="badge bg-primary">{{ $formulario->estrutura['uph_nao_organizada'] }}</span></td>
    </tr>
    <tr>
        <td>UPHs que repassaram a ACI</td>
        <td align="right"><span class="badge bg-primary">{{ $formulario->estrutura['nro_repasse'] }}</span></td>
    </tr>
    <tr>
        <td>UPHs que não repassaram a ACI</td>
        <td align="right"><span class="badge bg-primary">{{ $formulario->estrutura['nro_sem_repasse'] }}</span></td>
    </tr>
    <tr>
        <td>Repasse da ACI</td>
        <td align="right"><span class="badge bg-primary">{{ $formulario->aci['repasse'] == 'S' ? $formulario->aci['valor'] : 0 }}</span></td>
    </tr>
</table>
