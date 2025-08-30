import os

# Function to rename the URL in the file
def rename_url_in_file(file_path, old_url, new_url):
    try:
        # Open the file in read mode
        with open(file_path, 'r', encoding='utf-8') as file:
            content = file.read()

        # Replace old URL with new URL
        updated_content = content.replace(old_url, new_url)

        # If there was a change, overwrite the file with the updated content
        if content != updated_content:
            with open(file_path, 'w', encoding='utf-8') as file:
                file.write(updated_content)
        print(f"Updated: {file_path}")

    except Exception as e:
        #print(f"Could not process {file_path}: {e}")
        pass

# Function to traverse all files in a directory and its subdirectories
def traverse_and_rename(directory, old_url, new_url):
    for root, _, files in os.walk(directory):
        for file in files:
            file_path = os.path.join(root, file)
            rename_url_in_file(file_path, old_url, new_url)


if "1" in input("Are you first-time setup [1] or moving domains/paths [2]?\n> ").lower():

	folder_path = "./source" 
	old_url = "oo-pass"
	new_url = input("What is the directory you will host on? (e.g. example.com/oo, you enter 'oo'\n> ")

	traverse_and_rename(folder_path, old_url, new_url)
	
	print("Updated directory")

	folder_path = "./source" 
	old_url = "jscdn.ct.ws"
	new_url = input("What is the domain you will host on? (e.g. https://example.com, you enter 'example.com'\n> ")

	traverse_and_rename(folder_path, old_url, new_url)
	
	print("Updated domain.")
	print("You should be ready to host. Remember to follow Section 10 entirely.")

else:

	print("* Make sure to copy down the whole website files (minus the assets folder) and rename it 'source' in this directory.")

	folder_path = "./source" 
	old_url = input("What is the OLD directory you were hosting on? (e.g. example.com/oo, you enter 'oo'\n> ")
	new_url = input("What is the directory you will host on/want to move? (e.g. example.com/oo, you enter 'oo'\n> ")

	traverse_and_rename(folder_path, old_url, new_url)
	
	print("Updated directory")

	folder_path = "./source" 
	old_url = input("What is the OLD domain you were hosting on? (e.g. example.com/oo, you enter 'oo'\n> ")
	new_url = input("What is the domain you will host on? (e.g. https://example.com, you enter 'example.com'\n> ")

	traverse_and_rename(folder_path, old_url, new_url)
	
	print("Updated domain.")
print("Finished execution. You are safe to exit.")
