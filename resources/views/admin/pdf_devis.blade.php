<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Devis {{ $devis->ref_id }}</title>
<style>
  body { font-family: DejaVu Sans, Arial, sans-serif; color:#111; }
  .header { display:flex; align-items:center; justify-content:space-between; border-bottom:2px solid #1e3a8a; padding:12px 0; }
  .brand { display:flex; align-items:center; gap:12px; }
  .brand img { height:46px; }
  .title { font-size:28px; color:#1e3a8a; font-weight:700; }
  .meta { margin:10px 0 20px; font-size:12px; color:#555; }
  .section { margin:18px 0; }
  .section h3 { font-size:14px; color:#1e3a8a; margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:4px; }
  table { width:100%; border-collapse:collapse; font-size:12px; }
  th, td { border:1px solid #e5e7eb; padding:8px; text-align:left; vertical-align:top; }
  .grid { display:flex; flex-wrap:wrap; gap:8px; }
  .thumb { width:80px; height:80px; object-fit:cover; border:1px solid #e5e7eb; border-radius:6px; }
  .footer { margin-top:20px; font-size:11px; color:#777; border-top:1px solid #e5e7eb; padding-top:8px; }
</style>
</head>
<body>
  <div class="header">
    <div class="brand">
      <img src="{{ $logo }}" alt="Logo" style="height:40px;">
      <div>
        <div class="title">Fiche Devis</div>
        <div class="meta">Devis #{{ $devis->ref_id }} — Date: {{ optional($devis->created_at)->format('d/m/Y') }}</div>
      </div>
    </div>
    <div style="text-align:right;font-size:12px;">
      @if($company)
        <div><strong>{{ $company->company_name }}</strong></div>
        <div>{{ $company->email }}</div>  <div>{{ $company->phone }}</div>
        <div>{{ $company->address }}</div>
      @endif
    </div>
  </div>

  <div class="section">
    <h3>Résumé</h3>
    <table>
      <tr><th>Référence</th><td>{{ $devis->ref_id }}</td></tr>
      <tr><th>Date</th><td>{{ optional($devis->created_at)->format('d/m/Y') }}</td></tr>
    </table>
  </div>

  <div class="section">
    <h3>Client</h3>
    <table>
      <tr><th>Nom</th><td>{{ $devis->name }}</td><th>Email</th><td>{{ $devis->email }}</td></tr>
      <tr><th>Téléphone</th><td>{{ $devis->phone }}</td><th>Entreprise</th><td>{{ $devis->company }}</td></tr>
      <tr><th>Type</th><td colspan="3">{{ strtoupper($devis->type) }}</td></tr>
    </table>
  </div>

  <div class="section">
    @if($devis->type === 'specific')
      <h3>Spécifications techniques</h3>
      <table>
        <tr><th>Type d’échangeur</th><td>{{ $devis->type_exchangeur }}</td><th>Ø Cuivre (mm)</th><td>{{ $devis->cuivre_diametre }}</td></tr>
        <tr><th>Pas ailette (mm)</th><td>{{ $devis->pas_ailette }}</td><th>Hauteur (mm)</th><td>{{ $devis->hauteur_mm }}</td></tr>
        <tr><th>Largeur (mm)</th><td>{{ $devis->largeur_mm }}</td><th>Longueur (mm)</th><td>{{ $devis->longueur_mm }}</td></tr>
        <tr><th>Longueur totale (mm)</th><td>{{ $devis->longueur_totale_mm }}</td><th>Nombre de tubes</th><td>{{ $devis->nombre_tubes }}</td></tr>
        <tr><th>Géométrie X (mm)</th><td>{{ $devis->geometrie_x_mm }}</td><th>Géométrie Y (mm)</th><td>{{ $devis->geometrie_y_mm }}</td></tr>
        <tr><th>Collecteur 1</th><td>{{ $devis->collecteur1_nb }}</td><th>Ø C1 (mm)</th><td>{{ $devis->collecteur1_diametre }}</td></tr>
        <tr><th>Collecteur 2</th><td>{{ $devis->collecteur2_nb }}</td><th>Ø C2 (mm)</th><td>{{ $devis->collecteur2_diametre }}</td></tr>
      </table>
    @else
      <h3>Informations standard</h3>
      <table>
        <tr><th>Produit</th><td>{{ $devis->product }}</td><th>Quantité</th><td>{{ $devis->quantity }}</td></tr>
      </table>
    @endif
  </div>

  <div class="section">
    @if($devis->type === 'specific')
      <h3>Exigences</h3>
      <div style="border:1px solid #e5e7eb; padding:10px; border-radius:6px; font-size:12px;">
        {!! nl2br(e($devis->requirements)) !!}
      </div>
    @else
      <h3>Message</h3>
      <div style="border:1px solid #e5e7eb; padding:10px; border-radius:6px; font-size:12px;">
        {!! nl2br(e($devis->message)) !!}
      </div>
    @endif
  </div>

  <div class="footer" style="text-align:center;">© {{ date('Y') }} ALCOIL — SARL OUEST EQUIPEMENT.</div>
</body>
</html>