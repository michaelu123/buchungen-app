<div class="flex flex-col items-center justify-center">
  <h1 class="text-xl font-bold">Bestätigung Deiner Email-Adresse</h1>
  <p>Bitte klicke auf diesen Link, um Deine Email zu bestätigen.</p>
  <a href="{{ route('emailverifikation', ['emailb64' => $emailb64])}}">Bestätigen</a>
  </p>
</div>