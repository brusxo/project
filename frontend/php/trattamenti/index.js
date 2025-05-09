const datiTrattamenti = {
  Diagnosi: [
    ["Visita specialistica", 60],
    ["Visita d’urgenza", 100],
    ["Radiografia OTP", 80],
    ["Radiografia endorale", 25],
  ],
  Conservativa: [
    ["Otturazione cavità mesiale", 100],
    ["Otturazione cavità distale", 100],
    ["Otturazione cavità occlusale", 100],
    ["Ricostruzione con perno", 200],
  ],
  Chirurgia: [
    ["Estrazione dente complesso", 150],
    ["Estrazione dente semplice", 90],
    ["Estrazione ottavo incluso", 200],
  ],
  "Protesi mobile/fissa": [
    ["Protesi totale inferiore/superiore", 1400],
    ["Protesi scheletrata", 700],
    ["Protesi ceramica/zirconio", 700],
  ],
};

document.addEventListener("DOMContentLoaded", () => {
  // Inizializza data
  document.getElementById("data-odierna").textContent =
    new Date().toLocaleDateString("it-IT");

  // Costruzione DOM ottimizzata
  const contenitore = document.getElementById("contenitore-trattamenti");
  const fragment = document.createDocumentFragment();

  Object.entries(datiTrattamenti).forEach(([gruppo, trattamenti]) => {
    const div = document.createElement("div");
    div.className = "gruppo";

    const h3 = document.createElement("h3");
    h3.textContent = gruppo;
    div.appendChild(h3);

    trattamenti.forEach(([nome, prezzo], i) => {
      const id = `${gruppo}_${i}`.replace(/\s+/g, "_");

      const container = document.createElement("div");
      container.className = "trattamento";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.className = "check";
      checkbox.id = id;
      checkbox.dataset.prezzo = prezzo;

      const label = document.createElement("label");
      label.htmlFor = id;
      label.textContent = `${nome} (${prezzo}€)`;

      const quantita = document.createElement("input");
      quantita.type = "number";
      quantita.min = 1;
      quantita.value = 1;
      quantita.className = "quantita";

      const denti = document.createElement("input");
      denti.type = "text";
      denti.className = "denti";

      container.appendChild(checkbox);
      container.appendChild(label);
      container.append(" Quantità: ", quantita, " Denti: ", denti);
      div.appendChild(container);
    });

    fragment.appendChild(div);
  });
  contenitore.appendChild(fragment);

  const totalButton = document.getElementById("total-button");
  totalButton.addEventListener("click", getTotal);

  const savePDFButton = document.getElementById("save-pdf-button");
  savePDFButton.addEventListener("click", savePDF);
});

const getTotal = () => {
  const checks = document.querySelectorAll(".check");
  let totale = 0;
  let scontrino = "=== PREVENTIVO ===\n";
  let errore = false;

  checks.forEach((checkbox) => {
    if (checkbox.checked) {
      const row = checkbox.closest(".trattamento");
      const prezzo = parseFloat(checkbox.dataset.prezzo);
      const qta = parseInt(row.querySelector(".quantita").value);
      const denti = row.querySelector(".denti").value.trim();

      if (!validaDenti(denti)) {
        mostraErrore(
          `Errore nei denti inseriti per: ${checkbox.nextElementSibling.textContent}`
        );
        errore = true;
        return;
      }

      const parziale = prezzo * qta;
      totale += parziale;
      scontrino += `✔ ${
        checkbox.nextElementSibling.textContent
      } x${qta} = ${parziale.toFixed(2)}€ (denti: ${denti})\n`;
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
  const pattern = /^(\d{2})([-,]\d{2})*$/;
  const numeri = input.split(/[-,]/).map(Number);
  return pattern.test(input) && numeri.every((n) => n >= 11 && n <= 48);
}

function mostraErrore(msg) {
  // Mostra errore in un div dedicato, evita alert()
  const erroreDiv = document.getElementById("errore");
  if (erroreDiv) {
    erroreDiv.textContent = msg;
    erroreDiv.style.display = "block";
    setTimeout(() => (erroreDiv.style.display = "none"), 4000);
  } else {
    alert(msg); // fallback
  }
}

const savePDF = () => {
  const nome = document.getElementById("nome-paziente").value || "Paziente non specificato";
  const oggi = new Date();
  const dataOra = oggi.toLocaleDateString("it-IT") + " " + oggi.toLocaleTimeString("it-IT");

  const scontrinoText = document.getElementById("scontrino").textContent;
  const righe = scontrinoText.split("\n");

  // Crea un documento in formato A4
  const doc = new jspdf.jsPDF({
    orientation: "portrait",
    unit: "mm",
    format: "a4"
  });

  const larghezzaPagina = doc.internal.pageSize.getWidth();
  let y = 20; // Posizione verticale iniziale

  // Carica un font più professionale (Courier è usato per fatture)
  doc.setFont("helvetica", "normal");

  // Intestazione
  doc.setFontSize(16);
  doc.setFont("helvetica", "bold");
  doc.text("Studio Dentistico Brusco", 15, y);
  y += 8;

  doc.setFontSize(12);
  doc.setFont("helvetica", "normal");
  doc.text("Via Murello - Padova", 15, y);
  y += 8;
  doc.text("Telefono: +39 049 123 4567", 15, y);
  y += 10;

  // Dettagli del paziente
  doc.setFont("helvetica", "bold");
  doc.text("DATI PAZIENTE", 15, y);
  y += 6;
  doc.setFont("helvetica", "normal");
  doc.text(`Nome: ${nome}`, 15, y);
  y += 6;
  doc.text(`Data e ora: ${dataOra}`, 15, y);
  y += 10;

  // Tabella dei trattamenti
  doc.setFont("helvetica", "bold");
  doc.text("DESCRIZIONE TRATTAMENTI", 15, y);
  y += 6;

  doc.setDrawColor(200, 200, 200);
  doc.line(15, y, 195, y); // linea orizzontale
  y += 4;

  doc.setFont("courier", "normal");

  righe.forEach((riga) => {
    if (riga.startsWith("✔")) {
      const match = riga.match(/✔ (.+) x(\d+) = ([\d.,]+)€ \(denti: (.+)\)/);
      if (match) {
        const descrizione = match[1] + " x" + match[2];
        const prezzo = match[3] + "€";
        const denti = match[4];

        // Scrivi la riga principale
        doc.text(`${descrizione} .......... ${prezzo}`, 15, y);
        y += 6;

        // Scrivi i denti
        doc.setFont("courier", "italic");
        doc.text(`   Denti: ${denti}`, 15, y);
        doc.setFont("courier", "normal");
        y += 6;
      }
    } else if (riga.includes("Bollo dentistico")) {
      const descrizione = "Bollo dentistico";
      const prezzo = "2.00€";
      doc.text(`${descrizione} .......... ${prezzo}`, 15, y);
      y += 6;
    } else if (riga.startsWith("Totale")) {
      y += 4;
      doc.setDrawColor(200, 200, 200);
      doc.line(15, y, 195, y); // linea separatore
      y += 6;
      doc.setFont("courier", "bold");
      doc.text(riga, 15, y);
      doc.setFont("courier", "normal");
      y += 8;
    }
  });

  // Firma paziente
  doc.setFont("helvetica", "normal");
  doc.text("Firma paziente:", 15, y);
  y += 10;
  doc.line(15, y, 100, y); // linea per firma

  // Note finali
  y += 10;
  doc.setFontSize(9);
  doc.text("In base alle normative vigenti, il bollo si applica per importi superiori a €70.", 15, y);

  // Salva il file
  doc.save(`Preventivo_${new Date().toLocaleTimeString()}_${nome.replace(/\s+/g, "_")}.pdf`);
};