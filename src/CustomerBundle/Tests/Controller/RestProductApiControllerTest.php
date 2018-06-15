<?php

namespace CustomerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestProductApiControllerTest extends WebTestCase
{
    public function testPproductdisplay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/PProductDisplay');
    }

}
