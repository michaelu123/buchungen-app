<?php
use Carbon\Carbon;
?>

<div>
  <h1>Anmeldebestätigung für einen Termin zur Fahrradcodierung</h1>
  <p>{{ $anrede }},</p>
  <p>
    Sie sind für den Termin
    {{ Carbon::parse($termin->datum)->translatedFormat('D, d.m') . " um " . $buchung->uhrzeit }}<br>
    angemeldet. Wir freuen uns, Sie dabei zu haben!
  </p>
</div>