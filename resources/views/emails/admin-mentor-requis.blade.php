<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><style>
body{font-family:'Segoe UI',sans-serif;background:#f4f4f4;margin:0;padding:0;}
.wrap{max-width:540px;margin:40px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);}
.header{background:#7C3AED;padding:28px 32px;text-align:center;}
.header img{height:36px;}
.body{padding:32px;}
.title{font-size:1.15rem;font-weight:700;color:#1F2937;margin-bottom:12px;}
.msg{color:#374151;line-height:1.6;font-size:0.95rem;}
.btn{display:inline-block;margin-top:24px;padding:12px 28px;background:#7C3AED;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.9rem;}
.footer{padding:20px 32px;background:#f9fafb;text-align:center;font-size:0.78rem;color:#9CA3AF;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <span style="color:#fff;font-weight:800;font-size:1.3rem;font-family:'Urbanist',sans-serif;">DevHub</span>
    </div>
    <div class="body">
        <div class="title">⚠️ Attribution mentor requise</div>
        <div class="msg">
            Le projet collaboratif <strong>« {{ $titre }} »</strong> vient d'accueillir son premier membre,
            mais aucun mentor n'était disponible pour une attribution automatique.<br><br>
            Merci d'intervenir depuis le tableau de bord administrateur pour attribuer manuellement un mentor à cette équipe.
        </div>
        <a href="{{ $lien }}" class="btn">Accéder au tableau de bord</a>
    </div>
    <div class="footer">DevHub — Plateforme collaborative pour développeurs</div>
</div>
</body>
</html>
