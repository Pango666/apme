@php
  // Colores de marca
  $borgo   = '#7B2321';
  $miel    = '#F2B705';
  $tinta   = '#1F2937';
  $muted   = '#6B7280';

  // Normalizar portada a URL absoluta (sirve si el path viene como /storage/...)
  $coverUrl = null;
  if (!empty($cover)) {
    $coverUrl = preg_match('#^https?://#', $cover) ? $cover : url($cover);
  }

  // Preheader opcional (lo muestran algunos clientes)
  $preheader = $preheader ?? 'Novedades de APME';
@endphp
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title>{{ $subject ?? ('Nuevo: '.$title) }}</title>
  <style>
    /* Mobile tweaks sin romper clientes de correo */
    @media (max-width:620px){
      .container{width:100% !important}
      .p-24{padding:16px !important}
      .btn{display:block !important; width:100% !important; text-align:center !important}
    }
  </style>
</head>
<body style="margin:0;background:#f6f6f6;">
  <!-- Preheader (invisible) -->
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
    {{ $preheader }}
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f6f6;padding:20px 0;">
    <tr>
      <td align="center">
        <table role="presentation" class="container" width="620" cellpadding="0" cellspacing="0" style="width:620px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.06);">
          <!-- Header simple -->
          <tr>
            <td style="padding:16px 24px;background:{{ $borgo }};color:#fff;">
              <table role="presentation" width="100%">
                <tr>
                  <td style="font:700 16px/1.2 Arial,Helvetica,sans-serif;">APME</td>
                  <td align="right" style="font:400 12px/1.2 Arial,Helvetica,sans-serif;opacity:.9">Miel Ecológica</td>
                </tr>
              </table>
            </td>
          </tr>

          @if($coverUrl)
            <tr>
              <td>
                <img src="{{ $coverUrl }}" alt="" width="620" style="display:block;max-width:100%;height:auto;">
              </td>
            </tr>
          @endif

          <tr>
            <td class="p-24" style="padding:24px;">
              <h1 style="margin:0 0 10px 0;color:{{ $borgo }};font:700 22px/1.3 Arial,Helvetica,sans-serif;">
                {{ $title }}
              </h1>

              @if(!empty($excerpt))
                <p style="margin:0 0 18px 0;color:{{ $tinta }};font:400 14px/1.6 Arial,Helvetica,sans-serif;">
                  {{ $excerpt }}
                </p>
              @endif

              <p style="margin:0 0 20px 0;">
                <a class="btn" href="{{ $url }}"
                   style="background:{{ $borgo }};color:#fff;text-decoration:none;border-radius:10px;
                          padding:12px 18px;display:inline-block;font:700 14px/1 Arial,Helvetica,sans-serif;">
                  Leer más
                </a>
              </p>

              <hr style="border:none;border-top:1px solid #eee;margin:18px 0">

              <p style="margin:0;color:{{ $muted }};font:400 12px/1.6 Arial,Helvetica,sans-serif;">
                ¿No quieres recibir más correos?
                {{-- si ya habilitas one-click, puedes reemplazar por tu enlace real --}}
                <a href="{{ url('/boletin/baja') }}" style="color:{{ $borgo }};text-decoration:underline;">Darte de baja</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:14px 24px;background:#fafafa;color:#6b7280;font:400 12px/1.6 Arial,Helvetica,sans-serif;">
              APME – Asociación de Productores de Miel Ecológica · Bolivia
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
