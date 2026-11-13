<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Symfony підставляє в тестові запити «Accept-Language: en-us», а сайт обирає мову за браузером.
        // За замовчуванням тести — це гравець з українським браузером; інші мови перевіряє LocalizationTest.
        $this->withHeader('Accept-Language', 'uk-UA,uk;q=0.9');
    }
}
