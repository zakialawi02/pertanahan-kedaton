# Kedaton Land Parcel Map

This project is an interactive web mapping application designed to visualize land parcel data in the Kedaton area. It allows users to view land parcels on a map, switch between various basemaps, and see detailed information for each parcel by clicking on it.

## Key Features

-   **Interactive Map**: Displays land parcel data in a responsive map interface using OpenLayers.
-   **Detailed Information**: Shows comprehensive information for each land parcel upon clicking, including owner data, taxpayer details, NIB, NOP, area, and more.
-   **Multi-Basemap Support**: Users can switch between multiple basemap providers such as Bing Maps (Satellite), Mapbox (Streets), OpenStreetMap, and Esri (Terrain).
-   **Map Controls**: Includes essential map controls such as:
    -   Zoom In/Out
    -   Minimap (Overview Map)
    -   Scale Line
    -   Mouse Position Coordinates
-   **PHP Backend & PostgreSQL Database**: Parcel data is dynamically fetched from a PostgreSQL database using a PHP backend.
-   **Modern Design**: The user interface (UI) is built with Tailwind CSS for a clean and modern look.

## Technology Stack

-   **Frontend**:
    -   HTML
    -   [Tailwind CSS](https://tailwindcss.com/)
    -   [OpenLayers](https://openlayers.org/)
    -   [jQuery](https://jquery.com/)
-   **Backend**:
    -   PHP
-   **Database**:
    -   PostgreSQL with PostGIS extension

## Prerequisites

-   A web server (e.g., Apache, Nginx) with PHP support.
-   PostgreSQL Database Server.
-   Node.js and npm (Node Package Manager).

## Installation and Setup

1.  **Clone the Repository**

    ```bash
    git clone <YOUR_REPOSITORY_URL>
    cd pertanahan-kedaton
    ```

2.  **Install Frontend Dependencies**
    This project uses `npm` to manage frontend dependencies like Tailwind CSS and OpenLayers.

    ```bash
    npm install
    ```

3.  **Database Setup**

    -   Create a PostgreSQL database.
    -   Ensure you have the PostGIS extension enabled.
    -   Import your database schema.
    -   Update the database connection information in the `public/action/db_connect.php` file with your credentials.

        ```php
        <?php
        $host = 'localhost'; // Your database host
        $port = '5432'; // Your database port
        $dbname = 'your_db_name'; // Change to your database name
        $user = 'your_db_user';   // Change to your username
        $password = 'your_password'; // Change to your password

        $conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
        $dbconn = @pg_connect($conn_string);

        if (!$dbconn) {
            die('Database connection failed: ' . pg_last_error());
        }
        ```

4.  **Run the Build Process**
    This application uses Tailwind CSS for styling. You need to compile the CSS file from its source.

        -   For development (with automatic watch mode):
            npm run dev

        -   For production (files will be minified):
            npm run build

    These commands will take the source file from `src/css/input.css` and generate the output to`public/css/style.css`.

5.  **Run the Application** - Point your web server's document root to the `public/` or `pertanahan-kedaton/public/` directory. - Open the application in your browser (e.g., `http://localhost/` or `http://localhost/pertanahan-kedaton/public/`).

## Support and Donations

If you find this project useful and would like to support its further development, you can make a donation via the following platforms:

https://ko-fi.com/zakialawi

Every contribution you make is greatly appreciated. Thank you!

## License

This project is licensed under the GNU General Public License v3.0. See the [LICENSE](LICENSE) file for details.
