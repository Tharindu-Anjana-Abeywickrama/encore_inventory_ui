Setup Instructions
Download or Git Clone
git clone https://github.com/Tharindu-Anjana-Abeywickrama/encore_inventory_ui.git

Unzip the project folder (if downloaded as ZIP)

Run composer install

Make sure PHP version is 8.2

Run php artisan migrate

When prompted with:

The database 'encore_system001' does not exist on the 'mysql' connection.  
Would you like to create it? (yes/no) [yes]
Type yes

Run php artisan db:seed --class=InventorySeeder

Run the following commands:

php artisan storage:link  
php artisan serve