import sys
import ollama  # pip install ollama

# Get the input text from command line arguments
user_input = sys.argv[1] if len(sys.argv) > 1 else "Hello"

try:
    response = ollama.chat(
        model='llama2',  # Use your installed model name (e.g., 'llama2', 'mistral', etc.)
        messages=[
            {"role": "user", "content": user_input}
        ]
    )
    print(response['message']['content'])
except Exception as e:
    print(f"Error using Ollama: {e}")
