<?php
use Carbon\Carbon;
?>

<div>
  <h1>Anmeldebestätigung für einen Termin zur Fahrradcodierung</h1>
  <p>{{ $anrede }},</p>
  <p>
    Sie sind für den Termin<br />
    {{ Carbon::parse($termin->datum)->translatedFormat('D, d.m') . " um " . $buchung->uhrzeit }}<br>
    angemeldet. Wir freuen uns, Sie dabei zu haben!<br />
    Falls Sie den Termin absagen müssen, klicken Sie bitte auf <a href="{{ $abmeldeUrl }}">diesen Link</a>.
  </p>
</div>