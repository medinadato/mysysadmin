<?php

namespace YUMI\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * 
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertTrue($crawler->filter('html:contains("User list")')->count() > 0);
    }
}
