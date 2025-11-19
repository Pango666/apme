<!doctype html>
<html>
  <body style="font-family:Arial,Helvetica,sans-serif; color:#111;">
    @if($coverUrl)
      <img src="{{ $coverUrl }}" alt="" style="max-width:100%;border-radius:12px;">
    @endif
    <h2 style="margin-top:16px">{{ $title }}</h2>
    <p style="color:#444">{{ $excerpt }}</p>
    <p>
      <a href="{{ $ctaUrl }}" style="background:#7B2321;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">
        Ver detalle
      </a>
    </p>
    <hr style="margin:24px 0;">
    <p style="color:#666;font-size:12px">
      Si no deseas recibir más correos,
      <a href="{{ $unsubscribeUrl }}">desuscríbete aquí</a>.
    </p>
  </body>
</html>
