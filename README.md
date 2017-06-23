# Every string will be instance of Support\Str class.
## So you can access like this.

```php
echo $name;
// 'Some Name'
echo $name->upperCase();
// 'SOME NAME'
echo $name->upperCase()->lowerCase();
// 'some name'
echo $name->camelCase();
// 'someName'
echo $name->md();
// 'md5string'
```

## Every array/collection will be instance of Support\Arr class.

```php
echo $collection->join(' ');
// joined collections
```

## Router will defined and named in doc comments

```php
// routes/web.php
// Register controllers
$app->controller(UserController::class);


// Controller/UserController.php

class UserController extends Controller {

	// this willbe implement in all methods.

	/**
	* @middleware Auth
	* @pattern /users
	*/
	__construct() {

	}
	/**
	* @method get
	* @pattern /
	* @name users.list
	*/
	public function index() {

	}

	/**
	* @method get
	* @pattern /:id
	* @name users.show
	*/
	public function show() {

	}

	/**
	* @method get
	* @pattern /:id/edit
	* @name users.edit
	*/
	public function edit() {

	}

	/**
	* @method post
	* @pattern /:id/update
	* @name users.update
	* @middleware Validate
	*/
	public function update() {
		
	}

	/**
	* @method post|get
	* @pattern /:id/delete
	* @name users.destroy
	* @middleware Auth|Confirm
	*/
	public function destroy() {
		
	}

}
```
### Access routes

```curl
cur localhost/users/
# users list
... so on ...
```


