import sys
import json
import pymysql
import re
import ollama  # Ensure Ollama is installed and running

# Step 1: Extract location, budget, and room count from user input
def extract_input_details(user_input):
    location_match = re.search(r'in ([\w\s]+)', user_input, re.IGNORECASE)
    budget_match = re.search(r'budget of (\d+)', user_input, re.IGNORECASE)
    rooms_match = re.search(r'(\d+)\s*bedrooms?', user_input, re.IGNORECASE)

    location = location_match.group(1).strip() if location_match else ''
    budget = int(budget_match.group(1)) if budget_match else 100000
    rooms = int(rooms_match.group(1)) if rooms_match else 1

    return location, budget, rooms

# Step 2: Connect to MySQL and fetch matching properties
def fetch_properties(location, budget, rooms):
    try:
        connection = pymysql.connect(
            host='localhost',
            user='root',
            password='',
            database='realestate_ai',
            cursorclass=pymysql.cursors.DictCursor
        )
        with connection.cursor() as cursor:
            sql = """
                SELECT * FROM properties 
                WHERE location LIKE %s AND price <= %s AND rooms = %s
            """
            cursor.execute(sql, ('%' + location + '%', budget, rooms))
            return cursor.fetchall()
    except pymysql.MySQLError as e:
        raise RuntimeError(f"Database error: {e}")
    finally:
        if connection:
            connection.close()

# Step 3: Generate AI recommendation using Ollama
def generate_response_with_ollama(prompt):
    try:
        response = ollama.chat(model='llama3', messages=[
            {"role": "user", "content": prompt}
        ])
        return response.get('message', {}).get('content', 'No recommendation provided.').strip()
    except Exception as e:
        return f"Ollama Error: {e}"

# Step 4: Main entry
def main():
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'No input provided'}))
        return

    user_input = " ".join(sys.argv[1:]).strip()
    location, budget, rooms = extract_input_details(user_input)

    try:
        matches = fetch_properties(location, budget, rooms)
    except RuntimeError as db_error:
        print(json.dumps({'error': str(db_error)}))
        return

    # Compose prompt
    if matches:
        prompt = f"Found {len(matches)} properties in {location} under {budget} KES with {rooms} bedrooms:\n"
        for prop in matches[:3]:
            prompt += f"- {prop['title']} at {prop['location']} costing {prop['price']} KES with {prop['rooms']} rooms.\n"
        prompt += "Which one would you recommend and why?"
    else:
        prompt = f"No matches found in {location} for {rooms} bedrooms under {budget} KES. Recommend alternative locations or strategies."

    # Get recommendation from Ollama
    recommendation = generate_response_with_ollama(prompt)

    # Final output
    print(json.dumps({
        'input': {
            'location': location,
            'budget': budget,
            'rooms': rooms
        },
        'matches': matches,
        'recommendation': recommendation
    }, indent=2))

if __name__ == "__main__":
    main()
