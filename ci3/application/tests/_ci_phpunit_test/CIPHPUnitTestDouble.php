<?php
/**
 * Part of CI PHPUnit Test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

use PHPUnit\Framework\TestCase;
class CIPHPUnitTestDouble
{
	protected $testCase;
	public function __construct(TestCase $testCase)
	{
		$this->testCase = $testCase;
	}

	/**
	 * Get Mock Object
	 *
	 * $email = $this->getMockBuilder('CI_Email')
	 *	->setMethods(['send'])
	 *	->getMock();
	 * $email->method('send')->willReturn(TRUE);
	 *
	 *  will be
	 *
	 * $email = $this->getDouble('CI_Email', ['send' => TRUE]);
	 *
	 * @param  string $classname
	 * @param  array  $params    [method_name => return_value]
	 * @return object PHPUnit mock object
	 */
	public function getDouble($classname, $params)
	{
		$methods = array_keys($params);

		$mock = $this->testCase->getMockBuilder($classname)->setMethods($methods)
			->getMock();

		foreach ($params as $method => $return)
		{
			$mock->method($method)->willReturn($return);
		}

		return $mock;
	}

	protected function _verify($mock, $method, $expects, $with, $params = null)
	{
		$invocation = $mock->expects($expects)
			->method($method);

		$count = count($params);

		switch ($count) {
			case 0:
				break;
			case 1:
				$invocation->$with(
					$params[0]
				);
				break;
			case 2:
				$invocation->$with(
					$params[0], $params[1]
				);
				break;
			case 3:
				$invocation->$with(
					$params[0], $params[1], $params[2]
				);
				break;
			case 4:
				$invocation->$with(
					$params[0], $params[1], $params[2], $params[3]
				);
				break;
			case 5:
				$invocation->$with(
					$params[0], $params[1], $params[2], $params[3], $params[4], $params[5]
				);
				break;
			default:
				throw new RuntimeException(
					'Sorry, ' . $count . ' params not implemented yet'
				);
		}
	}

	/**
	 * Verifies that method was called exactly $times times
	 *
	 * $loader->expects($this->exactly(2))
	 * 	->method('view')
	 * 	->withConsecutive(
	 *		['shop_confirm', $this->anything(), TRUE],
	 * 		['shop_tmpl_checkout', $this->anything()]
	 * 	);
	 *
	 *  will be
	 *
	 * $this->verifyInvokedMultipleTimes(
	 * 	$loader,
	 * 	'view',
	 * 	2,
	 * 	[
	 * 		['shop_confirm', $this->anything(), TRUE],
	 * 		['shop_tmpl_checkout', $this->anything()]
	 * 	]
	 * );
	 *
	 * @param object $mock   PHPUnit mock object
	 * @param string $method
	 * @param int    $times
	 * @param array  $params arguments
	 */
	public function verifyInvokedMultipleTimes($mock, $method, $times, $params = null)
	{
		$this->_verify(
			$mock, $method, $this->testCase->exactly($times), 'withConsecutive',$params
		);
	}

	/**
	 * Verifies a method was invoked at least once
	 *
	 * @param object $mock   PHPUnit mock object
	 * @param string $method
	 * @param array  $params arguments
	 */
	public function verifyInvoked($mock, $method, $params = null)
	{
		$this->_verify(
			$mock, $method, $this->testCase->atLeastOnce(), 'with',$params
		);
	}

	/**
	 * Verifies that method was invoked only once
	 *
	 * @param object $mock   PHPUnit mock object
	 * @param string $method
	 * @param array  $params arguments
	 */
	public function verifyInvokedOnce($mock, $method, $params = null)
	{
		$this->_verify(
			$mock, $method, $this->testCase->once(), 'with',$params
		);
	}

	/**
	 * Verifies that method was not called
	 *
	 * @param object $mock   PHPUnit mock object
	 * @param string $method
	 * @param array  $params arguments
	 */
	public function verifyNeverInvoked($mock, $method, $params = null)
	{
		$this->_verify(
			$mock, $method, $this->testCase->never(), 'with',$params
		);
	}
}
