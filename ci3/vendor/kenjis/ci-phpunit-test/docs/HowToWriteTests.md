# CI PHPUnit Test for CodeIgniter 3.0

version: **v0.6.2** | 
[v0.5.0](https://github.com/kenjis/ci-phpunit-test/blob/v0.5.0/docs/HowToWriteTests.md) | 
[v0.4.0](https://github.com/kenjis/ci-phpunit-test/blob/v0.4.0/docs/HowToWriteTests.md) | 
[v0.3.0](https://github.com/kenjis/ci-phpunit-test/blob/v0.3.0/docs/HowToWriteTests.md) | 
[v0.2.0](https://github.com/kenjis/ci-phpunit-test/blob/v0.2.0/docs/HowToWriteTests.md)

## How to Write Tests

- [Introduction](#introduction)
- [Testing Environment](#testing-environment)
- [Can and Can't](#can-and-cant)
	- [MY_Loader](#my_loader)
	- [`exit()`](#exit)
	- [Reset CodeIgniter object](#reset-codeigniter-object)
	- [Hooks](#hooks)
- [Models](#models)
	- [Using Database](#using-database)
	- [Database Seeding](#database-seeding)
	- [Using PHPUnit Mock Objects](#using-phpunit-mock-objects)
- [Controllers](#controllers)
	- [Request to Controller](#request-to-controller)
	- [Request to URI string](#request-to-uri-string)
	- [Request and Use Mocks](#request-and-use-mocks)
	- [Ajax Request](#ajax-request)
	- [Examine DOM in Controller Output](#examine-dom-in-controller-output)
	- [Controller with Authentication](#controller-with-authentication)
	- [`redirect()`](#redirect)
	- [`show_error()` and `show_404()`](#show_error-and-show_404)
	- [Controller with Hooks](#controller-with-hooks)
	- [Controller with Name Collision](#controller-with-name-collision)
- [Mock Libraries](#mock-libraries)
- [Monkey Patching](#monkey-patching)
	- [Converting `exit()` to Exception](#converting-exit-to-exception)
	- [Patching Functions](#patching-functions)
	- [Patching Methods in User-defined Classes](#patching-methods-in-user-defined-classes)
- [More Samples](#more-samples)

### Introduction

Here is my advice:

* You don't have to write your business logic in your controllers. Write them in your models.
* You should test models first, and test them well.

And PHPUnit has great documentation. You should read [Writing Tests for PHPUnit](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html).

### Testing Environment

Tests always run on `testing` environment.

If you don't know well about config files and environments, see [CodeIgniter User Guide](http://www.codeigniter.com/user_guide/libraries/config.html#environments).

### Can and Can't

*CI PHPUnit Test* does not want to modify CodeIgniter files. The more you modify them, the more you get difficulities when you update CodeIgniter.

In fact, it uses a modified class and a few functions. But I try to modify as little as possible.

The core functions and a class which are modified:

* function `load_class()`
* function `is_loaded()`
* function `is_cli()`
* function `show_error()`
* function `show_404()`
* function `set_status_header()`
* class `CI_Loader`

and a helper which is modified:

* function `redirect()` in URL helper

All of them are in `tests/_ci_phpunit_test/replacing` folder.

And *CI PHPUnit Test* adds a property dynamically:

* property `CI_Output::_status`

#### MY_Loader

*CI PHPUnit Test* replaces `CI_Loader` and modifies below methods:

* `CI_Loader::model()`
* `CI_Loader::_ci_load_library()`
* `CI_Loader::_ci_load_stock_library()`

But if you place MY_Loader, your MY_Loader extends the loader of *CI PHPUnit Test*.

If your MY_Loader overrides the above methods, probably *CI PHPUnit Test* does not work correctly.

#### `exit()`

When a test exercises code that contains `exit()` or `die()` statement, the execution of the whole test suite is aborted.

For example, if you write `exit()` in your controller code, your testing ends with it.

I recommend you not to use `exit()` or `die()` in your code.

**Monkey Patching on `exit()`**

*CI PHPUnit Test* has functionality that makes all `exit()` and `die()` in your code throw `CIPHPUnitTestExitException`.

See [Monkey Patching](#monkey-patching) for details.

**`show_error()` and `show_404()`**

And *CI PHPUnit Test* has special [show_error() and show_404()](#show_error-and-show_404).

**`redirect()`**

*CI PHPUnit Test* replaces `redirect()` function in URL helper. Using it, you can easily test controllers that contain `redirect()`. See [`redirect()`](#redirect) for details.

#### Reset CodeIgniter object

CodeIgniter has a function `get_instance()` to get the CodeIgniter object (CodeIgniter instance or CodeIgniter super object).

*CI PHPUnit Test* has a new function `reset_instance()` which reset the current CodeIgniter object. After resetting, you can (and must) create a new your Controller instance with new state.

#### Hooks

If you enable CodeIgniter's hooks, hook `pre_system` is called once in PHPUnit bootstrap.

If you use `$this->request->enableHooks()` and `$this->request()`, hook `pre_controller`, `post_controller_constructor` and `post_controller` are called on every `$this->request()` to a controller.

See [Controller with Hooks](#controller-with-hooks) for details.

### Models

#### Using Database

*tests/models/Inventory_model_test.php*
~~~php
<?php

class Inventory_model_test extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->model('shop/Inventory_model');
		$this->obj = $this->CI->Inventory_model;
	}

	public function test_get_category_list()
	{
		$expected = [
			1 => 'Book',
			2 => 'CD',
			3 => 'DVD',
		];
		$list = $this->obj->get_category_list();
		foreach ($list as $category) {
			$this->assertEquals($expected[$category->id], $category->name);
		}
	}

	public function test_get_category_name()
	{
		$actual = $this->obj->get_category_name(1);
		$expected = 'Book';
		$this->assertEquals($expected, $actual);
	}
}
~~~

Test case class extends [TestCase](FunctionAndClassReference.md#class-testcase) class.

Don't forget to write `parent::setUpBeforeClass();` if you override `setUpBeforeClass()` method.

Don't forget to write `parent::tearDown();` if you override `tearDown()` method.

[$this->resetInstance()](FunctionAndClassReference.md#testcaseresetinstance) method in *CI PHPUnit Test* is a helper method to reset CodeIgniter instance and assign new CodeIgniter instance as `$this->CI`.

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/models/Category_model_test.php).

#### Database Seeding

I put [Seeder Library](../application/libraries/Seeder.php) and a sample [Seeder File](../application/database/seeds/CategorySeeder.php).

They are not installed, so if you want to use, copy them manually.

You can use them like below:

~~~php
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		$CI =& get_instance();
		$CI->load->library('Seeder');
		$CI->seeder->call('CategorySeeder');
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/models/Category_model_test.php).

#### Using PHPUnit Mock Objects

You can use `$this->getMockBuilder()` method in PHPUnit and [$this->verifyInvoked*()](FunctionAndClassReference.md#testcaseverifyinvokedmock-method-params) helper method in *CI PHPUnit Test*.

If you don't know well about PHPUnit Mock Objects, see [Test Doubles](https://phpunit.de/manual/current/en/test-doubles.html).

~~~php
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->model('Category_model');
		$this->obj = $this->CI->Category_model;
	}

	public function test_get_category_list()
	{
		// Create mock objects for CI_DB_pdo_result and CI_DB_pdo_sqlite_driver
		$return = [
			0 => (object) ['id' => '1', 'name' => 'Book'],
			1 => (object) ['id' => '2', 'name' => 'CD'],
			2 => (object) ['id' => '3', 'name' => 'DVD'],
		];
		$db_result = $this->getMockBuilder('CI_DB_pdo_result')
			->disableOriginalConstructor()
			->getMock();
		$db_result->method('result')->willReturn($return);
		$db = $this->getMockBuilder('CI_DB_pdo_sqlite_driver')
			->disableOriginalConstructor()
			->getMock();
		$db->method('get')->willReturn($db_result);

		// Verify invocations
		$this->verifyInvokedOnce(
			$db_result,
			'result',
			[]
		);
		$this->verifyInvokedOnce(
			$db,
			'order_by',
			['id']
		);
		$this->verifyInvokedOnce(
			$db,
			'get',
			['category']
		);

		// Replace property db with mock object
		$this->obj->db = $db;

		$expected = [
			1 => 'Book',
			2 => 'CD',
			3 => 'DVD',
		];
		$list = $this->obj->get_category_list();
		foreach ($list as $category) {
			$this->assertEquals($expected[$category->id], $category->name);
		}
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/models/Category_model_mocking_db_test.php).

### Controllers

#### Request to Controller

You can use [$this->request()](FunctionAndClassReference.md#testcaserequestmethod-argv-params---callable--null) method in *CI PHPUnit Test*.

*tests/controllers/Welcome_test.php*
~~~php
<?php

class Welcome_test extends TestCase
{
	public function test_index()
	{
		$output = $this->request('GET', ['Welcome', 'index']);
		$this->assertContains('<title>Welcome to CodeIgniter</title>', $output);
	}
}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Welcome_test.php).

#### Request to URI string

~~~php
	public function test_uri_sub_sub_index()
	{
		$output = $this->request('GET', 'sub/sub/index');
		$this->assertContains('<title>Page Title</title>', $output);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/sub/Sub_test.php).

#### Request and Use Mocks

You can use [$this->request->setCallable()](FunctionAndClassReference.md#request-setcallable) method in *CI PHPUnit Test*. [$this->getDouble()](FunctionAndClassReference.md#testcasegetdoubleclassname-params) is a helper method in *CI PHPUnit Test*.

~~~php
	public function test_send_okay()
	{
		$this->request->setCallable(
			function ($CI) {
				$email = $this->getDouble('CI_Email', ['send' => TRUE]);
				$CI->email = $email;
			}
		);
		$output = $this->request(
			'POST',
			['Contact', 'send'],
			[
				'name' => 'Mike Smith',
				'email' => 'mike@example.jp',
				'body' => 'This is test mail.',
			]
		);
		$this->assertContains('Mail sent', $output);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Mock_phpunit_test.php).

The function you set by `$this->request->setCallable()` runs after controller instantiation. So you can't inject mocks into controller constructor.

In that case, You can use [$this->request->setCallablePreConstructor()](FunctionAndClassReference.md#request-setcallablepreconstructor) method and [load_class_instance()](FunctionAndClassReference.md#function-load_class_instanceclassname-instance) function in *CI PHPUnit Test*.

~~~php
	public function test_index_logged_in()
	{
		$this->request->setCallablePreConstructor(
			function () {
				// Get mock object
				$auth = $this->getDouble(
					'Ion_auth', ['logged_in' => TRUE]
				);
				// Inject mock object
				load_class_instance('ion_auth', $auth);
			}
		);

		$output = $this->request('GET', 'auth_check_in_construct');
		$this->assertContains('You are logged in.', $output);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Auth_check_in_construct_test.php).

#### Ajax Request

You can use [$this->ajaxRequest()](FunctionAndClassReference.md#testcaseajaxrequestmethod-argv-params---callable--null) method in *CI PHPUnit Test*.

~~~php
	public function test_index_ajax_call()
	{
		$output = $this->ajaxRequest('GET', ['Ajax', 'index']);
		$expected = '{"name": "John Smith", "age": 33}';
		$this->assertEquals($expected, $output);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Ajax_test.php).

#### Check Status Code

You can use [$this->assertResponseCode()](FunctionAndClassReference.md#testcaseassertresponsecodecode) method in *CI PHPUnit Test*.

~~~php
		$this->request('GET', 'welcome');
		$this->assertResponseCode(200);
~~~

#### Examine DOM in Controller Output

I recommend to use [symfony/dom-crawler](http://symfony.com/doc/current/components/dom_crawler.html).

~~~php
		$output = $this->request('GET', ['Welcome', 'index']);
		$crawler = new Symfony\Component\DomCrawler\Crawler($output);
		// Get the text of the first <h1>
		$text = $crawler->filter('h1')->eq(0)->text();
~~~

See [working sample](https://github.com/kenjis/codeigniter-tettei-apps/blob/develop/application/tests/controllers/Bbs_test.php#L126-128).

#### Controller with Authentication

I recommend to use PHPUnit mock objects. [$this->getDouble()](FunctionAndClassReference.md#testcasegetdoubleclassname-params) is a helper method in *CI PHPUnit Test*.

~~~php
	public function test_index_logged_in()
	{
		$this->request->setCallable(
			function ($CI) {
				// Get mock object
				$auth = $this->getDouble(
					'Ion_auth', ['logged_in' => TRUE, 'is_admin' => TRUE]
				);
				// Inject mock object
				$CI->ion_auth = $auth;
			}
		);
		$output = $this->request('GET', ['Auth', 'index']);
		$this->assertContains('<p>Below is a list of the users.</p>', $output);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Auth_test.php).

#### `redirect()`

By default, *CI PHPUnit Test* replaces `redirect()` function in URL helper. Using it, you can easily test controllers that contain `redirect()`.

But you could still override `redirect()` using your `MY_url_helper.php`. If you place `MY_url_helper.php`, your `redirect()` will be used.

If you use `redirect()` in *CI PHPUnit Test*, you can write tests like this:

~~~php
	public function test_index()
	{
		$this->request('GET', ['Admin', 'index']);
		$this->assertRedirect('login', 302);
	}
~~~

[$this->assertRedirect()](FunctionAndClassReference.md#testcaseassertredirecturi-code--null) is a method in *CI PHPUnit Test*.

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Redirect_test.php).

**Upgrade Note for v0.4.0**

v0.4.0 has new `MY_url_helper.php`. If you use it, you must update your tests.

*before:*
~~~php
	/**
	 * @expectedException				PHPUnit_Framework_Exception
	 * @expectedExceptionCode			302
	 * @expectedExceptionMessageRegExp	!\ARedirect to http://localhost/\z!
	 */
	public function test_index()
	{
		$this->request('GET', ['Redirect', 'index']);
	}
~~~

↓

*after:*
~~~php
	public function test_index()
	{
		$this->request('GET', ['Redirect', 'index']);
		$this->assertRedirect('/', 302);
	}
~~~

#### `show_error()` and `show_404()`

You can use [$this->assertResponseCode()](FunctionAndClassReference.md#testcaseassertresponsecodecode) method in *CI PHPUnit Test*.

~~~php
	public function test_index()
	{
		$this->request('GET', ['nocontroller', 'noaction']);
		$this->assertResponseCode(404);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Nocontroller_test.php).

If you don't call `$this->request()` in your tests, `show_error()` throws `CIPHPUnitTestShowErrorException` and `show_404()` throws `CIPHPUnitTestShow404Exception`. So you must expect the exceptions. You can use `@expectedException` annotation in PHPUnit.

**Upgrade Note for v0.4.0**

v0.4.0 has changed how to test `show_error()` and `show_404()`. You must update your tests.

*before:*
~~~php
	/**
	 * @expectedException		PHPUnit_Framework_Exception
	 * @expectedExceptionCode	404
	 */
	public function test_index()
	{
		$this->request('GET', 'show404');
	}
~~~

↓

*after:*
~~~php
	public function test_index()
	{
		$this->request('GET', 'show404');
		$this->assertResponseCode(404);
	}
~~~

If you don't want to update your tests, set property `$bc_mode_throw_PHPUnit_Framework_Exception` `true` in [CIPHPUnitTestRequest](../application/tests/_ci_phpunit_test/CIPHPUnitTestRequest.php) class. But `$bc_mode_throw_PHPUnit_Framework_Exception` is deprecated.

#### Controller with Hooks

If you want to enable hooks, call [$this->request->enableHooks()](FunctionAndClassReference.md#request-enablehooks) method. It enables `pre_controller`, `post_controller_constructor`, `post_controller` hooks.

~~~php
		$this->request->enableHooks();
		$output = $this->request('GET', 'products/shoes/show/123');
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Hook_test.php).

#### Controller with Name Collision

If you have two controllers with the exact same name, PHP Fatal error stops PHPUnit testing.

In this case, you can use PHPUnit annotations `@runInSeparateProcess` and `@preserveGlobalState disabled`. But tests in a separate PHP process are very slow.

*tests/controllers/sub/Welcome_test.php*
~~~php
<?php

class sub_Welcome_test extends TestCase
{
	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_uri_sub_welcome_index()
	{
		$output = $this->request('GET', 'sub/welcome/index');
		$this->assertContains('<title>Page Title</title>', $output);
	}
}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/sub/Welcome_test.php).

### Mock Libraries

You can put mock libraries in `tests/mocks/libraries` folder. You can see [application/tests/mocks/libraries/email.php](../application/tests/mocks/libraries/email.php) as a sample.

With mock libraries, you could replace your object in CodeIgniter instance.

This is how to replace Email library with `Mock_Libraries_Email` class.

~~~php
	public function setUp()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Mail_model');
		$this->obj = $this->CI->Mail_model;
		$this->CI->email = new Mock_Libraries_Email();
	}
~~~

Mock library class name must be `Mock_Libraries_*`, and it is autoloaded.

### Monkey Patching

*CI PHPUnit Test* has three monkey patchers.

* `ExitPatcher`: Converting `exit()` to Exception
* `FunctionPatcher`: Patching Functions
* `MethodPatcher`: Patching Methods in User-defined Classes

**Note:** This functionality has a negative impact on speed of tests.

To enable monkey patching, uncomment below code in `tests/Bootstrap.php` and configure them:

~~~php
/*
require __DIR__ . '/_ci_phpunit_test/patcher/bootstrap.php';
MonkeyPatchManager::init([
	'cache_dir' => APPPATH . 'tests/_ci_phpunit_test/tmp/cache',
	// Directories to patch on source files
	'include_paths' => [
		APPPATH,
		BASEPATH,
	],
	// Excluding directories to patch
	'exclude_paths' => [
		APPPATH . 'tests/',
	],
	// All patchers you use.
	'patcher_list' => [
		'ExitPatcher',
		'FunctionPatcher',
		'MethodPatcher',
	],
	// Additional functions to patch
	'functions_to_patch' => [
		//'random_string',
	],
	'exit_exception_classname' => 'CIPHPUnitTestExitException',
]);
*/
~~~

**Upgrade Note for v0.6.0**

Add the above code (`require` and `MonkeyPatchManager::init()`) before

~~~php
/*
 * -------------------------------------------------------------------
 *  Added for CI PHPUnit Test
 * -------------------------------------------------------------------
 */
~~~

in [tests/Bootstrap.php](https://github.com/kenjis/ci-phpunit-test/blob/v0.6.2/application/tests/Bootstrap.php).

`TestCase::$enable_patcher` was removed. Please remove it.

#### Converting `exit()` to Exception

This patcher converts `exit()` or `die()` statements to exceptions on the fly.

If you have a controller like below:

~~~php
	public function index()
	{
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode(['foo' => 'bar']))
			->_display();
		exit();
	}
~~~

A test case could be like this:

~~~php
	public function test_index()
	{
		try {
			$this->request('GET', 'welcome/index');
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
		}
		$this->assertContains('{"foo":"bar"}', $output);
	}
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Exit_to_exception_test.php).

#### Patching Functions

This patcher allows replacement of global functions that can't be mocked by PHPUnit.

But it has some limitations. Some functions can't be replaced and it might cause errors.

So by default we can replace only a dozen pre-defined functions in [FunctionPatcher](https://github.com/kenjis/ci-phpunit-test/blob/v0.6.2/application/tests/_ci_phpunit_test/patcher/Patcher/FunctionPatcher.php#L35).

~~~php
	public function test_index()
	{
		MonkeyPatch::patchFunction('mt_rand', 100, 'Welcome::index');
		$output = $this->request('GET', 'welcome/index');
		$this->assertContains('100', $output);
	}
~~~

[MonkeyPatch::patchFunction()](FunctionAndClassReference.md#monkeypatchpatchfunctionfunction-return_value-class_method) replaces PHP native function `mt_rand()` in `Welcome::index` method, and it will return `100` in the test method.

You could change return value of patched function using PHP closure:

~~~php
		MonkeyPatch::patchFunction(
			'function_exists',
			function ($function) {
				if ($function === 'random_bytes')
				{
					return true;
				}
				elseif ($function === 'openssl_random_pseudo_bytes')
				{
					return false;
				}
				elseif ($function === 'mcrypt_create_iv')
				{
					return false;
				}
				else
				{
					return __GO_TO_ORIG__;
				}
			},
			'Patching_on_function'
		);
~~~

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Patching_on_function_test.php#L59-L80).

**Note:** If you call `MonkeyPatch::patchFunction()` without 3rd argument, all the functions (located in `include_paths` and not in `exclude_paths`) called in the test method will be replaced. So, for example, a function in CodeIgniter code might be replaced and it results in unexpected outcome. 

If you want to patch other functions, you can add them to [functions_to_patch](https://github.com/kenjis/ci-phpunit-test/blob/v0.6.2/application/tests/Bootstrap.php#L318) in `MonkeyPatchManager::init()`.

But there are some known limitations:

* Patched functions which have parameters called by reference don't work.
* You may see visibility errors if you pass non-public callbacks to patched functions. For example, you pass `[$this, 'method']` to `array_map()` and the `method()` method in the class is not public.

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Patching_on_function_test.php).

#### Patching Methods in User-defined Classes

This patcher allows replacement of methods in user-defined classes.

~~~php
	public function test_index()
	{
		MonkeyPatch::patchMethod(
			'Category_model',
			['get_category_list' => [(object) ['name' => 'Nothing']]]
		);
		$output = $this->request('GET', 'welcome/index');
		$this->assertContains('Nothing', $output);
	}
~~~

[MonkeyPatch::patchMethod()](FunctionAndClassReference.md#monkeypatchpatchmethodclassname-params) replaces `get_category_list()` method in `Category_model`, and it will return `[(object) ['name' => 'Nothing']]` in the test method.

See [working sample](https://github.com/kenjis/ci-app-for-ci-phpunit-test/blob/v0.6.2/application/tests/controllers/Patching_on_method_test.php).

### More Samples

Want to see more tests?

* https://github.com/kenjis/ci-app-for-ci-phpunit-test/tree/v0.6.2/application/tests
* https://github.com/kenjis/codeigniter-tettei-apps/tree/develop/application/tests
