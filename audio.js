// audio.js
document.addEventListener('DOMContentLoaded', function() {
    const audio = new Audio('musica.mp3');
    audio.id = 'background-music';
    audio.loop = true;
    
    // Verifica se o áudio já foi iniciado
    if (!localStorage.getItem('audioStarted')) {
        audio.play().then(() => {
            localStorage.setItem('audioStarted', 'true');
        }).catch((error) => {
            console.error('Erro ao iniciar o áudio:', error);
        });
    } else {
        // Apenas inicia o áudio se ainda não estiver tocando
        if (audio.paused) {
            audio.play().catch((error) => {
                console.error('Erro ao iniciar o áudio:', error);
            });
        }
    }
    
    // Adiciona o áudio ao corpo do documento
    document.body.appendChild(audio);
});
