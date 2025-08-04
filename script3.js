document.addEventListener("DOMContentLoaded", function () {
    const chatIcon = document.querySelector(".chat-icon");
    const chatPopup = document.getElementById("chatPopup");
    const chatCloseBtn = document.getElementById("chatCloseBtn");
    const chatInput = document.getElementById("chatInput");
    const chatSendBtn = document.getElementById("chatSendBtn");
    const chatBody = document.querySelector(".chat-body");

    chatIcon.addEventListener("click", function () {
        chatPopup.style.display = "flex";
    });

    chatCloseBtn.addEventListener("click", function () {
        chatPopup.style.display = "none";
    });

    chatSendBtn.addEventListener("click", function () {
        const message = chatInput.value;
        if (message.trim() !== "") {
            const userMessage = document.createElement("div");
            userMessage.classList.add("user-message");
            userMessage.innerHTML = `<p>${message}</p>`;
            chatBody.appendChild(userMessage);
            chatInput.value = "";

            setTimeout(() => {
                const botMessage = document.createElement("div");
                botMessage.classList.add("bot-message");
                botMessage.innerHTML = `<p>How can I assist you?</p>`;
                chatBody.appendChild(botMessage);
            }, 1000);
        }
    });
});
