<?php

namespace Tests\Feature;

use App\Http\Controllers\GifController;
use App\Interfaces\GifValidatorInterface;
use App\Interfaces\HttpServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class GifTest extends TestCase
{
    use DatabaseTransactions;

    public function test_getGifById_successfully()
    {        
        $httpServiceMock = Mockery::mock(HttpServiceInterface::class);
        $validatorMock = Mockery::mock(GifValidatorInterface::class);
        
        $httpServiceMock->shouldReceive('get')->andReturn(response()->json(['data' => 'mocked data']), 200);
        
        $gifController = new GifController($httpServiceMock, $validatorMock);
        
        $response = $gifController->getGifById('gif_id');
        
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', json_decode($response->content(), true));
    }

    public function test_getGifs_successfully()
    {                
        $validatorMock = Mockery::mock(GifValidatorInterface::class);
        $httpServiceMock = Mockery::mock(HttpServiceInterface::class);
        
        $validatorMock->shouldReceive('validateGetGifs')->andReturnUsing(function ($request) {            
            return Validator::make($request->all(), []);            
        });
        $validatorMock->shouldReceive('fails')->andReturn(false);
        $httpServiceMock->shouldReceive('get')->andReturn(response()->json(['data' => 'mocked data']), 200);
        
        $controller = new GifController($httpServiceMock, $validatorMock);
        
        $request = new Request(['query' => 'example', 'limit' => 5, 'offset' => 0]);
        
        $response = $controller->getGifs($request);
        
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', json_decode($response->content(), true));
    }

    public function test_getGifs_validation_error()
    {        
        $validatorMock = Mockery::mock(GifValidatorInterface::class);
        $httpServiceMock = Mockery::mock(HttpServiceInterface::class);

        $validatorMock->shouldReceive('validateGetGifs')->andReturnUsing(function ($request) {                        
            return Validator::make($request->all(), ['query' => 'required']);            
        });
               
        $controller = new GifController($httpServiceMock, $validatorMock);
        
        $request = new Request(['limit' => 10]);
        
        $response = $controller->getGifs($request);
        
        $this->assertEquals(422, $response->status());
        $this->assertJson($response->content());        
    }


    public function test_create_favourite_successfully()
    {        
        $user = User::factory()->create();

        $this->actingAs($user, 'api');
        
        $data = [
            'user_id' => $user->id,
            'alias' => 'test alias',
            'gif_id' => 'testGifId'
        ];
        
        $response = $this->postJson('/api/gifs', $data);
        
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('favourites', $data);
    }

    public function test_create_favourite_validation_error()
    {        
        $user = User::factory()->create();
        
        $this->actingAs($user, 'api');
        
        $data = [
            'user_id' => $user->id,
            'alias' => 'test alias',
        ];
        
        $response = $this->postJson('/api/gifs', $data);
        
        $response->assertStatus(422);
        
        $response->assertJsonValidationErrors(['gif_id']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
