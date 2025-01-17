<?php

/**
 * Test: Nette\Forms HTTP data.
 */

declare(strict_types=1);

use Nette\Forms\Form;
use Nette\Forms\Validator;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


before(function () {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_GET = $_POST = $_FILES = [];
});


test('', function () {
	$form = new Form;
	$form->addSubmit('send', 'Send');

	Assert::truthy($form->isSubmitted());
	Assert::true($form->isSuccess());
	Assert::same([], $form->getHttpData());
	Assert::same([], $form->getValues(true));
});


test('', function () {
	$form = new Form;
	$form->setMethod($form::GET);
	$form->addSubmit('send', 'Send');

	Assert::false($form->isSubmitted());
	Assert::false($form->isSuccess());
	Assert::same([], $form->getHttpData());
	Assert::same([], $form->getValues(true));
});


test('', function () {
	$name = 'name';
	$_POST = [Form::TRACKER_ID => $name];

	$form = new Form($name);
	$form->addSubmit('send', 'Send');

	Assert::truthy($form->isSubmitted());
	Assert::same([Form::TRACKER_ID => $name], $form->getHttpData());
	Assert::same([], $form->getValues(true));
	Assert::same($name, $form[Form::TRACKER_ID]->getValue());
});


test('', function () {
	$form = new Form;
	$input = $form->addSubmit('send', 'Send');
	Assert::false($input->isSubmittedBy());
	Assert::false(Validator::validateSubmitted($input));

	$_POST = ['send' => ''];
	$form = new Form;
	$input = $form->addSubmit('send', 'Send');
	Assert::true($input->isSubmittedBy());
	Assert::true(Validator::validateSubmitted($input));
});
