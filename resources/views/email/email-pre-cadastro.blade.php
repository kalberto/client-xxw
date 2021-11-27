<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SELECT | Pedido de cadatro</title>
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
        <p>Pedido de cadastro do Select.</p>
        <table align="center" style="font-family:calibri;margin:20px" width="100%">
            <tbody>
                @if(isset($mail_data['documento']) && $mail_data['documento'] != null)
                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
                    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Documento:</td>
                    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['documento']}}</td>
                </tr>
                @endif

                @if(isset($mail_data['razao_social']) && $mail_data['razao_social'] != null)
                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
                    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Razão Social:</td>
                    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['razao_social']}}</td>
                </tr>
                @endif

                @if(isset($mail_data['contato_responsavel']) && $mail_data['contato_responsavel'] != null)
                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
                    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Contato responsável:</td>
                    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['contato_responsavel']}}</td>
                </tr>
                @endif
                @if(isset($mail_data['email']) && $mail_data['email'] != null)
	                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
		                <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">E-mail:</td>
		                <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['email']}}</td>
	                </tr>
                @endif
                @if(isset($mail_data['telefone']) && $mail_data['telefone'] != null)
	                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
		                <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Telefone:</td>
		                <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['telefone']}}</td>
	                </tr>
                @endif
                @if(isset($mail_data['cidade']) && $mail_data['cidade'] != null)
	                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
		                <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Cidade:</td>
		                <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['cidade']}}</td>
	                </tr>
                @endif
                @if(isset($mail_data['estado']) && $mail_data['estado'] != null)
	                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
		                <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Estado:</td>
		                <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$mail_data['estado']}}</td>
	                </tr>
                @endif
            </tbody>
        </table>
        <p><br><br>Em caso de dúvidas entre em contato.<span>&nbsp;</span><br><br>(Não Responder este E-<span class="il">Mail</span>) - {{date('d/m/Y')}} {{date('h:i:s')}}</p>
    </div>
</body>
</html>
