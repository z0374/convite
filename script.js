const WHATSAPP_NUMERO = "5511999999999"; // ALTERE PARA SEU NÚMERO

function carregarTotal() {
  // Tenta carregar, mas não quebra se o PHP não estiver rodando (ex: GitHub Pages)
  fetch("confirmar.php")
    .then(r => r.json())
    .then(d => {
      if(d.total) document.getElementById("totalConfirmados").innerText = d.total;
    })
    .catch(() => console.log("Backend não disponível (modo estático)"));
}

function confirmar() {
  if (localStorage.getItem("confirmado")) {
    document.getElementById("mensagem").innerText = "🌷 Presença já confirmada!";
    return;
  }

  const nome = document.getElementById("nome").value.trim();
  const qtd = parseInt(document.getElementById("qtd").value);

  if (!nome || qtd < 1) {
    alert("Por favor, digite o nome da família.");
    return;
  }

  // Tenta salvar no backend (PHP)
  fetch("confirmar.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({ nome, qtd })
  })
  .then(r => r.json())
  .then(d => {
    // Processo de sucesso com Backend
    finalizarConfirmacao(nome, qtd);
  })
  .catch(() => {
    // Fallback: Se não tiver PHP (ex: GitHub Pages), apenas redireciona pro Zap
    finalizarConfirmacao(nome, qtd);
  });
}

function finalizarConfirmacao(nome, qtd) {
  localStorage.setItem("confirmado", "sim");
  
  const msg = encodeURIComponent(
    `🌸 *Confirmação de Presença* 🌸\n` +
    `Aniversário: Ayla Liz\n` +
    `Família: ${nome}\n` +
    `Quantidade: ${qtd} pessoas`
  );

  window.location.href = `https://wa.me/${WHATSAPP_NUMERO}?text=${msg}`;
}

function alterarQtd(valor) {
  const campo = document.getElementById("qtd");
  let atual = parseInt(campo.value);

  atual += valor;

  if (atual < 1) atual = 1;
  if (atual > 15) atual = 15; // Limite razoável

  campo.value = atual;
}

// Inicia
carregarTotal();