<?php


namespace Test;

use App\Service\Slugify;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
    public function testSlugify()
    {
        $slugify = new Slugify();
        $this->assertEquals('je-suis-une-imbecile', $slugify->generate('Je suis une imbécile'));
        $this->assertEquals('ecae', $slugify->generate('&éçàè'));
        $this->assertEquals('coucou-ca-va-moi-cest-plutot-bien', $slugify->generate('COUCOU ça va moi c\'est plutôt bien!'));
        $this->assertEquals('phpstorm-lediteur-de-code-pour-php-a-tester', $slugify->generate('PHPStorm, l\'éditeur de code pour PHP à tester !'));
        $this->assertEquals('', $slugify->generate(''));
        $this->assertEquals('bien-ou-bien', $slugify->generate('bien-ou-bien'));
        $this->assertEquals('tiret-tiret-tiret', $slugify->generate('TIRET---TIRET---TIRET---'));
        $this->assertEquals('test-espaces', $slugify->generate('   Test espaces   '));
    }
}