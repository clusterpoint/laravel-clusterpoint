# Clusterpoint 4.0 PHP Client API - Laravel Package

[![License](https://poser.pugx.org/clusterpoint/php-client-api-v4/license)](https://packagist.org/packages/clusterpoint/php-client-api-v4)
[![Total Downloads](https://poser.pugx.org/clusterpoint/php-client-api-v4/downloads)](https://packagist.org/packages/clusterpoint/php-client-api-v4)
[![Latest Stable Version](https://poser.pugx.org/clusterpoint/php-client-api-v4/v/stable)](https://packagist.org/packages/clusterpoint/php-client-api-v4)
[![Latest Unstable Version](https://poser.pugx.org/clusterpoint/php-client-api-v4/v/unstable)](https://packagist.org/packages/clusterpoint/php-client-api-v4)

## Official Documentation

Documentation for the API can be found on the [Clusterpoint website](https://www.clusterpoint.com/apidoc/php-v4/).

## Install
1. **Install** the package.  
``composer require clusterpoint/php-client-api-v4-laravel-package``
1. **Register** Service Provider in your `config/app.php` file.  
``Clusterpoint\ClusterpointServiceProvider::class``
1. **Publish** config file.  
``php artisan vendor:publish --provider="Clusterpoint\ClusterpointServiceProvider"``  
1. **Edit config** – The main config file path is  
  ``config/clusterpoint.php``  
however we recommend to add your credentials to your .env file in laravel project root directory.
  ```PHP
CP_HOST=https://api-eu.clusterpoint.com/v4
CP_ID=42
CP_USERNAME=myusername@clusterpoint.com
CP_PASSWORD=mypassword
  ```

## Usage examples
* [Client Usage Example](#client)
* [Model Usage Example](#model)
* [Route Model Binding Example](#route)


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
		//Initialize the service
		$cp = new Client([
		    'host'      => 'https://api-eu.clusterpoint.com/v4',
		    'account_id'  => '42',
		    'username'  => 'myusername',
		    'password'  => 'mypassword'
		]);
		// Set the database to work with to initalize the query builder for it.
		$db = $cp->database("bikes");

		// Or if your config isset you could do just this.
		$db = Client::database("bikes");
		// Build your query
		$results = $db->where('color', 'red')
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
	
}
```
This will assume that database selected is called "examples" and primary key is "_id". If you want to define specific database to refer to or specific primary key you use there, you have to do it like this:  
```PHP
<?php

namespace App;

use Clusterpoint\Model;

class Example extends Model
{
	protected $db = "my_database_name";
	protected $primaryKey = "custom_id";
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

<a name="bugs"></a>
## Bugs and Vulnerabilities

If you discover a vulnerability or bug within our API or database functionality, please send an e-mail to our support team at info@clusterpoint.com.

<a name="license"></a>
## License

The Clusterpoint PHP client API is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)