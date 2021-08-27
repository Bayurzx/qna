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

``` php
 Route::resource('questions', QuestionsController::class);
 ```
  *Notice we used `resource` instead of `get` because we will be performing `CRUD` ops*

- Create `QuestionsController` with `php artisan make:controller QuestionController --resource --model=Question`

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
  - **Note:** that `Route::get('questions/{slug}', "QuestionsController@show")->name('questions.show');` wont work.
  - if you are using an action from the contoller class such as `'show'` you must place them in an array.

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

## Lesson 26. Adding Vote Controls on Question and Answer 
- Worked on `show.blade.php`


## Lesson 27. Adding Vote Controls on Question and Answer 
- In the lesson, font-awesome was used for some designs.You can use...
  - FontAwesome CDN
  - Down fontawesome locally
  - or you can use npm to install
- I decided to use option 2 but will also use option 3
- For option 2 simply add  `public/css/all.min.css` and `public/webfonts/`
Decided not to use option 3 `unnecessarily complicated!!!`

## Lesson 28. Adding Vote Controls on Question and Answer Part 2
- Styling in `resources\sass\app.scss`
- We added this

``` scss
.vote-controls {
    min-width: 60px;
    margin-right: 30px;
    text-align: center;
    color: $gray-700; // this is from node_modules bootstrap

    span, a {
        display: block;
    }
    span {
        &.votes-count {
            font-size: 25px;
        }
        &.favorites-count {
            font-size: 12px;
        }
    }

    a {
        cursor: pointer;
        color: $gray-600;

        &.off, &.off:hover {
            color: $gray-500;
        } 
        
        &.favorite {
            &.favorited, &.favorited:hover {
                color: #e6c300;
            } 
        }

        &.vote-accepted {
            color: #63b47c;
        }
    }
}
```
  - Note that this scss
  - `&` represent parent property
  - This will enable distinct selection of classes
  - all variable property were either defined in `resources\sass\_variables.scss` or `node_modules bootstrap`
  - we used `npm run watch` to allow laravel autimatically intepret the `scss` in `resources\sass\app.scss` to `css` in `public\css\app.css` 

## Lesson 29. Saving The Answer 
*If there are any files starting with `_` kindly note that they are part of a file*
- Moved the answer part of `resources\views\questions\show.blade.php` to `resources\views\answers\_show.blade.php`
  - Represented it as ...
     ``` php
         @include('answers._show', [
        'answers' => $question->answers,
        'answersCount' => $question->answers_count,
    ])
     ```
     - Note that `answers` and `answersCount` replaced their associates as variables
- Created a mini form for answers in `resources\views\answers\_create.blade.php` which is included in `resources\views\questions\show.blade.php`
- Added the route in `routes\web.php` as...

``` php
Route::resource('questions.answers', AnswersController::class)->only('store', 'edit', 'update', 'destroy');
```
  - This created all 7 methods for answers `questions.answers` 
```
+--------+-----------+--------------------------------------------+---------------------------+--------------------------------------------------+----------------------------------+
| Domain | Method    | URI                                        | Name                      | Action
                         | Middleware                       |
+--------+-----------+--------------------------------------------+---------------------------+--------------------------------------------------+----------------------------------+
|        | GET|HEAD  | questions/{question}/answers               | questions.answers.index   | App\Http\Controllers\AnswersController@index     | web                              |
|        | POST      | questions/{question}/answers               | questions.answers.store   | App\Http\Controllers\AnswersController@store     | web                              |
|        | GET|HEAD  | questions/{question}/answers/create        | questions.answers.create  | App\Http\Controllers\AnswersController@create    | web                              |
|        | GET|HEAD  | questions/{question}/answers/{answer}      | questions.answers.show    | App\Http\Controllers\AnswersController@show      | web                              |
|        | PUT|PATCH | questions/{question}/answers/{answer}      | questions.answers.update  | App\Http\Controllers\AnswersController@update    | web                              |
|        | DELETE    | questions/{question}/answers/{answer}      | questions.answers.destroy | App\Http\Controllers\AnswersController@destroy   | web                              |
|        | GET|HEAD  | questions/{question}/answers/{answer}/edit | questions.answers.edit    | App\Http\Controllers\AnswersController@edit      | web                              |
+--------+-----------+--------------------------------------------+---------------------------+--------------------------------------------------+----------------------------------+
```

- Created an answer controller with `php artisan make:controller AnswersController -r -m Answer`
  - -r :: Resource
  - -m :: model(Answer)

## Lesson 30. Saving The Answer - Part 2 
- Created a form for answers and included it in show blade
- Since we included question in the route, we will be including it as show below
  
``` php
public function store(Question $question, Request $request)
{
    $request->validate([
        'body' => 'required'
    ]);

    $question->answers()->create($request->validate(['body' => 'required']) + ['user_id' => Auth::id()]);

    return back()->with('success', "Your answer has been submitted successfully");
}
```
  - Notice that the ...
    - answers was created through the question variable
    - validate was passed as an argument in the create method and it was merged with the user_id validation with `Auth`
    - the `back()` return user to the previous page

## Lesson 30. Saving The Answer - Part 3
- Created `$fillable` variable for answers in `app\Models\Answer.php`

### Note: If the user is not logged in, it will return an unhandled error

## Lesson 32. Updating The Answer 
- Added edit and delete button
- IMplemented it in AnswersCOntroller

## Lesson 34. Deleting The Answer 
- Authorize answer delete and return success message in AnswerController
- Go to `app\Models\Answer.php` to reduce answer count since it was deleted in `boot` method

## Lesson 35. Deleting The Answer - Part 2 (Best answer)
- Since we want a copy of the best answer in our db
  - We save the answer's question as `$question`, in `app\Models\Answer.php`, then...
``` php
static::deleted(function ($answer)
{
    $question = $answer->question;
    $question->decrement('answers_count');
    if ($question->best_answer_id == $answer->id ) {
        $question->best_answer_id = NULL;
        $question->save();
    }
} );
```
    - This is to compare it in a if statement
  - ... we save the `best_answer_id` as null
  - save the quesition in the DB

## Lesson 36. Deleting The Answer - Part 3 (Using add_foreign_best_answer_to_question table)
- Went back a previous commit and created a separate branch called `best_answer_id`
  - This doesn't contain the solution we had in part2
- Created a new migration called `database\migrations\2021_08_20_172556_add_foreign_best_answer_to_question.php` and ran migration
- Added our schema table as ...
``` php
Schema::table('questions', function (Blueprint $table) {
    $table->foreign('best_answer_id')
          ->references('id')
          ->on('answers')
          ->onDelete('SET NULL');
});
```
- Note that the logic in `app\Models\Answer.php` is now
``` php
        static::deleted(function ($answer)
        {
            $answer->question->decrement('answers_count');
        } );
```
- Now on deleting an answer, the best_answer_id property of questions is automatically changed to null from the DB itself

## Lesson 37. Accepting the answer as best answer 
- To mark best answer, we introduced `onclick` to `resources\views\answers\_show.blade.php` and added some `js` function
- created a hidden form to enable us post accepted answer using a new defined route
  - We used what is called a `Single Action Controller` which is a bit different since we didn't specify action name
  - This controller uses the __invoke method and calls a method we will define in `app\Models\Question.php`
- In `app\Models\Question.php`, we simply declare `best_answer_id` in the `acceptBestAnswer` method
- Now we define a policy in `app\Policies\AnswerPolicy.php` that only allows if the user's id == answer's questions's user_id

``` php
    public function accept(User $user, Answer $answer)
    {
        return $user->id == $answer->question->user_id;
    }
```
- We then go to `app\Models\Question.php` to create `acceptBestAnswer` method
- We defined a can/else/if/endif html syntax to endsure that no only the loggedin user can see the accepted answer
  
``` php
@can('accept', $answer)
  <a href="" class="{{ $answer->status }}"
      onclick="event.preventDefault(); document.getElementById('accept-answer-{{$answer->id }}').submit(); "
      title="Mark as best answer">
      <i class="fa fa-check fa-2x"></i>
      {{-- <span class="favorites-count">123</span> --}}
  </a>
  <form action="{{route('answers.accept', $answer->id) }} " id="accept-answer-{{$answer->id }}"
      method="POST" style="display: none;" >
      @csrf
  </form>
  
  @else
      @if ($answer->is_best)                           
          <a href="" class="{{ $answer->status }}"
              title="Question owner accepted it as best answer">
              <i class="fa fa-check fa-2x"></i>
          </a>
      @endif
  @endcan                                 
```
  - Note that while `is_best` was used in the answer model isBest was actually used, it seems there is a naming standard to be used

``` php 
public function getStatusAttribute()
{
    return $this->isBest() ? 'vote-accepted' : '';
}

public function getIsBestAttribute()
{
    return $this->isBest();
}

public function isBest()
{
    return $this->id == $this->question->best_answer_id;
}
```

## Lesson 39. Favoriting The Question 
- created a migration `2021_08_23_221724_create_favorites_table` with `php artisan make:migration create_favorites_table`
- in `database\migrations\2021_08_23_221724_create_favorites_table.php` we specify the table columns ['user_id', 'question_id', 'timestamps'] in favorites table and used unique to make sure they are alway unique ` $table->unique(['user_id', 'question_id']);`
  - This is what they call constraints
- ran the migration '`php artisan mugrate`'
- create a favorites method in `app\Models\User.php` which indicates that the favorites table has a belongsTomany category.
- we could specify the foreign keys that would be used in the method but was omitted since we have laravel working for us
```php
    public function favorites()
    {
        return $this->belongsToMany(User::class or Question::class, 'favorites'); //,'user_id', 'question_id');
    }
```
  - This is in both user and question models `User::class or Question::class`

- Test run on tinker
``` php
>>> $q1 = App\Models\Question::find(1) // create question 1
>>> $q2 = App\Models\Question::find(2) // create question 2
>>> $u1 = App\Models\User::find(1) // create user 1
>>> $u2 = App\Models\User::find(2) // create user 2
>>> $u3 = App\Models\User::find(3) // create user 3
>>> $u1->favorites // indicate no favorites detected
>>> $u1->favorites()->attach($q1->id) // attach favorite question 1 to user 1 
>>> $u1->load('favorites') // reload to show favorited
>>> $u1->favorites()->attach($q2) // attach favorite question 2 to user 1 (works both ways for obj or int or arrays containing int) 
>>> $u1->load('favorites') // reload to show favorited
>>> $u2->favorites()->attach([$q1->id, $q2->id]) // attach favorite question 2 to user 1 (works both ways for obj or int or arrays containing int) 
>>> $q1->favorites()->attach($u3) // As you see it also works backwards thanks to belongsToMany method in both user and question
>>> $u1->favorites()->detach($q2) // to detach or unfavorite a question
>>> $u2->favorites()->detach([$q1->id, $q2->id]) // works for arrays too, return total items affected
>>> $q1->favorites()->where('user_id', $u1->id)->count() > 0 // Check if question 1 has an favorite greater than 0 in column user_id with value of $u1->id
=> true
>>> $q2->favorites()->where('user_id', $u1->id)->count() > 0
=> false
```

-  In Questions model we add 
```php
    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0; 
    }
```
   - Notice that this is similar to `$q2->favorites()->where('user_id', $u1->id)->count() > 0`
   - Where `$this` replaces the current question model
   - `auth()->id()` replaces ` $u1->id` current user's id

- Generate seeder for our new favorites table `php artisan make:seeder UsersQuestionsAnswersTableSeeder`
  - Delete pre-existing table to prevent duplication error by calling `DB`'s delete method
  - cut and paste factory seeder that was in `database\seeders\DatabaseSeeder.php` to its own class `database\seeders\UsersQuestionsAnswersTableSeeder.php`
  - `database\seeders\FavoritesTableSeeder.php` logic count the number of people in user and randomly attach a user favorites' to a question
  - Also import `FavoritesTableSeeder` into DatabaseSeeder
- run `php artisan db:seed --class=FavoritesTableSeeder` to seed the db table
- added timestamps to the table by adding the `->withTimestamps()` method to `belongsToMany` method in both `user` and `question` models
- 

## Lesson 42. Favoriting The Question (Favorite button)
- 