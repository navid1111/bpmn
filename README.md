# BPMN Editor - Laravel Application

A full-featured BPMN (Business Process Model and Notation) diagram editor built with Laravel and bpmn-js. Create, edit, save, and export interactive BPMN diagrams directly in your browser.

## Features

- 🎨 **Interactive BPMN Editor** - Create and modify BPMN diagrams with drag-and-drop interface
- 💾 **Save & Load** - Store diagrams in database with full CRUD operations
- 📥 **Export** - Download diagrams as XML (.bpmn) files
- ↶↷ **Undo/Redo** - Full command stack support for editing operations
- 🔍 **Zoom Controls** - Zoom in, out, and fit to viewport
- 📋 **Diagram Management** - List, edit, and delete saved diagrams
- 🎯 **Version Control** - Track diagram versions
- 📝 **Metadata** - Add names and descriptions to diagrams

## Technology Stack

- **Backend**: Laravel 12.46.0
- **Frontend**: bpmn-js 17.0.0
- **Database**: SQLite (configurable)
- **Build Tool**: Vite 7.0.7
- **PHP**: 8.4.13

## Installation

### Prerequisites

- PHP 8.4+
- Composer
- Node.js & npm
- SQLite (or your preferred database)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd pmp
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run database migrations**
   ```bash
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run dev
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - Open your browser and navigate to: `http://localhost:8000/bpmn`

## Usage

### Creating a New Diagram

1. Navigate to `http://localhost:8000/bpmn`
2. Click **"+ Create New Diagram"**
3. Use the visual editor to design your BPMN process:
   - Drag elements from the palette
   - Connect elements with sequence flows
   - Edit element properties
   - Add labels and names
4. Enter a diagram name and optional description
5. Click **"💾 Save"** to store the diagram

### Editing Existing Diagrams

1. From the diagram list, click **"Edit"** on any diagram
2. Modify the diagram using the editor tools
3. Click **"💾 Save"** to update

### Exporting Diagrams

- **From Editor**: Click **"📥 Export XML"** to download the current diagram
- **From List**: Click **"Export"** next to any diagram

### Editor Controls

- **📄 New Diagram** - Create a fresh diagram
- **💾 Save** - Save current diagram to database
- **📥 Export XML** - Download diagram as .bpmn file
- **↶ Undo** - Undo last action
- **↷ Redo** - Redo undone action
- **🔍+ Zoom In** - Increase canvas zoom
- **🔍- Zoom Out** - Decrease canvas zoom
- **⛶ Fit** - Fit diagram to viewport

## API Endpoints

### Web Routes
- `GET /bpmn` - List all diagrams
- `GET /bpmn/create` - Create new diagram
- `GET /bpmn/{id}` - View diagram
- `GET /bpmn/{id}/edit` - Edit diagram
- `GET /bpmn/{id}/export` - Export diagram as XML

### API Routes (AJAX)
- `POST /api/bpmn` - Store new diagram
- `GET /api/bpmn/{id}` - Get diagram data
- `PUT /api/bpmn/{id}` - Update diagram
- `DELETE /api/bpmn/{id}` - Delete diagram

## Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   └── BpmnEditorController.php    # Main controller
│   └── Models/
│       └── BpmnDiagram.php              # Diagram model
├── database/
│   └── migrations/
│       └── 2026_01_14_000000_create_bpmn_diagrams_table.php
├── resources/
│   ├── js/
│   │   └── bpmn-editor.js               # BPMN editor JavaScript
│   └── views/
│       └── bpmn/
│           ├── index.blade.php          # Diagram list
│           └── editor.blade.php         # Editor interface
└── routes/
    └── web.php                          # Route definitions
```

## Database Schema

### `bpmn_diagrams` Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar | Diagram name |
| description | text | Optional description |
| xml_content | longtext | BPMN XML content |
| version | varchar | Version number |
| user_id | bigint | Creator (nullable) |
| is_published | boolean | Publication status |
| created_at | timestamp | Creation time |
| updated_at | timestamp | Last update time |
| deleted_at | timestamp | Soft delete time |

## Development

### Running in Development Mode

```bash
# Terminal 1: Start Vite dev server
npm run dev

# Terminal 2: Start Laravel server
php artisan serve
```

### Building for Production

```bash
npm run build
```

## Documentation

For detailed implementation information, see [IMPLEMENTATION.md](IMPLEMENTATION.md).

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-sourced software licensed under the MIT license.

## Credits

- Built with [Laravel](https://laravel.com/)
- BPMN editor powered by [bpmn-js](https://bpmn.io/toolkit/bpmn-js/)
- Documentation context provided by [Context7 MCP](https://context7.com/)

## Support

For issues and questions, please open an issue on the GitHub repository.
