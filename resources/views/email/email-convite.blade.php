<html>
<head>
    <meta charset="UTF-8">
    <title>Participe do evento: {{ $data['nome_evento'] }}</title>
</head>
<body>
<table width="100%" height="100%" align="center" valign="middle" cellpadding="0" cellspacing="0" bgcolor="d7d7d7">
    <tr><td>
            <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="ffffff">
                <!-- header -->
                <tr>
                    <td>
                        <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr width="600">
                                <td>
                                    <table width="600" height="85" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#c9c9c9">
                                        <tr width="600" height="85">
                                            <td width="193" height="85"><img src="{{url("images/xxylogo.png")}}" alt="Logotipo xxy" width="85" height="85" style="display: block; margin:10px 0 10px 50px" align="absmiddle"></td>
                                            <td width="407" valign="middle">
                                                <table width="407" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td width="20"></td>
                                                        <td width="367" style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 26px; color: #ffffff;">Convite</td>
                                                        <td width="20"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="600" height="500" align="center" valign="middle" cellpadding="0" cellspacing="0" border="0">
                            <tr width="600" height="50"><td colspan="3"></td></tr>
                            <tr>
                                <td width="50"></td>
                                <td width="470" style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 16px; color: #000000;">
                                    <h1 style="margin-top: 10px; margin-bottom: 10px; font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 16px; text-transform: uppercase; color: #000000;">Olá {{ $data['nome'] }}. <strong style="color: #9bb381;"></strong></h1>
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 16px; line-height: 1.4; color: #000000;">Participe do evento: {{ $data['nome_evento'] }}.</p>
                                    <a href="{{ $data['url'] }}" title="Link evento" style="text-decoration: none;background: #888888;border: none;color: #fff;font-size: 14px;font-weight: 700;text-transform: uppercase;border-radius: 3px;line-height: 45px;width: 150px;display: block;text-align: center;">Link evento</a>
                                    <br>
                                </td>
                                <td width="80"></td>
                            </tr>
                            <tr width="600" height="50"><td colspan="3"></td></tr>
                        </table>
                    </td>
                </tr>
                <!-- rodapé -->
                <tr>
                    <td>
                        <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#c9c9c9">
                            <tr>
                                <td width="193" valign="top"><img src="{{ url("images/xxwlogo.png")}}" alt="Logotipo xxw" width="85" height="85" style=" margin:10px 0 10px 50px"></td>
                                <td valign="top" style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 13px; color: #000;">
                                    <p style="margin-top:10px; margin-bottom: 10px; font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 13px; color: #000;"><strong>Contato</strong></p>
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; line-height: 1.4; color: #000;">
                                        <span style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; color: #000;">(41) 0000-0000 • Curitiba</span><br/>
                                    </p>
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; color: #000;">
                                        <a style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; color: #000;" href="mailto:falecomselect@xxw.com">falecomselect@xxw.com</a>
                                    </p>
                                </td>
                                <td valign="top" style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 13px; color: #000;">
                                    <p style="margin-top:10px; margin-bottom: 10px; font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 13px; color: #000;"><strong>Endereço</strong></p>
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; line-height: 1.4; color: #000;">
                                        <span style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; color: #000;">Rua zero – Curitiba, PR</span><br/>
                                        <span style="font-family: 'Trebuchet MS', Arial, sans-serif; font-weight: bold; font-size: 10px; color: #000;">CEP.: 0000-000</span><br/>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td></tr>
</table>
</body>
</html>
