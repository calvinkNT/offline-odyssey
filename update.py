import fitz
import json
import os
import base64
from bs4 import BeautifulSoup

downloads_folder = "downloads"

if "y" in input("Are the game HTML files in the /games directory? [y/n]\n> "):
    pass
else:
    exit()

# Loop through files in the folder
for filename in os.listdir(downloads_folder):
    if filename.endswith('.html'):
        original_path = os.path.join(downloads_folder, filename)

        # Strip extension and encode the name using URL-safe base64
        name_without_ext = os.path.splitext(filename)[0]
        encoded_name = base64.urlsafe_b64encode(name_without_ext.encode('utf-8')).decode('utf-8')
        new_filename = f"{encoded_name}.html"
        new_path = os.path.join(downloads_folder, new_filename)

        # Rename file
        os.rename(original_path, new_path)
        print(f"✅ Renamed: {filename} → {new_filename}")

print("🎉 All files renamed using URL-safe Base64.")

if "y" in input("Is the Ultimate Game Stash PDF in this directory and renamed as UGS.pdf? [y/n]\n> "):
    pass
else:
    exit()

# Paths
pdf_path = "UGS.pdf"
html_folder = "downloads"
output_json = "games_data.json"

games = []

# Read the PDF
with fitz.open(pdf_path) as doc:
    for page in doc:
        lines = page.get_text().split('\n')
        for line in lines:
            if ':' in line and '.html' in line:
                try:
                    name_part, file_part = line.split(':', 1)
                    html_file = file_part.strip()
                    raw_id = os.path.splitext(html_file)[0]
                    full_path = os.path.join(html_folder, html_file)

                    # URL-safe Base64 encoding
                    encoded_id = base64.urlsafe_b64encode(raw_id.encode('utf-8')).decode('utf-8')

                    # Format name in Title Case
                    name = name_part.strip().title()

                    # Try extracting title from HTML
                    if os.path.exists(full_path):
                        with open(full_path, 'r', encoding='utf-8', errors='ignore') as f:
                            soup = BeautifulSoup(f, 'html.parser')
                            title = soup.title.string if soup.title else None
                            if title:
                                name = title.strip().title()

                    games.append({
                        "id": encoded_id,
                        "name": name
                    })
                except Exception as e:
                    print(f"Error parsing line: {line}\n{e}")

# Write JSON
with open(output_json, 'w', encoding='utf-8') as f:
    json.dump(games, f, indent=4)

print(f"✅ Done! Extracted {len(games)} entries to {output_json} using URL-safe Base64 IDs.")
from time import sleep
sleep(9999)
