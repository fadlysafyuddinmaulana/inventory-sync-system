# DataTables Integration - Quick Guide

## Features Enabled

### **Table Features**

| Feature         | Status | Details                                       |
| --------------- | ------ | --------------------------------------------- |
| Sorting         | ✅     | Click headers to sort (Name, SKU, Price, Qty) |
| Search          | ✅     | Real-time filter by product name/SKU          |
| Pagination      | ✅     | 10, 25, 50, 100, or all rows per page         |
| Responsive      | ✅     | Works on mobile, tablet, and desktop          |
| Export to Excel | ✅     | Download as `.xlsx` file                      |
| Export to PDF   | ✅     | Generate printable PDF report                 |
| Print           | ✅     | Printer-friendly formatting                   |

---

## User Guide

### **Sorting**

- Click any column header to sort ascending/descending
- Image and Action columns are not sortable

### **Searching**

- Type in the search box to filter products by name or SKU
- Real-time results as you type

### **Pagination**

- **Page Length**: Select 10, 25, 50, 100, or All
- **Navigation**: Use Previous/Next buttons or jump to specific page
- **Info**: Shows "Showing X to Y of Z products"

### **Export Data**

1. Click **Excel** button → Downloads `.xlsx` file
2. Click **PDF** button → Opens PDF in new window
3. Click **Print** button → Opens print preview

---

## Configuration

### **Current Settings**

```javascript
// Default page length
pageLength: 10;

// Available page lengths
lengthMenu: [10, 25, 50, 100, -1]; // -1 = All

// Default sorting
order: [[1, "asc"]]; // Sort by Product Name ascending

// Non-sortable columns
columnDefs: [
    { orderable: false, targets: 0 }, // Image
    { orderable: false, targets: 5 }, // Action
];
```

### **Customizing**

To change the default page length, edit `resources/views/products/index.blade.php`:

```javascript
pageLength: 10,  // Change this number (10, 25, 50, 100)
```

---

## Display Layout

```
┌─────────────────────────────────────────────────┐
│  Entries ▼  [Search]  [Excel] [PDF] [Print]   │
├─────────────────────────────────────────────────┤
│  Image | Name | SKU | Price | Qty | Action   │
├─────────────────────────────────────────────────┤
│  [Product rows...]                              │
├─────────────────────────────────────────────────┤
│  Showing 1 to 10 of 50...   Previous | 1 2 3 | Next
└─────────────────────────────────────────────────┘
```

---

## Browser Compatibility

| Browser | Status             |
| ------- | ------------------ |
| Chrome  | ✅ Fully supported |
| Firefox | ✅ Fully supported |
| Safari  | ✅ Fully supported |
| Edge    | ✅ Fully supported |
| IE11    | ⚠️ Limited support |

---

## CDN Libraries Used

- **DataTables**: 2.0.8 (Latest)
- **Bootstrap Integration**: 5.0+
- **Responsive Extension**: 3.0.2
- **Buttons Extension**: 3.0.2
- **Export Libraries**:
    - JSZip 3.10.1 (Excel export)
    - PDFMake 0.2.7 (PDF export)

---

## Troubleshooting

### **Table not showing?**

- Refresh the page (Ctrl+F5)
- Check browser console for JavaScript errors
- Ensure JavaScript is enabled

### **Export buttons not working?**

- Check that CDN libraries are loading (check Network tab in DevTools)
- Verify JavaScript is enabled
- Try a different export format

### **Search not working?**

- Make sure the search box is active (has focus)
- Check that products are actually loaded
- Try refreshing the page

### **Sorting not working?**

- Click the same header again to toggle ascending/descending
- Image and Action columns cannot be sorted (by design)

---

## Performance Notes

- **Sorting**: Instant (client-side)
- **Search**: Instant (client-side)
- **Pagination**: Instant (client-side)
- **Export**: 2-5 seconds depending on data size

The DataTable processes all data client-side for responsive interaction!

---

## Next Steps

1. **Test the features** - Try sorting, searching, paginating
2. **Export data** - Test Excel/PDF/Print exports
3. **Customize** - Adjust page length or styling as needed
4. **Monitor** - Check browser console for any errors

---

_Last Updated: May 3, 2026_
