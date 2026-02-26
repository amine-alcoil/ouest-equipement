<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Devis {{ $devis->ref_id }}</title>
<style>
  @page { margin: 0; }
  body { 
    font-family: 'DejaVu Sans', sans-serif; 
    color: #334155; 
    line-height: 1.5; 
    margin: 0;
    padding: 0;
    background-color: #fff;
  }
  .page-container { padding: 40px; }
  
  /* Decorative Top Bar */
  .top-accent { 
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 8px;
    background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
  }

  /* Accent Colors */
  .text-primary { color: #1e3a8a; }
  .bg-primary { background-color: #1e3a8a; }
  .border-primary { border-color: #1e3a8a; }

  /* Header Design */
  .header { 
    display: table; 
    width: 100%; 
    border-bottom: 3px solid #1e3a8a; 
    padding-bottom: 20px; 
    margin-bottom: 30px;
  }
  .header-left { display: table-cell; vertical-align: middle; width: 60%; }
  .header-right { display: table-cell; vertical-align: top; width: 40%; text-align: right; font-size: 11px; color: #64748b; }
  
  .logo { height: 80px; margin-bottom: 5px; }
  .document-title { 
    font-size: 28px; 
    font-weight: 800; 
    text-transform: uppercase; 
    letter-spacing: 2px;
    margin: 0;
    color: #1e3a8a;
  }
  
  /* Info Grid */
  .info-grid { display: table; width: 100%; margin-bottom: 30px; border-spacing: 20px 0; margin-left: -20px; }
  .info-column { display: table-cell; width: 50%; vertical-align: top; }
  .info-box { 
    background-color: #f8fafc; 
    border-radius: 8px; 
    padding: 15px; 
    border-left: 4px solid #1e3a8a;
    min-height: 100px;
  }
  .info-box h3 { 
    margin: 0 0 10px 0; 
    font-size: 12px; 
    text-transform: uppercase; 
    color: #64748b; 
    letter-spacing: 0.5px;
  }
  .info-content { font-size: 13px; color: #1e293b; }
  .info-row { margin-bottom: 4px; }
  .info-label { font-weight: 600; color: #475569; width: 100px; display: inline-block; }

  /* Table Design */
  .section-title { 
    font-size: 14px; 
    font-weight: 700; 
    color: #1e3a8a; 
    margin: 30px 0 15px 0; 
    text-transform: uppercase;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 8px;
  }
  
  table { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 20px; }
  th { 
    background-color: #f1f5f9; 
    color: #475569; 
    font-weight: 700; 
    text-align: left; 
    padding: 12px 15px; 
    font-size: 11px; 
    text-transform: uppercase;
    border-bottom: 2px solid #e2e8f0;
  }
  td { 
    padding: 12px 15px; 
    border-bottom: 1px solid #f1f5f9; 
    font-size: 12px; 
    vertical-align: top;
  }
  tr:last-child td { border-bottom: none; }
  
  .tech-specs { background-color: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
  .tech-specs td { width: 25%; }
  .tech-label { font-weight: 600; color: #64748b; background-color: #f8fafc; }

  /* Requirements/Message Box */
  .content-box { 
    background-color: #fdfdfd; 
    border: 1px solid #e2e8f0; 
    border-radius: 8px; 
    padding: 20px; 
    font-size: 13px; 
    color: #334155;
    white-space: pre-line;
  }

  /* Footer */
  .footer { 
    position: fixed; 
    bottom: 0; 
    left: 0; 
    right: 0; 
    padding: 20px 40px; 
    text-align: center; 
    font-size: 10px; 
    color: #94a3b8;
    border-top: 1px solid #f1f5f9;
  }
</style>
</head>
<body>
  <div class="top-accent"></div>
  <div class="page-container">
    <div class="header">
      <div class="header-left">
        <img src="{{ $logo }}" class="logo" alt="ALCOIL">
        <h1 class="document-title">Devis Professionnel</h1>
        <div style="font-size: 14px; color: #64748b; margin-top: 5px;">
          Réf: <span style="color: #1e3a8a; font-weight: 700;">{{ $devis->ref_id }}</span> 
          &bull; Date: {{ optional($devis->created_at)->format('d/m/Y') }}
        </div>
      </div>
      <div class="header-right">
        @if($company)
          <div style="font-size: 14px; font-weight: 800; color: #1e3a8a; margin-bottom: 5px;">{{ $company->company_name }}</div>
          <div>{{ $company->address }}</div>
          <div>Tél: {{ $company->phone }}</div>
          <div>Email: {{ $company->email }}</div>
        @endif
      </div>
    </div>

    <div class="info-grid">
      <div class="info-column">
        <div class="info-box">
          <h3>Informations Client</h3>
          <div class="info-content">
            <div class="info-row"><span class="info-label">Nom:</span> {{ $devis->name }}</div>
            <div class="info-row"><span class="info-label">Entreprise:</span> {{ $devis->company ?: '-' }}</div>
            <div class="info-row"><span class="info-label">Email:</span> {{ $devis->email }}</div>
            <div class="info-row"><span class="info-label">Tél:</span> {{ $devis->phone ?: '-' }}</div>
          </div>
        </div>
      </div>
      <div class="info-column">
        <div class="info-box" style="border-left-color: #334155;">
          <h3>Détails du Devis</h3>
          <div class="info-content">
            <div class="info-row"><span class="info-label">Type:</span> {{ $devis->type === 'specific' ? 'Échangeur Spécifique' : 'Produit Standard' }}</div>
            <div class="info-row"><span class="info-label">Statut:</span> {{ strtoupper($devis->status) }}</div>
          </div>
        </div>
      </div>
    </div>

    @if($devis->type === 'specific')
      <div class="section-title">Spécifications Techniques</div>
      <table class="tech-specs">
        <tr>
          <td class="tech-label">Type d'échangeur</td><td>{{ $devis->type_exchangeur }}</td>
          <td class="tech-label">Ø Cuivre (mm)</td><td>{{ $devis->cuivre_diametre }}</td>
        </tr>
        <tr>
          <td class="tech-label">Pas ailette (mm)</td><td>{{ $devis->pas_ailette }}</td>
          <td class="tech-label">Hauteur (mm)</td><td>{{ $devis->hauteur_mm }}</td>
        </tr>
        <tr>
          <td class="tech-label">Largeur (mm)</td><td>{{ $devis->largeur_mm }}</td>
          <td class="tech-label">Longueur (mm)</td><td>{{ $devis->longueur_mm }}</td>
        </tr>
        <tr>
          <td class="tech-label">Long. Totale (mm)</td><td>{{ $devis->longueur_totale_mm }}</td>
          <td class="tech-label">Nombre de tubes</td><td>{{ $devis->nombre_tubes }}</td>
        </tr>
        <tr>
          <td class="tech-label">Géométrie X/Y</td><td>{{ $devis->geometrie_x_mm }} / {{ $devis->geometrie_y_mm }}</td>
          <td class="tech-label">Collecteurs</td><td>C1: {{ $devis->collecteur1_nb }} / C2: {{ $devis->collecteur2_nb }}</td>
        </tr>
      </table>

      <div class="section-title">Exigences Particulières</div>
      <div class="content-box">
        {!! nl2br(e($devis->requirements)) !!}
      </div>
    @else
      <div class="section-title">Détails de la Commande</div>
      <table>
        <thead>
          <tr>
            <th>Désignation du Produit</th>
            <th style="text-align: center; width: 100px;">Quantité</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight: 600; font-size: 14px;">{{ $devis->product }}</td>
            <td style="text-align: center; font-size: 14px;">{{ $devis->quantity }}</td>
          </tr>
        </tbody>
      </table>

      <div class="section-title">Message / Instructions</div>
      <div class="content-box">
        {!! nl2br(e($devis->message)) !!}
      </div>
    @endif

    <div style="margin-top: 50px; text-align: right; padding-right: 40px;">
      <div style="font-size: 12px; color: #64748b; margin-bottom: 40px;">Cachet et Signature</div>
      <div style="width: 200px; border-bottom: 1px solid #e2e8f0; display: inline-block;"></div>
    </div>
  </div>

  <div class="footer">
    ALCOIL &bull; SARL OUEST EQUIPEMENT &bull; {{ date('Y') }}<br>
    Ce document est généré numériquement et constitue une fiche technique de devis.
  </div>
</body>
</html>