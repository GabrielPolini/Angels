function hack() {
    const output = document.getElementById('output');
    output.innerHTML = '';

    let commands = [
        'Connecting to server...',
        'Connection established...',
        'Bypassing firewall...',
        'Firewall bypassed...',
        'Accessing secret data...',
        'Data accessed: AngelsMC plans retrieved.',
        'Mission accomplished.'
    ];

    let i = 0;
    let interval = setInterval(() => {
        if (i < commands.length) {
            output.innerHTML += commands[i] + '<br>';
            i++;
        } else {
            clearInterval(interval);
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 1000);
        }
    }, 1000);
}
