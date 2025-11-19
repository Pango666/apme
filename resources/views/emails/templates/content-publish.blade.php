<!doctype html>
<html>
  <body style="font-family:Arial,Helvetica,sans-serif;background:#f6f6f6;padding:20px">
    <table width="600" align="center" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden">
      <tr>
        <td>
          <img src="{{ $cover }}" alt="" width="600" style="display:block;max-width:100%">
        </td>
      </tr>
      <tr>
        <td style="padding:24px">
          <h2 style="margin:0 0 10px 0;color:#7B2321">{{ $title }}</h2>
          @if(!empty($excerpt))
            <p style="color:#444">{{ $excerpt }}</p>
          @endif
          <p style="margin:20px 0">
            <a href="{{ $url }}" style="background:#7B2321;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none">Leer más</a>
          </p>
          <hr style="border:none;border-top:1px solid #eee;margin:24px 0">
          <p style="font-size:12px;color:#666">
            ¿No quieres recibir más correos?
            <a href="{{ '{{unsubscribe_url}}' }}">Darte de baja</a>.
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>
