<div>
  <p>
    Liebe(r) {{ $buchung->mitgliedsname }},<br />
    Anbei Ihre Saisonkarte für {{ $basisdaten->jahr }}.<br />
    Diese E-Mail enthält die Saisonkarte in Form von drei Anlagen:<br />
  <ul>
    <li>
      Als PNG-Datei (ein verbreitetes Bild-Format).
    </li>
    <li>
      Als JPG-Datei (ein noch verbreiteteres Bild-Format).
    </li>
    <li>
      Als PDF-Datei.
    </li>
  </ul>
  Mindestens eins dieser Formate sollte sich auf dem Handy darstellen lassen.<br />
  Außerdem können Sie sich über die Links unten die Saisonkarten jederzeit zusätzlich herunterladen.

  </p>


  <p>
    <a href="{{ route('skdownload', ['encNr' => $encNr, 'type' => 'png']) }}">Download Saisonkarte als PNG-Datei</a>
  </p>
  <p>
    <a href="{{ route('skdownload', ['encNr' => $encNr, 'type' => 'jpg']) }}">Download Saisonkarte als JPG-Datei</a>
  </p>
  <p>
    <a href="{{ route('skdownload', ['encNr' => $encNr, 'type' => 'pdf']) }}">Download Saisonkarte als PDF-Datei</a>
  </p>
</div>