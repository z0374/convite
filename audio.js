  // Tenta tocar assim que a página carregar
    window.addEventListener('load', function() {
        var audio = document.getElementById("playerAudio");
        
        // Define volume para 50% (opcional, para não assustar)
        audio.volume = 0.21;
        
        var promise = audio.play();

        if (promise !== undefined) {
            promise.catch(error => {
                // Autoplay foi bloqueado pelo navegador?
                // Adiciona um evento para tocar no primeiro clique do usuário em qualquer lugar
                console.log("Autoplay bloqueado. Aguardando interação...");
                document.addEventListener('click', function() {
                    audio.play();
                }, { once: true });
            });
        }
    });