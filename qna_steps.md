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
  ``` bash
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
    `factory()->create()` method will insert record to database while  `factory()->make()`generate record and store to memory

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
``` php
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
- Passed the values for status view and vote in question/index blade. 
- We used `scss` for styling, and compiled it before it could work with `npm run dev`. 
- You can check this at `webpack.mix.js` file. It shows the location the files compiled to
- Run `npm run watch` to automatically compile

##  Lesson 11. Adding votes, answers and views counter on Question item
- Did some styling learnt about scss, defining variables and using child properties in styling
- Made the answer class dynamic

##  Lesson 12. Buiding Question Form 
- Created the ask question link 
- You can serach all available route with `php artisan route:list --name=question` The name question helps to limit the search


##  Lesson 13. Buiding Question Form - Part 2  
- We will be using the `$errors` variable which is automatically defined in view blade.
- If the submit button is clicked at this stage, you get a expired error msg `419`
  - This is as a result of csrf not being defined. You can fix this by simply adding the `@csrf` directly below the `<form>` tag
  - The @ operator tells PHP to suppress error messages, so that they will not be shown. `<Sometimes a Bad Practice>`



## Lesson 14. Validating and Saving the Question
- To generate our form request function, we run...

      php artisan make:request AskQuestionRequest

  - This creates a php function file called `AskQuestionRequest` that contains two functions called `authorize()` and `rules()` `App\Http\Requests\AskQuestionRequest`

- We used the function `old()` to temporary persist our form data value if the submit fails. It's a method from the request function

## Lesson 15. Validating and Saving the Question 
- In the questionsController, we edited the store function to help us create questions with the form
  - After creating the question, we redirect to the quesiton route
- We create the a success message stored in the session.
  - The html is stored in the `_message.blade.php`
  - We include it in our `index.blade`

## Lesson 16. Updating The Question 
- Edited the `edit` funciton in QuestionsController.
- Moved a section of our form to layout and made the submit button dynamic

## Lesson 17. Updating The Question - Part 2 
- I used the `@method('PUT')` or `method_field('PUT')` function in the form body to enable `PUT` method since forms only support `POST` and `GET`

## Lesson 18. Deleting The Question - Part 2 
- I used the `@method('DELETE')` or `method_field('DELETE')` function in the form body to enable `DELETE` method since forms only support `POST` and `GET`
- We simply wrapped a form around the button to delete.


## Lesson 19. Showing The Question detail
- We need to show the question, but we also need to change the url from it's default as seen after running...
  
```

.../qna>>> php artisan route:list --name=questions


+--------+-----------+---------------------------+-------------------+--------------------------------------------------+------------+
| Domain | Method    | URI                       | Name              | Action                                           | Middleware |
+--------+-----------+---------------------------+-------------------+--------------------------------------------------+------------+
|        | GET|HEAD  | questions                 | questions.index   | App\Http\Controllers\QuestionsController@index   | web        |
|        | POST      | questions                 | questions.store   | App\Http\Controllers\QuestionsController@store   | web        |
|        | GET|HEAD  | questions/create          | questions.create  | App\Http\Controllers\QuestionsController@create  | web        |
|        | GET|HEAD  | questions/{question}      | questions.show    | App\Http\Controllers\QuestionsController@show    | web        |
|        | PUT|PATCH | questions/{question}      | questions.update  | App\Http\Controllers\QuestionsController@update  | web        |
|        | DELETE    | questions/{question}      | questions.destroy | App\Http\Controllers\QuestionsController@destroy | web        |
|        | GET|HEAD  | questions/{question}/edit | questions.edit    | App\Http\Controllers\QuestionsController@edit    | web        |
+--------+-----------+---------------------------+-------------------+--------------------------------------------------+------------+
```

- `questions.show` needs a new url
- We do this by excluding the sub-route in our resource route in `routes\web.php`
``` php
Route::resource('questions', QuestionsController::class)->except('show');
Route::get('/questions/{slug}', [App\Http\Controllers\QuestionsController::class, 'show'])->name('questions.show');
```
- **Note:** that `Route::get('questions/{slug}', "QuestionsController@show")->name('questions.show');
` wont work.

- We need to define our own model binding resolution logic by adding `Route::bind` to `boot()` in `app\Providers\RouteServiceProvider.php`
  - Read about it here -> [Customizing The Resolution Logic](https://laravel.com/docs/8.x/routing#customizing-the-resolution-logic)

- Changed the 2nd `route()` argument from `$this->id` to `$this->slug` in `app\Models\Question.php`

- We add more logic to QuestionController@show
  - First, we increment views in the db 
  - then, we return the page to the blade

- Install the `parsedown` with `composer require parsedown/laravel`
- You can free parse markup or markdown text with 
  - `{!! $question->body_html !!}` in the blade
  - ` \Parsedown::instance()->text($this->body);` in `app\Models\Question.php`

## Lesson 20. Authorizing The Question - Using Gates
- `use Illuminate\Support\Facades\Gate;` in your `app\Providers\AuthServiceProvider.php` file
- Define gates for update and delete question
  - You can use `Gate::denies` or `Gate::allows` I used both occurence
- Added the `Auth::user` method to hide button in index.blade
``` php
@if (Auth::user()->can('update-question', $question))
  // ... code
@endif
```

## Lesson 21. Authorizing The Question - Using Policies
- Create our policy class with
  ``` bash
  php artisan make:policy QuestionPolicy --model=Question
  ```

- You can add this line to `app\Policies\QuestionPolicy.php` to check if the user created the question and if it doesn't have any answer yet
``` bash
  return $user->id == $question->user_id && $question->answers < 1;
```

- Next, register the policy by mapping the model to it's respective policy
  ``` php (app\Providers\AuthServiceProvider.php)
  Question::class => QuestionPolicy::class,
  ```
- Then, we can specify which policy function from QuestionPolicy.php we wanna authorize in QuestionController
  ``` php
   $this->authorize('update', $question);
  ```
  - Note that `'update'` is the function name from `Questgit add ionPolicy.php`
  - We also didn't need to specify the user instance, because laravel would recognise from behind the scene.
- We define a _constructor function in the QuestionCOntroller class that will run first (what `__constructor` do) with the exception of the `index` and `show`

``` php
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

```

## Lesson 22. Designing Answer Schema
- First, create the answer model with `php artisan make:model Answer -m`
  - -m, --migration => Create a new migration file for the model.
  - -c, --controller Create a new controller for the model.
  - -r, --resource Indicates if the generated controller should be a resource controller
- Second, update your schema in up() function in `database\migrations\2021_08_11_230401_create_answers_table.php`
 
 ``` php
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('user_id');
            $table->text('body');
            $table->integer('votes_count')->default(0);
            $table->timestamps();
        });
    }
```
- Third, migrate your schema to your db from the schema updated.
  `php artisan migrate`

- Fourth, create a relationship of from Question model to Answer model with ...
  ``` php
    public function answers()
    {
        $this->hasMany(Answer::class); //take note of hasMany()
    }
  ``` 
  - Do the same with `User` model

- Fifth, create a relationship of from Answer model to Question model with ...
  ``` php
    public function question()
    {
        return $this->belongsTo(Question::class) //take not of belongsTo
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
  ``` 
  - Notice that this funciton is recieving from User and Question Models

- Rename the `answers` table to `answers_count`.
  - This is needed because...
  - Create a new migration with `php artisan make:migration rename_answers_in_questions_table --table=questions`
  - Add the following to the up function `$table->renameColumn('answers', 'answers_count');` to change the column name.
  - install `doctrine/dbal` with `composer require doctrine/dbal`
  - run `php artisan migrate`

## Lesson 23. Generating Fake Answers 
- Create the answer factory with `php artisan make:factory AnswerFactory`
- we do the same with QuestionFactory with AnswerFactory
- use `DB::table('students')->pluck('name');` instead of `App/User::pluck('id');`
- linked users->questions->answers in DatabaseSeeder
- Increment answers from `boot()` as shown in `app\Models\Answer.php`
  - I didn't use `$answer->question->save()` because increment automaticalaly does that
  - Also commented out `'answers_count' => rand(0,10),` in `database\factories\QuestionFactory.php` sincec its now done automatically
- Rerun the migration (fresh seed)

## Lesson 25. Displaying answers for question
- Added answers to show-blade.
- Added `getAvatarAttribute()` to user.php
  - We removed `$default` and `d` from the url query string
  - Go to [gravatar](https://en.gravatar.com/site/implement/images/php/ "PHP Image Requests") to learn more
- Note that the `N+1 query` problem occured as a result of our `$answer->user` redirection. From debug, we were able to trace it to `RouteServiceProvider.php`. 
  - simply add `with('answers.user')` before where like this

``` php
        Route::bind('slug', function ($slug) {
            return Question::with('answers.user')->where('slug', $slug)->firstOrFail();
        });
```