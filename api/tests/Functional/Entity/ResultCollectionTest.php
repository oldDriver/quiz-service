<?php
namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultCollectionTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
    private string $testUrl = '/results';
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }
    
    /**
     * @test
     * @group Functional
     * @group Result
     */
    public function collection()
    {
        // anonymous
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        // user without results
        $client = $this->getJwtClient(100);
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(0, $client->getResponse()->toArray()['hydra:member']);
        // user with result
        $client = $this->getJwtClient(777);
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        // editor
//         $client = $this->getJwtClient(null, ['ROLE_EDITOR']);
//         $client->request(Request::METHOD_GET, $this->testUrl);
//         $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        // admin 
        $client = $this->getJwtClient(null, ['ROLE_ADMIN']);
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        
        
    }
}
