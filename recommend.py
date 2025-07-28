import sys

location = sys.argv[1]
budget = int(sys.argv[2])
rooms = int(sys.argv[3])

# You can replace this with LLM recommendation logic
recommendation = f"For {location} under KES {budget} with {rooms} rooms:\n" \
                 f" - Suggested Property: {rooms}-bedroom in {location} at KES {budget - 5000}/month\n" \
                 f" - Agent: John Mwangi (Real Kenya Properties)"

print(recommendation)
