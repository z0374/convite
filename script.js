const WHATSAPP_NUMERO = "5511999999999"; // SEU WHATS

function carregarTotal() {
  fetch("confirmar.php")
    .then(r => r.json())
    .then(d => {
      document.getElementById("totalConfirmados").innerText = d.total;
    });
}

function confirmar() {
  if (localStorage.getItem("confirmado")) {
    document.getElementById("mensagem").innerText =
      "🌷 Presença já confirmada neste aparelho!";
    return;
  }

  const nome = document.getElementById("nome").value.trim();
  const qtd = parseInt(document.getElementById("qtd").value);

if (!nome || qtd < 1) {
  alert("Preencha corretamente os dados.");
  return;
}


  fetch("confirmar.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({ nome, qtd })
  })
  .then(r => r.json())
  .then(d => {
    if (d.sucesso) {
      localStorage.setItem("confirmado", "sim");

      const msg = encodeURIComponent(
        `🌸 Confirmação de Presença 🌸
Ayla Liz • 1 aninho
Família: ${nome}
Quantidade de pessoas: ${qtd}`
      );

      window.location.href =
        `https://wa.me/${WHATSAPP_NUMERO}?text=${msg}`;
    }
  });
}

function alterarQtd(valor) {
  const campo = document.getElementById("qtd");
  let atual = parseInt(campo.value);

  atual += valor;

  if (atual < 1) atual = 1;
  if (atual > 20) atual = 20; // limite opcional

  campo.value = atual;
}


carregarTotal();
