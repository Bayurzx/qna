- Create new Laravel Project
laravel new qna

~~ Try `npm cache verify`  for some npm install error ~~

`npm install`

- install this for auth
composer require laravel/ui 	
php artisan ui vue --auth  

- _running mysql on wsl2_
sudo /etc/init.d/mysql start
sudo mysql

- _on windows, creating a database with mysql_
`mysql -u root -e "CREATE DATABASE qna;"`

- _checkout to a new branch_
`git checkout -b newModel`

- _create model for db along with migration by specifying -m or --migration_
`php artisan make:model Question -m`

- _You can read about column modifiers [here](https://laravel.com/docs/8.x/migrations#column-modifiers "Laravel Migrations")_

- If you run into this **error**, it's because your data types might not be the same on the code line where you create the foreign key `user_id`
```
(errno: 150 "Foreign key constraint is incorrectly formed") (SQL: alter ta
  ble `firms` add constraint `firms_user_id_foreign` foreign key (`user_id`)
  references `users` (`id`))
```
- Simply confirm that your `uder_id` `id` `users` are the same data type and rerun migration with

```php artisan migrate:fresh```

- Learn a little about `namespace` and `use`, use helps import laravel libraries which are used as a function in some parts of `app\Models\User.php` and `app\Models\Question.php`
    - I imported `Str` to use for the slug function in `app\Models\User.php` instead of str_slug()

- Experimenting with `sentence` in tinker
  ```
  >>> $faker = Faker\Factory::create();
  >>> $faker->sentence()
  >>> rtrim($faker->sentence(),".")
  >>> $faker->paragraphs()
  >>> $faker->paragraphs(3, true)
  ```
  
- Inserting `3` records with factory in tinker
  `>>> \App\Models\User::factory(3)->create();`

- I used `\App\Models\Question::factory(rand(1,5))->make()` instead of `factory(App\Question::class, rand(1,5))->make()`

- Populated the qna db bby using 
  `php artisan migrate:fresh --seed`

  