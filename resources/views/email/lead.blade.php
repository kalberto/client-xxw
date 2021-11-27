<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>xxw - Contato site</title>
</head>
<body>
<?php
	$x = 0;

	function calcZebra(){
		global $x;
		$x++;
		if(($x%2==0) ? $rgb = 255 : $rgb =230)

		return $rgb.','.$rgb.','.$rgb;
	}
?>
<div style="font-size:12.8px;background-color:rgb(255,255,255);text-decoration-style:initial;text-decoration-color:initial;font-family:calibri">
   <p>Você recebeu um email de contato do site xxw canais</p>
   <table align="center" style="font-family:calibri;margin:20px" width="100%">
      <tbody>
		 @if(isset($email) && $email != null)
			<tr style="background-color:rgb(<?php echo calcZebra();?>)">
		        <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Email</td>
		        <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$email}}</td>
		     </tr>
		 @endif
		@if(isset($documento) && $documento != null)
			<tr style="background-color:rgb(<?php echo calcZebra();?>)">
		    	<td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Documento</td>
		    	<td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$documento}}</td>
		    </tr>
		@endif
		@if(isset($assunto) && $assunto != null)
			<tr style="background-color:rgb(<?php echo calcZebra();?>)">
			    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Assunto</td>
			    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$assunto}}</td>
			 </tr>
		@endif
		@if(isset($mensagem) && $mensagem != null)
			<tr style="background-color:rgb(<?php echo calcZebra();?>)">
				<td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Mensagem</td>
				<td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mensagem}}</td>
			</tr>
         @endif
      </tbody>
   </table>
   <p><br><br>Em caso de dúvidas entre em contato com o administrador(Etools/GPAC).<span>&nbsp;</span><br><br>(Não Responder este E-<span class="il">Mail</span>) - {{date('d/m/Y')}} {{date('h:i:s')}}</p>
</div>
</body>
</html>
