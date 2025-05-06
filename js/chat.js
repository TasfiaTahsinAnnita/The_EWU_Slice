function initializeLiveChat() {
    // Create chat widget
    const chatWidget = document.createElement('div');
    chatWidget.className = 'chat-widget';
    chatWidget.innerHTML = `
        <div class="chat-header">Live Chat</div>
        <div class="chat-body" id="chat-body"></div>
        <input type="text" id="chat-input" placeholder="Type a message...">
        <button onclick="sendChatMessage()">Send</button>
    `;
    document.body.appendChild(chatWidget);

    // Toggle chat visibility
    const chatHeader = chatWidget.querySelector('.chat-header');
    chatHeader.addEventListener('click', () => {
        chatWidget.classList.toggle('open');
    });

    // Simulate automated responses
    window.sendChatMessage = function() {
        const input = document.getElementById('chat-input');
        const chatBody = document.getElementById('chat-body');
        const message = input.value.trim();

        if (message) {
            chatBody.innerHTML += `<p><strong>You:</strong> ${message}</p>`;
            input.value = '';
            chatBody.scrollTop = chatBody.scrollHeight;

            // Simulate a reply after 1 second
            setTimeout(() => {
                chatBody.innerHTML += `<p><strong>Support:</strong> Thanks for reaching out! How can we help you today?</p>`;
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 1000);
        }
    };
}