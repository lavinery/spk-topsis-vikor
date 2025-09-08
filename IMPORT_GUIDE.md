# Routes Import Guide

## Overview

The SPK-TOPSIS system includes a bulk import functionality for mountain routes, allowing administrators to efficiently add large datasets of routes and mountains from Excel/CSV files.

## Features

- ✅ **Excel/CSV Support**: Import from both .xlsx and .csv files
- ✅ **Automatic Mountain Creation**: Mountains are created automatically if they don't exist
- ✅ **Route Updates**: Existing routes are updated, new ones are created
- ✅ **Data Validation**: File format and required columns validation
- ✅ **Error Handling**: Clear error messages for failed imports
- ✅ **Template Download**: Pre-formatted CSV template available

## File Format Requirements

### Required Columns

| Column | Type | Description | Example |
|--------|------|-------------|---------|
| `mountain_name` | String | Name of the mountain | "Gunung Semeru" |
| `route_name` | String | Name of the route | "Jalur Ranu Kumbolo" |
| `distance_km` | Decimal | Route distance in kilometers | 12.5 |
| `elevation_gain_m` | Integer | Elevation gain in meters | 1200 |
| `slope_class` | Integer | Slope difficulty (1-5) | 3 |
| `land_cover_key` | String | Land cover type | "forest" |
| `water_sources_score` | Integer | Water availability (0-10) | 7 |
| `support_facility_score` | Integer | Facility quality (0-10) | 6 |
| `permit_required` | Boolean | Permit requirement (0/1) | 1 |
| `province` | String | Province location | "Jawa Timur" |
| `elevation_m` | Integer | Mountain peak elevation | 3676 |

### Optional Columns

All columns are technically optional, but missing data will result in null values in the database.

## Import Process

### 1. Access Import Function

1. Login as admin or editor
2. Navigate to **Admin Panel** → **Routes**
3. Scroll to the **Import Routes** section

### 2. Prepare Your File

1. Download the CSV template: [routes_import_template.csv](/templates/routes_import_template.csv)
2. Fill in your route data following the template format
3. Save as either .csv or .xlsx format

### 3. Upload and Import

1. Click **Choose File** and select your prepared file
2. Click **Import** to start the import process
3. Wait for the success/error message

## Data Processing Logic

### Mountain Creation
- Mountains are created using `firstOrCreate()` based on `mountain_name`
- If mountain exists, it's updated with new `elevation_m` and `province` data
- Mountain status is automatically set to "open"

### Route Creation
- Routes are created using `updateOrCreate()` based on `mountain_id` + `route_name`
- If route exists, all fields are updated with new data
- If route doesn't exist, a new one is created

### Data Validation
- File must be .xlsx or .csv format
- File size limit: 20MB (configurable in Nginx)
- Required columns are validated during import

## Example Data

```csv
mountain_name,route_name,distance_km,elevation_gain_m,slope_class,land_cover_key,water_sources_score,support_facility_score,permit_required,province,elevation_m
Gunung Semeru,Jalur Ranu Kumbolo,12.5,1200,3,forest,7,6,1,Jawa Timur,3676
Gunung Semeru,Jalur Ranu Pane,15.2,1400,4,forest,8,5,1,Jawa Timur,3676
Gunung Rinjani,Jalur Sembalun,8.7,1800,4,grassland,6,7,1,Nusa Tenggara Barat,3726
```

## Error Handling

### Common Errors

1. **File Format Error**
   - Message: "Import failed: The file must be a file of type: xlsx, csv"
   - Solution: Ensure file is saved as .xlsx or .csv

2. **Missing Required Columns**
   - Message: "Import failed: Column 'mountain_name' not found"
   - Solution: Check column headers match exactly

3. **Data Type Errors**
   - Message: "Import failed: Invalid data type"
   - Solution: Ensure numeric fields contain only numbers

4. **File Size Error**
   - Message: "Import failed: File too large"
   - Solution: Split large files into smaller chunks

### Success Messages

- **Success**: "Routes imported successfully."
- **Partial Success**: Import completes but some rows may have warnings

## Best Practices

### 1. Data Preparation
- Use the provided CSV template
- Validate data types before import
- Check for duplicate route names within the same mountain
- Ensure province names are consistent

### 2. File Organization
- Keep file size under 10MB for optimal performance
- Use clear, descriptive mountain and route names
- Include all available data for better recommendations

### 3. Testing
- Test with small files first
- Verify imported data in the admin panel
- Check that routes appear correctly in assessments

## Technical Details

### Import Class: `RoutesImport`
- Implements `OnEachRow` and `WithHeadingRow` interfaces
- Processes each row individually for better error handling
- Uses Laravel Excel package for file processing

### Controller: `Admin\ImportController`
- Handles file upload and validation
- Provides user feedback for success/error states
- Protected by admin/editor role middleware

### Database Operations
- Uses `firstOrCreate()` for mountains (prevents duplicates)
- Uses `updateOrCreate()` for routes (allows updates)
- All operations are wrapped in database transactions

## Troubleshooting

### Import Not Working
1. Check file format (.xlsx or .csv only)
2. Verify column headers match exactly
3. Ensure user has admin/editor role
4. Check file size (max 20MB)

### Data Not Appearing
1. Refresh the routes list
2. Check for error messages
3. Verify data in database directly
4. Check mountain creation in mountains list

### Performance Issues
1. Split large files into smaller chunks
2. Import during off-peak hours
3. Monitor server resources
4. Consider using background jobs for very large files

## Support

For technical support or questions about the import functionality:
1. Check the error messages carefully
2. Verify your data format against the template
3. Contact system administrator for database issues
4. Review server logs for detailed error information
