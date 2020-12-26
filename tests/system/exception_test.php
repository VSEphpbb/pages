<?php
/**
*
* Pages extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\pages\tests\system;

class exception_test extends \phpbb_test_case
{
	/** @var \phpbb\language\language */
	protected $lang;

	/**
	* Get an instance of \phpbb\language\language
	*/
	public function get_language_instance()
	{
		global $phpbb_root_path, $phpEx;

		// Get instance of \phpbb\language\language (dataProvider is called before setUp(), so this must be done here)
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$lang_loader->set_extension_manager(new \phpbb_mock_extension_manager($phpbb_root_path));
		$this->lang = new \phpbb\language\language($lang_loader);
		$this->lang->add_lang('exceptions', 'phpbb/pages');
	}

	protected function setUp(): void
	{
		parent::setUp();

		$this->get_language_instance();
	}

	/**
	* Data for test_exceptions function
	*
	* @return array
	*/
	public function exceptions_test_data()
	{
		$this->get_language_instance();

		return array(
			array(
				'base',
				'EXCEPTION_FIELD_MISSING',
				$this->lang->lang('EXCEPTION_FIELD_MISSING'),
			),
			array(
				'base',
				array('EXCEPTION_OUT_OF_BOUNDS', '{foo}'),
				$this->lang->lang('EXCEPTION_OUT_OF_BOUNDS', '{foo}'),
			),
			array(
				'invalid_argument',
				array('{foo}', 'FIELD_MISSING'),
				$this->lang->lang('EXCEPTION_INVALID_ARGUMENT', '{foo}', $this->lang->lang('EXCEPTION_FIELD_MISSING')),
			),
			array(
				'out_of_bounds',
				'{foo}',
				$this->lang->lang('EXCEPTION_OUT_OF_BOUNDS', '{foo}'),
			),
			array(
				'unexpected_value',
				array('{foo}', 'TOO_LONG'),
				$this->lang->lang('EXCEPTION_UNEXPECTED_VALUE', '{foo}', $this->lang->lang('EXCEPTION_TOO_LONG')),
			),
		);
	}

	/**
	* Test some exceptions and make sure they're translated
	*
	* @dataProvider exceptions_test_data
	*/
	public function test_exceptions($exception_name, $message, $expected)
	{
		try
		{
			$exception_name = '\phpbb\pages\exception\\' . $exception_name;

			throw new $exception_name($message);
		}
		catch (\phpbb\pages\exception\base $e)
		{
			self::assertEquals($expected, $e->get_message($this->lang));
		}
	}

	/**
	* Test exception language file is being loaded
	*/
	public function test_exceptions_lang()
	{
		self::assertEquals('Required field missing', $this->lang->lang('EXCEPTION_FIELD_MISSING'));
	}
}
