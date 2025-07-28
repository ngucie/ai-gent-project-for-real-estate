<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Real Estate AI Agent</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f1f3f6;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
    }

    .chat-container {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 90%;
      padding: 20px;
    }

    .chat-header {
      font-size: 24px;
      font-weight: bold;
      color: #2e86de;
      margin-bottom: 20px;
      text-align: center;
    }

    .chat-box {
      height: 250px;
      overflow-y: auto;
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 15px;
      background: #f9f9f9;
    }

    .chat-box p {
      margin: 10px 0;
    }

    .user {
      text-align: right;
      color: #333;
    }

    .agent {
      text-align: left;
      color: #2e86de;
    }

    .input-area {
      display: flex;
      gap: 10px;
    }

    input[type="text"] {
      flex: 1;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    button {
      padding: 10px 15px;
      background-color: #2e86de;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #1b4f72;
    }

    @media screen and (max-width: 480px) {
      .chat-box {
        height: 200px;
      }

      .chat-header {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="chat-container">
    <div class="chat-header">🏠 Real Estate AI Agent</div>
    <div class="chat-box" id="chatBox"></div>
    <div class="input-area">
      <input type="text" id="userInput" placeholder="Ask me anything..." />
      <button onclick="sendQuery()">Send</button>
    </div>
  </div>

  <script>
    async function sendQuery() {
      const input = document.getElementById('userInput');
      const chatBox = document.getElementById('chatBox');
      const message = input.value.trim();

      if (!message) return;

      chatBox.innerHTML += `<p class="user"><strong>You:</strong> ${message}</p>`;
      input.value = '';

      try {
        const response = await fetch('agent.php?query=' + encodeURIComponent(message));
        const data = await response.json();

        if (data.recommendation || data.result || data.response) {
          chatBox.innerHTML += `<p class="agent"><strong>Agent:</strong> ${data.recommendation || data.result || data.response}</p>`;
        } else if (data.error) {
          chatBox.innerHTML += `<p class="agent"><strong>Error:</strong> ${data.error}</p>`;
        } else {
          chatBox.innerHTML += `<p class="agent"><strong>Agent:</strong> No meaningful response.</p>`;
        }

      } catch (error) {
        chatBox.innerHTML += `<p class="agent"><strong>Error:</strong> ${error.message}</p>`;
      }

      chatBox.scrollTop = chatBox.scrollHeight;
    }
  </script>

</body>
</html>
