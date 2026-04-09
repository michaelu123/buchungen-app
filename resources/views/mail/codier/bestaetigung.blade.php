<?php
use Carbon\Carbon;
?>

<div>
  <h1>Anmeldebestätigung für einen Termin zur Fahrradcodierung</h1>
  <p>{{ $anrede }},</p>
  <p>
    hiermit bestätigen wir Ihnen den gewünschten Termin für die Codierung Ihres Fahrrads in der
    Platenstraße 4 in 80336 München (Radlerhaus) am<br />
    {{ Carbon::parse($termin->datum)->translatedFormat('D, d.m') . " um " . $buchung->uhrzeit }}<br>
  </p>
  <p>
    Bitte kommen Sie pünktlich zum vorgenannten Termin und bringen Sie folgende Unterlagen mit:
  <ul>
    <li>
      Ihren Personalausweis (sofern Ihre Wohnadresse NICHT auf dem Personalausweis steht, bringen Sie
      bitte eine offizielle Meldebestätigung mit).
    </li>
    <li>
      den Kaufbeleg für Ihr Fahrrad (sofern vorhanden, siehe unsere FAQ im Anhang dieser Mail und <a
        href="https://muenchen.adfc.de/fileadmin/Gliederungen/Pedale/muenchen/user_upload/ADFC_Muenchen/AGs/AG_Codierung/Codierung-FAQ.pdf">hier</a>).
    </li>
    <li>
      den ausgefüllten Codierauftrag (1x), im Anhang dieser Mail und <a
        href="https://muenchen.adfc.de/fileadmin/Gliederungen/Pedale/muenchen/user_upload/ADFC_Muenchen/AGs/AG_Codierung/Codierauftrag_2025_MUC.pdf">hier</a>.
    </li>
  </ul>
  </p>
  <p>
    Falls Sie den Termin absagen müssen, klicken Sie bitte auf <a href="{{ $abmeldeUrl }}">diesen Link</a>.
  </p>
</div>