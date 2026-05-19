import re
import os

def slugify(text):
    text = text.lower()
    text = re.sub(r'[^a-z0-9]+', '-', text)
    return text.strip('-')

files = [
    ('seed_02_vendor1_snackshack.sql', '1'),
    ('seed_03_vendor2_dormbites.sql', '2'),
    ('seed_04_vendor3_campuspantry.sql', '3'),
    ('seed_05_vendor4_quickmart.sql', '4'),
    ('seed_06_vendor5_freshhub.sql', '5')
]

base_dir = r"c:\Users\janss\Documents\GitHub\DormDash\source\database\init"

for filename, v_id in files:
    filepath = os.path.join(base_dir, filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    # Extract items
    item_matches = re.findall(r"\(\d+,\s*[\d.]+,\s*'([^']+)'", content)
    
    # Now find the item_images section
    images_section = re.search(r"(INSERT INTO item_images.*?VALUES\s*)(.*?);", content, re.DOTALL)
    if not images_section:
        continue
        
    prefix = images_section.group(1)
    old_values = images_section.group(2)
    
    # Let's rebuild the item_images values
    new_values_lines = []
    for i, name in enumerate(item_matches):
        slug = slugify(name)
        new_values_lines.append(f"(@v{v_id}_start + {i}, '/images/items/{slug}.jpg', NOW(), NOW())")
    
    new_values = ",\n".join(new_values_lines)
    
    new_content = content.replace(old_values, new_values)
    
    with open(filepath, 'w') as f:
        f.write(new_content)
        
    print(f"Updated {filename}")
