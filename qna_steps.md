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
  - This will drop all tables and rerun all migrations

- Learn a little about `namespace` and `use`, use helps import laravel libraries which are used as a function in some parts of `app\Models\User.php` and `app\Models\Question.php`
    - I imported `Str` to use for the slug function in `app\Models\User.php` instead of str_slug()

## **Lesson 5. Generating Fake data via Model Factories 1 & 2**

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
## Note:
    `factory()->create()` method will insert record to database while  `factory()->make()`generate reord and store to memory

- Populated the qna `mysql db` with fake data by using logic written in database\factories through database seeder.
  - Note that definition() was used instead of define in the UserFactory file (laravel 8)
`php artisan migrate:fresh --seed`

- Created QuestionFactory with :
  `php artisan make:factory QuestionFactory`

  - `paragraphs()` takes a 2nd args of true to indicate string instead of array


## **Lesson 7. Displaying all questions**

- Created new route in `routes\web.php`

```
 Route::resource('questions', QuestionsController::class);
 ```
  *Notice we used `resource` instead of `get` because we will be performing `CRUD` ops*

- Create `QuestionsController` with `php artisan make:controller QuestionController --rersource --model=Question`

  - If you open `QuestionsController` you will notice that all `CRUD` options were created because of the --resource flag and Question model was used.
    
    - index
    - create
    - store
    - show
    - edit
    - update
    - destory

- Create variable to hold questions and use the paginate(5 questions) method and return **questions** variable questions view(resources\views\questions)
```
$questions = Question::latest()->paginate(3);

return view('questions.index', compact('questions'));
```

- Customise pagination view(`{{ $questions->links() }}`) generated from `Question::latest()->paginate(3)` function with:

      php artisan vendor:publish --tag=laravel-pagination


##  Lesson 8. Adding Author info and Question creation date on Question item

- We created created_date method for question(`app\Models\Question.php`) to be used in the blade. We also createed one for url but it's empty for now

- We fixed the `N+1 query problem` by using the `with('user')` method in `app\Http\Controllers\QuestionsController.php:18,32`.
  - This issue causes lazy loading as seen when multiple calls are made to the DB after dumping with `dd`.
  - Make sure you are able to debug with `dd()` by installing...

        composer require barryvdh/laravel-debugbar --dev

##  Lesson 10. Adding votes, answers and views counter on Question item 
