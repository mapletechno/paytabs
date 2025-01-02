## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/ecommerce-store.git
    cd ecommerce-store
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

3. Set up the database:
    - Create a MySQL database.
    - Import the [db.sql](http://_vscodecontentref_/25) file into your database.

4. Configure environment variables:
    - Rename `.env.example` to [.env](http://_vscodecontentref_/26).
    - Update the [.env](http://_vscodecontentref_/27) file with your database and PayTabs credentials.

## Usage

1. Start the PHP built-in server:
    ```sh
    php -S localhost:8000 -t public
    ```

2. Open your browser and navigate to `http://localhost:8000`.

## License

This project is licensed under the MIT License. See the LICENSE file for details.