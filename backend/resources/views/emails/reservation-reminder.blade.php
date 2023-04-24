<!DOCTYPE html>
<html>

<head>
  <title>予約のリマインダー</title>
</head>

<body>
  <p>こんにちは {{ $user->name }} さん</p>

  <p>このメールは、{{ $reservation->datetime }}に予約されたことをお知らせするために送信しています。</p>

  <p>予約の詳細は以下の通りです。</p>

  <ul>
    <li>予約日時: {{ $reservation->datetime }}</li>
    <li>利用者名: {{ $user->name }}</li>
  </ul>

  <p>ご利用ありがとうございました。</p>
</body>

</html>