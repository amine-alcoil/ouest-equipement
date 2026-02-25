<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0f1e34;
            padding: 40px 20px;
            text-align: center;
        }
        .header img {
            height: 50px;
            width: auto;
        }
        .content {
            padding: 40px 30px;
            color: #334155;
            line-height: 1.6;
        }
        .content h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f1e34;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .content p {
            font-size: 16px;
            margin-bottom: 25px;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            background-color: #34682b;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
        }
        .subtext {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 25px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/Logo_ALCOIL_without_txt_org.png')) }}" alt="ALCOIL">
        </div>
        <div class="content">
            <h1>Bonjour {{ $name }},</h1>
            <p>Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte <strong>ALCOIL</strong>.</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Réinitialiser mon mot de passe</a>
            </div>

            <p>Ce lien de réinitialisation expirera dans <strong>60 minutes</strong>.</p>
            <p>Si vous n'avez pas demandé cette réinitialisation, aucune action n'est requise de votre part.</p>
            
            <div class="subtext">
                Si vous avez des difficultés à cliquer sur le bouton "Réinitialiser mon mot de passe", copiez et collez l'URL ci-dessous dans votre navigateur :<br>
                <a href="{{ $url }}" style="color: #34682b;">{{ $url }}</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ALCOIL. Tous droits réservés.</p>
            <p>Solutions Professionnelles d’Échangeurs Thermiques</p>
        </div>
    </div>
</body>
</html>