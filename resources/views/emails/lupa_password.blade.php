<h2>Reset Password</h2>
<p>Kami menerima permintaan untuk mereset password Anda.</p>
<p>Silakan klik link di bawah ini untuk mereset:</p>

<a href="{{ route('password.reset.form', ['token' => $token]) }}">
    Reset Password Sekarang
</a>

<p>Link ini hanya berlaku selama <strong>5 menit</strong>.</p>
