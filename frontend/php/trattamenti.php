<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Preventivo Trattamenti</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
  </style>
</head>
<body>

<div id="areaPDF">
  <div class="intestazione">
    <img src="logo_studio_dentistico.jpg" alt="Logo Studio Brusco">
    <h2>Seleziona i trattamenti</h2>
  </div>

  <p><strong>Data:</strong> <span id="data-odierna"></span></p>
  <p><strong>Nome paziente:</strong> <input type="text" id="nome-paziente" placeholder="Mario Rossi" style="padding:5px; border-radius:5px; width: 250px;"></p>

  <form id="form-trattamenti">
    <div id="contenitore-trattamenti"></div>

    <div class="gruppo">
      <h3>Altri Trattamenti</h3>
      <div class="trattamento">
        <input type="checkbox" data-prezzo="90" class="check" id="extra1">
        <label for="extra1">Ablazione tartaro (90€)</label>
        Quantità: <input type="number" value="1" min="1" class="quantita">
        Denti: <input type="text" class="denti">
      </div>
      <div class="trattamento">
        <input type="checkbox" data-prezzo="170" class="check" id="extra2">
        <label for="extra2">Sbiancamento (170€)</label>
        Quantità: <input type="number" value="1" min="1" class="quantita">
        Denti: <input type="text" class="denti">
      </div>
    </div>

    <div class="bottoni">
      <button type="button" onclick="calcolaTotale()">Calcola Totale</button>
      <button type="button" onclick="salvaPDF()">Salva come PDF</button>
      <a href="../home/index.php" class="btn-home">Torna alla Home</a>
    </div>
  </form>

  <div class="scontrino" id="scontrino"></div>

  <p style="margin-top:40px;"><strong>Firma paziente:</strong></p>
  <div style="border-top: 1px solid #000; width: 300px; margin-top: 20px;"></div>
</div>

<script>
  const datiTrattamenti = {
    "Diagnosi": [
      ["Visita specialistica", 60],
      ["Visita d’urgenza", 100],
      ["Radiografia OTP", 80],
      ["Radiografia endorale", 25]
    ],
    "Conservativa": [
      ["Otturazione cavità mesiale", 100],
      ["Otturazione cavità distale", 100],
      ["Otturazione cavità occlusale", 100],
      ["Ricostruzione con perno", 200]
    ],
    "Chirurgia": [
      ["Estrazione dente complesso", 150],
      ["Estrazione dente semplice", 90],
      ["Estrazione ottavo incluso", 200]
    ],
    "Protesi mobile/fissa": [
      ["Protesi totale inferiore/superiore", 1400],
      ["Protesi scheletrata", 700],
      ["Protesi ceramica/zirconio", 700]
    ]
  };

  const contenitore = document.getElementById("contenitore-trattamenti");

  for (let gruppo in datiTrattamenti) {
    const div = document.createElement("div");
    div.className = "gruppo";
    div.innerHTML = `<h3>${gruppo}</h3>`;
    datiTrattamenti[gruppo].forEach((trattamento, i) => {
      const id = `${gruppo}_${i}`.replace(/\s+/g, "_");
      div.innerHTML += `
        <div class="trattamento">
          <input type="checkbox" data-prezzo="${trattamento[1]}" class="check" id="${id}">
          <label for="${id}">${trattamento[0]} (${trattamento[1]}€)</label>
          Quantità: <input type="number" value="1" min="1" class="quantita">
          Denti: <input type="text" class="denti">
        </div>`;
    });
    contenitore.appendChild(div);
  }

  function calcolaTotale() {
    const checks = document.querySelectorAll(".check");
    const quantitaInputs = document.querySelectorAll(".quantita");
    const dentiInputs = document.querySelectorAll(".denti");
    let totale = 0;
    let scontrino = "=== PREVENTIVO ===\n";
    let errore = false;

    checks.forEach((checkbox, i) => {
      if (checkbox.checked) {
        const prezzo = parseFloat(checkbox.dataset.prezzo);
        const qta = parseInt(quantitaInputs[i].value);
        const denti = dentiInputs[i].value.trim();
        if (!validaDenti(denti)) {
          alert(`Errore nei denti inseriti per: ${checkbox.nextElementSibling.textContent}`);
          errore = true;
          return;
        }
        const parziale = prezzo * qta;
        totale += parziale;
        scontrino += `✔ ${checkbox.nextElementSibling.textContent} x${qta} = ${parziale.toFixed(2)}€ (denti: ${denti})\n`;
      }
    });

    if (errore) return;

    if (totale > 70) {
      scontrino += "\n➕ Bollo dentistico: 2.00€";
      totale += 2;
    }

    scontrino += `\n\nTotale: ${totale.toFixed(2)}€`;
    document.getElementById("scontrino").textContent = scontrino;
  }

  function validaDenti(input) {
    if (!input) return false;
    const pattern = /^(\d{2})([-,](\d{2}))*$/;
    if (!pattern.test(input)) return false;
    const numeri = input.split(/[-,]/).map(Number);
    return numeri.every(n => n >= 11 && n <= 48);
  }

  function salvaPDF() {
  const nome = document.getElementById("nome-paziente").value || "preventivo";
  const oggi = new Date();
  const dataOra = oggi.toLocaleDateString("it-IT") + " " + oggi.toLocaleTimeString("it-IT");

  const scontrinoText = document.getElementById("scontrino").textContent;
  const righe = scontrinoText.split("\n");

  const altezzaContenuto = 60 + righe.length * 6 + 20; // calcolo altezza stimata
  const doc = new jspdf.jsPDF({
    orientation: 'p',
    unit: 'mm',
    format: [80, altezzaContenuto]
  });

  let y = 10;
  doc.setFont("Courier", "normal");
  doc.setFontSize(10);

  // Intestazione
  doc.setFontSize(12);
  doc.setFont("Courier", "bold");
  doc.text("Studio Dentistico Brusco", 10, y);
  y += 6;
  doc.setFontSize(10);
  doc.setFont("Courier", "normal");
  doc.text("Via Murello - Padova", 10, y);
  y += 6;
  doc.text("Data e ora: " + dataOra, 10, y);
  y += 6;
  doc.text("Paziente: " + nome, 10, y);
  y += 6;
  doc.text("========================", 10, y);
  y += 6;
  doc.text("       PREVENTIVO       ", 10, y);
  y += 6;
  doc.text("========================", 10, y);
  y += 6;

  righe.forEach(riga => {
    if (riga.startsWith("✔")) {
      const match = riga.match(/✔ (.+) x(\d+) = ([\d.,]+)€ \(denti: (.+)\)/);
      if (match) {
        const descrizione = match[1] + " x" + match[2];
        const prezzo = match[3] + "€";
        const punti = ".".repeat(Math.max(0, 32 - descrizione.length - prezzo.length));
        doc.text(descrizione + punti + prezzo, 10, y);
        y += 5;
        doc.text("  Denti: " + match[4], 10, y);
        y += 5;
      }
    } else if (riga.includes("Bollo dentistico")) {
      const descrizione = "Bollo dentistico";
      const prezzo = "2.00€";
      const punti = ".".repeat(Math.max(0, 32 - descrizione.length - prezzo.length));
      doc.text(descrizione + punti + prezzo, 10, y);
      y += 5;
    } else if (riga.startsWith("Totale")) {
      const prezzo = riga.split(": ")[1];
      const descrizione = "TOTALE";
      const punti = ".".repeat(Math.max(0, 32 - descrizione.length - prezzo.length));
      y += 3;
      doc.text(descrizione + punti + prezzo, 10, y);
      y += 6;
    }
  });

  // Firma
  y += 10;
  doc.text("Firma paziente:", 10, y);
  y += 12;
  doc.line(10, y, 70, y); // riga per la firma

  // Salvataggio
  doc.save(`Preventivo_${nome.replace(/\s+/g, "_")}.pdf`);
}




  document.addEventListener("DOMContentLoaded", () => {
    const oggi = new Date();
    const dataString = oggi.toLocaleDateString("it-IT");
    document.getElementById("data-odierna").textContent = dataString;
  });
</script>

</body>
</html>
