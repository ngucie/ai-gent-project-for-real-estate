import sys
import json
import pymysql

# Get the input
user_input = sys.argv[1]

# Very basic parsing (you can improve with NLP later)
import re
location = re.search(r'in (\w+)', user_input)
budget = re.search(r'budget of (\d+)', user_input)
rooms = re.search(r'(\d+) bedrooms?', user_input)

location = location.group(1) if location else ''
budget = int(budget.group(1)) if budget else 100000
rooms = int(rooms.group(1)) if rooms else 1

# Connect to MySQL
connection = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    database='realestate_ai'
)

cursor = connection.cursor(pymysql.cursors.DictCursor)
query = """
    SELECT * FROM properties 
    WHERE location LIKE %s AND price <= %s AND rooms = %s
"""
cursor.execute(query, ('%' + location + '%', budget, rooms))
results = cursor.fetchall()

print(json.dumps(results, indent=2))
