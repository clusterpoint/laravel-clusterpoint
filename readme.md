# Clusterpoint 4.x PHP Client API - Laravel Package

[![Latest Stable Version](https://poser.pugx.org/clusterpoint/laravel-clusterpoint/v/stable)](https://packagist.org/packages/clusterpoint/laravel-clusterpoint) [![Total Downloads](https://poser.pugx.org/clusterpoint/laravel-clusterpoint/downloads)](https://packagist.org/packages/clusterpoint/laravel-clusterpoint) [![Latest Unstable Version](https://poser.pugx.org/clusterpoint/laravel-clusterpoint/v/unstable)](https://packagist.org/packages/clusterpoint/laravel-clusterpoint) [![License](https://poser.pugx.org/clusterpoint/laravel-clusterpoint/license)](https://packagist.org/packages/clusterpoint/laravel-clusterpoint)

## Official Documentation

Documentation for the API can be found on the [Clusterpoint website](https://www.clusterpoint.com/docs/api/4/php/345/overview).

## Install
1. **Install** the package.  
``composer require clusterpoint/laravel-clusterpoint``
1. **Register** Service Provider in your `config/app.php` file.  
``Clusterpoint\ClusterpointServiceProvider::class``
1. **Publish** config file.  
``php artisan vendor:publish --provider="Clusterpoint\ClusterpointServiceProvider"``  
1. **Edit config** – The main config file path is  
  ``config/clusterpoint.php``  
however we recommend to add your credentials to your .env file in laravel project root directory.
```
CP_HOST=https://api-eu.clusterpoint.com/v4  
CP_ID=42  
CP_USERNAME=myusername@clusterpoint.com  
CP_PASSWORD=mypassword  
```

## Usage examples
* [Client Usage Example](#client)
* [Model Usage Example](#model)
* [Route Model Binding Example](#route)
* [Multiple connections Example](#connect)


<a name="client"></a>
##Client Usage Example
Here you can see standart Laravel Controller that uses our service.
```PHP
<?php

namespace App\Http\Controllers;

use Clusterpoint\Client; // use our package.

class ExampleController extends Controller
{
    public function getIndex() {
		
		$cp = new Client(); // by defualt uses 'default' connection name from ./config/clusterpoint.php
		
		// Set the collection to work with to initalize the query builder for it.
		$collection = $cp->database("database.collection");

		// Build your query
		$results = $collection->where('color', 'red')
			->where('availability', true)
			->limit(5)
			->groupBy('category')
			->orderBy('price')
			->select(['name', 'color', 'price', 'category'])
			->get();
			
		// Access your results
		return $results[0]->price;
	}
}
```

<a name="model"></a>
##Model Usage Example
First create your model in `app` folder:    
```PHP
<?php

namespace App;

use Clusterpoint\Model;

class Example extends Model
{
	protected $db = "database.collection"; // set your databse and collection names
	//protected $primaryKey = "custom_id"; // If you want to define specific specific primary key, default = _id
}
```

Now you can use model inside the controller.
```PHP
<?php

namespace App\Http\Controllers;

use App\Example; // use your model.

class ExampleController extends Controller
{	
    public function getIndex() {
		$example = Example::where('price', '>', 200)->first();
		
		$example->price = 300;
		$example->save();
		
		return view('example', compact('example'));
	}
}
```
<a name="route"></a>
##Route Model Binding Example
We will use model created above to show this example, first bind your model in `app/Http/routes.php` file like this:  
```PHP
<?php

Route::model('example', 'App\Example');
Route::get('/examples/{example}', 'ExampleController@getIndex');
```
Now if you pass the primary key value in your url like `myweb.dev/examples/42` you can access the document already inside your controller like this. 
```PHP
<?php

namespace App\Http\Controllers;

use App\Example;

class ExampleController extends Controller
{
	public function getIndex($example) {
		$id = $example->_id; // value is 42
		$name = $example->name;
		$price = $example->price;
		return view('example', compact('name','price'));
	}
}
```

<a name="connect"></a>
##Multiple connections Example
You can set multiple connections and use what you needed in the Model.

First add a connection setting array in the main config file ``config/clusterpoint.php``.
For example a connection named "test"
```PHP
<?php

return array(
  "default" => array(
    'host' => env('CP_HOST', 'https://api-eu.clusterpoint.com/v4'),
    'account_id' => env('CP_ID', ''),
    'username' => env('CP_USERNAME', ''),
    'password' => env('CP_PASSWORD', ''),
  ),
  "test" => array(
    'host' => env('CP1_HOST', 'https://api-eu.clusterpoint.com/v4'),
    'account_id' => env('CP1_ID', ''),
    'username' => env('CP1_USERNAME', ''),
    'password' => env('CP1_PASSWORD', ''),
  )
);

```

However we recommend you to add your credentials to your .env file in the laravel project root directory.
```
CP1_HOST=https://api-eu.clusterpoint.com/v4  
CP1_ID=42  
CP1_USERNAME=myusername@clusterpoint.com  
CP1_PASSWORD=mypassword  
```
Now You can use the connection in Your Model:
```PHP
<?php

namespace App;

use Clusterpoint\Model;

class Example extends Model
{
	protected $connection = "test";
}
```


<a name="bugs"></a>
## Support, Feature Requests & Bug Reports

* [GitHub issues](https://github.com/clusterpoint/laravel-clusterpoint/issues) for bug reports and feature requests
* [StackOverflow](https://stackoverflow.com) to ask questions (please make sure to use the [clusterpoint](http://stackoverflow.com/questions/tagged/clusterpoint) tag)
* You can also send an e-mail to our support team at support@clusterpoint.com

<a name="license"></a>
## License

Clusterpoint 4.0 PHP Client API - Laravel Package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)