<table>
  <thead>
    <tr>
      <th style="width:20px;text-align:center;background-color:#0658c9;color:#ffffff;">USUARIO</th>
      <th style="width:25px;text-align:center;background-color:#0658c9;color:#ffffff;">NOMBRE</th>
      <th style="width:35px;text-align:center;background-color:#0658c9;color:#ffffff;">CORREO ELECTRÓNICO</th>
      <th style="width:25px;text-align:center;background-color:#0658c9;color:#ffffff;">FECHA REGISTRO</th>
    </tr>
  </thead>
  @for ($i=0; $i < count($usuarios_return); $i++)
    <tr>
    <td>{{$usuarios_return[$i]['usuario']}}</td>
    <td>{{$usuarios_return[$i]['nombre']}}</td>
    <td>{{$usuarios_return[$i]['correo']}}</td>
    <td>{{$usuarios_return[$i]['fecha']}}</td>
    </tr>
    <tr>
      <td colspan="4" style="height:1px;background-color:#808080;"></td>
    </tr>
    <tr>
      <td colspan="3" style="text-align:right;background-color">SUCURSALES</td>
    </tr>

    @php
    $sucursales = $usuarios_return[$i]['sucursales'];
    @endphp

    @if(count($sucursales) > 0)
      @for ($j=0; $j < count($sucursales); $j++)
        <tr>
        <td colspan="3" style="text-align:right;">{{$sucursales[$j]->sucursal}}</td>
      </tr>
      @endfor
    @endif
    <tr>
      <td colspan="4" style="height:1px;background-color:#808080;"></td>
    </tr>
  @endfor
</table>
