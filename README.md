# Command lines #
- Model
+ php artisan make:model Category
+ php artisan make:model TempImage
+ php artisan make:model SubCategory
+ php artisan make:model Brand
+ php artisan make:model Product
+ php artisan make:model ProductImage

- Controller
+ php artisan make:controller Admin/AdminLoginController
+ php artisan make:controller Admin/HomeController
+ php artisan make:controller Admin/CategoryController
+ php artisan make:controller Admin/TempImagesController
+ php artisan make:controller Admin/SubCategoryController
+ php artisan make:controller Admin/BrandController
+ php artisan make:controller Admin/ProductController
+ php artisan make:controller Admin/ProductSubCategoryController

- Factory
+ php artisan make:factory CategoryFactory

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
+ Add column "role" to table "users": php artisan make:migration alter_users_table
+ php artisan make:migration create_categories_table
+ php artisan make:migration create_temp_images_table
+ php artisan make:migration create_sub_categories_table
+ php artisan make:migration create_brands_table
+ php artisan make:migration create_products_table
+ php artisan make:migration create_product_images_table

- Install librarys
+ Image Intervention: composer require intervention/image