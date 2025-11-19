<!doctype html>
<html>
  <body style="font-family:Arial,sans-serif">
    <h2>Confirmar suscripción</h2>
    <p>Hola{{ $sub->name ? " {$sub->name}" : '' }}, confirma tu suscripción al boletín de APME.</p>
    <p>
      <a href="{{ route('newsletter.confirm',$sub->token) }}"
         style="background:#7B2321;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none">
        Confirmar
      </a>
    </p>
    <p style="color:#555">Si no fuiste tú, ignora este correo.</p>
  </body>
</html>
