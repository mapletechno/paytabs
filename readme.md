## Installation

Check working example - temporary link:
https://phpstack-1383605-5136042.cloudwaysapps.com/pytb/
1. Clone the repository:
    ```sh
    git clone https://github.com/mapletechno/paytabs.git
    cd ecommerce-store
    ```

2. Set up the database:
    - Create a MySQL database.
    - Import the [db.sql] file into your database.

3. Configure environment variables:
    - Rename `.env.example` to [.env]
    - Update the [.env] file with your database and PayTabs credentials.
    - Rename the return URL to YOUR_url/handle-payment-response.php 
    So the handle-payment-response.php file link is your return URL

4. Change access permission to the .env file
    - chmod 400 (or 440 if your server is run by another user) for.env file for security

## Usage

1. Start the PHP built-in server:
    ```sh
    php -S localhost:8000 -t public
    ```

2. Open your browser and navigate to `http://localhost:8000`.

## License

This project is licensed under the MIT License. See the LICENSE file for details.