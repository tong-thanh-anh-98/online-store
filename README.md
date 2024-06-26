# Command lines #
- Model
* Admin
+ php artisan make:model Category
+ php artisan make:model TempImage
+ php artisan make:model SubCategory
+ php artisan make:model Brand
+ php artisan make:model Product
+ php artisan make:model ProductImage
* Front
+ php artisan make:model Country
+ php artisan make:model CustomerAddress
+ php artisan make:model OrderItem

- Controller
* Admin
+ php artisan make:controller Admin/AdminLoginController
+ php artisan make:controller Admin/HomeController
+ php artisan make:controller Admin/CategoryController
+ php artisan make:controller Admin/TempImagesController
+ php artisan make:controller Admin/SubCategoryController
+ php artisan make:controller Admin/BrandController
+ php artisan make:controller Admin/ProductController
+ php artisan make:controller Admin/ProductSubCategoryController
+ php artisan make:controller Admin/ProductImageController
* Front
+ php artisan make:controller FrontController
+ php artisan make:controller StoreController
+ php artisan make:controller CartController
+ php artisan make:controller AuthController

- Factory
+ php artisan make:factory CategoryFactory
+ php artisan make:factory ProductFactory

- DB Seed
+ php artisan make:seeder CountrySeeder
+ php artisan db:seed --class=CountrySeeder
+ php artisan db:seed

- Create user account with tinker:
+ php artisan tinker
{
    $model = new User();
    $model->name = "Admin";
    $model->email = "admin@gmail.com";
    $model->password = Hash::make("123456789");
    $model->role = 2; // role == 2 (Admin), role == 1 (User)
    $model->save();
}

- Database
* Admin
+ Add column "role" to table "users": php artisan make:migration alter_users_table
+ php artisan make:migration create_categories_table
+ php artisan make:migration create_temp_images_table
+ php artisan make:migration create_sub_categories_table
+ php artisan make:migration create_brands_table
+ php artisan make:migration create_products_table
+ php artisan make:migration create_product_images_table
* Front
+ php artisan make:migration alter_categories_table
+ php artisan make:migration alter_products_table
+ php artisan make:migration alter_sub_categories_table
+ php artisan make:migration alter_users_table
+ php artisan make:migration create_countries_table
+ php artisan make:migration create_orders_table
+ php artisan make:migration create_order_items_table
+ php artisan make:migration create_customer_addresses_table

- Install librarys
+ Image Intervention: composer require intervention/image
+ Database Abstraction Layer: composer require doctrine/dbal
+ composer require hardevine/shoppingcart

+ File composer.json:
+ Add "files" after "autoload"/"psr-4" then run the command: composer dump-autoload
-   "files": [
        "app/Helpers/helpers.php"
    ]
- Then run the command: composer dump-autoload