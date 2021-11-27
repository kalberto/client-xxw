<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{$subject}}</title>
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
        <p>Contato site xxx.</p>
        <table align="center" style="font-family:calibri;margin:20px" width="100%">
            <tbody>
                @if(isset($nome) && $nome != null)
                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
                    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Nome:</td>
                    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$nome}}</td>
                </tr>
                @endif

                @if(isset($email) && $email != null)
                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
                    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">E-mail:</td>
                    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$email}}</td>
                </tr>
                @endif

                @if(isset($celular) && $celular != null)
                <tr style="background-color:rgb(<?php echo calcZebra();?>)">
                    <td width="300" style="font-family:arial,sans-serif;margin:0px;padding:5px">Celular:</td>
                    <td style="font-family:arial,sans-serif;margin:0px;text-align:justify;padding:5px">{{$celular}}</td>
                </tr>
                @endif
            </tbody>
        </table>
        <p><br><br>Em caso de dúvidas entre em contato.<span>&nbsp;</span><br><br>(Não Responder este E-<span class="il">Mail</span>) - {{date('d/m/Y')}} {{date('h:i:s')}}</p>
    </div>
</body>
</html>
