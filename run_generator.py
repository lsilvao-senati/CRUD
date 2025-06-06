from crud_generator.generator import CRUDGenerator
import os
import json # For pretty printing

# Ensure output directory is clean or doesn't exist
output_dir = "test_generated_crud"
if os.path.exists(output_dir):
    import shutil
    shutil.rmtree(output_dir)
    print(f"Removed existing directory: {output_dir}")

# Initialize generator
generator = CRUDGenerator(sql_file_path="clinica.sql", output_dir=output_dir)

# Parse SQL
try:
    generator.parse_sql_file()
    print("\nParsed 'doctores' table structure:")
    print(json.dumps(generator.tables.get('doctores', {}), indent=2))
    print("\nParsed 'citas' table structure:")
    print(json.dumps(generator.tables.get('citas', {}), indent=2))

    # Proceed to generate all files
    generator.generate_all_files()
    print("CRUD generation complete.")
except Exception as e:
    print(f"An error occurred: {e}")
    # Print traceback for more details
    import traceback
    traceback.print_exc()
