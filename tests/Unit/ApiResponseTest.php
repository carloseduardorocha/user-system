<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ApiResponse;

use Tests\TestCase;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiResponseTest extends TestCase
{
    public function test_format_returns_successful_response(): void
    {
        $data     = ['key' => 'value'];
        $response = ApiResponse::format(true, $data, 'Success message');

        $this->assertEquals([
            'data'    => $data,
            'success' => true,
            'message' => 'Success message',
        ], $response);
    }

    public function test_format_returns_failure_response(): void
    {
        $data     = [];
        $response = ApiResponse::format(false, $data, 'Error message', 'error_slug');

        $this->assertEquals([
            'data'       => $data,
            'success'    => false,
            'message'    => 'Error message',
            'error_slug' => 'error_slug',
        ], $response);
    }

    public function test_json_success_returns_json_response(): void
    {
        $resource = new JsonResource(['key' => 'value']);
        $response = ApiResponse::jsonSuccess($resource, 200, 'Success message');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
        $this->assertEquals('Success message', $response->getData(true)['message']);
    }

    public function test_json_error_returns_json_response(): void
    {
        $resource = new JsonResource(['key' => 'value']);
        $response = ApiResponse::jsonError($resource, 400, 'Error message');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
        $this->assertEquals('Error message', $response->getData(true)['message']);
    }

    public function test_json_exception_returns_json_response(): void
    {
        $response = ApiResponse::jsonException(500, 'Error message', 'error_slug');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Error message', $response->getData(true)['message']);
        $this->assertEquals('error_slug', $response->getData(true)['error_slug']);
    }

    public function test_json_request_validation_error_throws_exception(): void
    {
        $validator = Validator::make([], []);
        $this->expectException(HttpResponseException::class);

        ApiResponse::jsonRequestValidationError($validator);
    }

    public function test_json_request_forbidden_throws_exception(): void
    {
        $this->expectException(HttpResponseException::class);

        ApiResponse::jsonRequestForbidden();
    }
}
