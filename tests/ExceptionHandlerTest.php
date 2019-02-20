<?php

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionHandlerTest extends TestCase
{
    /**
     * Test for hendler exceptio.
     *
     * @return void
     */
    public function testRender()
    {
        $request = $this->createMock(Request::class);
        $instance = new Handler($this->createMock(Container::class));
        $class = new \ReflectionClass(Handler::class);
        $method = $class->getMethod('render');
        $method->setAccessible(true);

        $response = $method->invokeArgs(
            $instance,
            [
                $request,
                $this->createMock(ModelNotFoundException::class)
            ]
        );
        $this->assertEquals(
            $response->original['error']['status'],
            Response::HTTP_NOT_FOUND
        );

        $response = $method->invokeArgs(
            $instance,
            [
                $request,
                $this->createMock(NotFoundHttpException::class)
            ]
        );
        $this->assertEquals(
            $response->original['error']['status'],
            Response::HTTP_NOT_FOUND
        );

        $queryException = new QueryException('', [], new HttpException(409));
        $queryException->errorInfo = [1 => 1062];
        $response = $method->invokeArgs(
            $instance,
            [
                $request,
                $queryException
            ]
        );
        $this->assertEquals(
            $response->original['error']['status'],
            Response::HTTP_CONFLICT
        );

        $response = $method->invokeArgs(
            $instance,
            [
                $request,
                $this->createMock(MethodNotAllowedHttpException::class)
            ]
        );
        $this->assertEquals(
            $response->original['error']['status'],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
