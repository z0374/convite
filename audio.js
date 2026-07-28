document.addEventListener('DOMContentLoaded', function() {
    var audio = document.getElementById("playerAudio");
    
    // --- CONFIGURAÇÃO DO TEMPO DE INÍCIO ---
    // Defina aqui em que segundo a música deve começar na primeira vez
    var TEMPO_INICIO = 14; // Exemplo: Começa aos 25 segundos
    // ---------------------------------------

    if (!audio) return;

    audio.volume = 0.5;
    audio.loop = true; // O loop nativo sempre volta para o 0:00

    // Função que aplica o tempo inicial
    var aplicarTempoInicial = function() {
        // Verifica se o áudio já tem metadados (duração) carregados
        if(audio.readyState >= 1) {
            audio.currentTime = TEMPO_INICIO;
        } else {
            // Se não carregou ainda, espera carregar para pular o tempo
            audio.addEventListener('loadedmetadata', function() {
                audio.currentTime = TEMPO_INICIO;
            }, { once: true });
        }
    };

    // Aplica o salto de tempo
    aplicarTempoInicial();

    // --- LÓGICA DE AUTOPLAY (IGUAL ANTES) ---
    var tentarTocar = function() {
        var promessa = audio.play();

        if (promessa !== undefined) {
            promessa.then(_ => {
                console.log("Áudio tocando a partir de: " + TEMPO_INICIO + "s");
                removerOuvintes();
            }).catch(error => {
                console.log("Autoplay bloqueado. Aguardando toque...");
            });
        }
    };

    var destravarAudio = function() {
        // Nota: No clique, ele vai tocar de onde parou (do tempo configurado)
        audio.play();
        removerOuvintes();
    };

    var removerOuvintes = function() {
        document.removeEventListener('click', destravarAudio);
        document.removeEventListener('touchstart', destravarAudio);
        document.removeEventListener('scroll', destravarAudio);
    };

    tentarTocar();

    document.addEventListener('click', destravarAudio);
    document.addEventListener('touchstart', destravarAudio);
    document.addEventListener('scroll', destravarAudio);
});